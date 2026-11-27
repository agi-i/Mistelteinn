<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use CodeIgniter\Model;
use Config\Database;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'product_id',
        'customer_id',
        'sold_by',
        'quantity',
        'total_price',
        'created_at',
        'note',
        'payment_method',
    ];
    protected $useTimestamps = false;
}

class SaleController extends BaseController
{
    protected SaleModel $saleModel;
    protected CustomerModel $customerModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->customerModel = new CustomerModel();
        $this->productModel  = new ProductModel();
    }

    // READ: List all recorded sales
    public function index()
    {
        $sales = $this->saleModel
            ->select('sales.*, customers.full_name AS customer_name, products.name AS product_name')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('products', 'products.id = sales.product_id', 'left')
            ->orderBy('sales.id', 'DESC')
            ->findAll();

        return view('sales/index', [
            'sales' => $sales,
        ]);
    }

    // CREATE FORM: Show Record Sale screen
    public function new()
    {
        $customers = $this->customerModel->orderBy('full_name', 'ASC')->findAll();
        $products  = $this->productModel->orderBy('name', 'ASC')->findAll();

        return view('sales/form', [
            'customers' => $customers,
            'products'  => $products,
        ]);
    }

    // CREATE ACTION: Save sale row(s), log sale_items, save note & payment_method, and deduct stock
    public function create()
    {
        $productIds = $this->request->getPost('product_id') ?? [];
        $quantities = $this->request->getPost('quantity') ?? [];
        $customerIdPost = (int) ($this->request->getPost('customer_id') ?? 0);
        $customerId = $customerIdPost > 0 ? $customerIdPost : null;

        $allowedPayments = ['cash', 'card', 'ewallet'];
        $payment = strtolower(trim((string) ($this->request->getPost('payment_method') ?? 'cash')));
        if (!in_array($payment, $allowedPayments, true)) {
            $payment = 'cash';
        }

        $hasNote = (bool) $this->request->getPost('has_flower_note');
        $rawNote = trim((string) ($this->request->getPost('note') ?? ''));
        $note    = ($hasNote || $rawNote !== '') && $rawNote !== '' ? mb_substr($rawNote, 0, 500) : null;

        $itemsToSave = [];

        foreach ($productIds as $idx => $prodId) {
            $qty = (int) ($quantities[$idx] ?? 0);
            if (empty($prodId) || $qty <= 0) {
                continue;
            }

            $product = $this->productModel->find((int) $prodId);
            if (!$product) {
                continue;
            }

            if ((int) $product['stock_quantity'] < $qty) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'Insufficient stock for ' . $product['name'] . ' (Available: ' . $product['stock_quantity'] . ').'
                );
            }

            $unitPrice = (float) $product['price'];
            $lineTotal = round($unitPrice * $qty, 2);

            $itemsToSave[] = [
                'product'     => $product,
                'product_id'  => (int) $product['id'],
                'quantity'    => $qty,
                'unit_price'  => $unitPrice,
                'total_price' => $lineTotal,
            ];
        }

        if (empty($itemsToSave)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Please select a flower/product from the dropdown and enter a valid quantity.'
            );
        }

        $db = Database::connect();

        // Resolve sold_by (NOT NULL in sales table)
        $soldBy = (int) (session()->get('user_id') ?? session()->get('id') ?? 0);
        if ($soldBy <= 0 && $db->tableExists('users')) {
            $firstUser = $db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
            $soldBy = (int) ($firstUser['id'] ?? 0);
        }

        if ($soldBy <= 0) {
            return redirect()->back()->withInput()->with(
                'error',
                'Could not determine staff/user ID (sold_by) for this sale.'
            );
        }

        $hasSaleItemsTable = $db->tableExists('sale_items');
        $now = date('Y-m-d H:i:s');
        $db->transBegin();

        try {
            $insertedCount = 0;

            foreach ($itemsToSave as $item) {
                $stockUpdated = $db->table('products')
                    ->where('id', $item['product_id'])
                    ->where('stock_quantity >=', $item['quantity'])
                    ->set('stock_quantity', 'stock_quantity - ' . $item['quantity'], false)
                    ->update();

                if ($stockUpdated === false) {
                    $dbErr = $db->error();
                    throw new \RuntimeException($dbErr['message'] ?: 'Failed updating product stock.');
                }

                $saleId = $this->saleModel->insert([
                    'product_id'     => $item['product_id'],
                    'customer_id'    => $customerId,
                    'sold_by'        => $soldBy,
                    'quantity'       => $item['quantity'],
                    'total_price'    => $item['total_price'],
                    'created_at'     => $now,
                    'note'           => $note,
                    'payment_method' => $payment,
                ], true);

                if (!$saleId) {
                    $dbErr = $db->error();
                    throw new \RuntimeException($dbErr['message'] ?: 'Failed inserting into sales table.');
                }

                if ($hasSaleItemsTable) {
                    $itemInserted = $db->table('sale_items')->insert([
                        'sale_id'    => (int) $saleId,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal'   => $item['total_price'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    if ($itemInserted === false) {
                        $dbErr = $db->error();
                        throw new \RuntimeException($dbErr['message'] ?: 'Failed inserting into sale_items table.');
                    }
                }

                $insertedCount++;
            }

            if ($db->transStatus() === false) {
                $dbErr = $db->error();
                throw new \RuntimeException($dbErr['message'] ?: 'Transaction failed.');
            }

            $db->transCommit();

            return redirect()->to('/sales')->with(
                'message',
                'Sale recorded successfully (' . $insertedCount . ' item(s)).'
            );
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'SaleController::create failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with(
                'error',
                'Failed to record sale: ' . $e->getMessage()
            );
        }
    }

    // SHOW: View single sale details, payment method, and flower note
    public function show($id = null)
    {
        $sale = $this->saleModel
            ->select('sales.*, customers.full_name AS customer_name, products.name AS product_name, products.price AS unit_price')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('products', 'products.id = sales.product_id', 'left')
            ->where('sales.id', (int) $id)
            ->first();

        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale record not found.');
        }

        $items = [[
            'product_name' => $sale['product_name'] ?? ('Product #' . ($sale['product_id'] ?? '')),
            'quantity'     => (int) ($sale['quantity'] ?? 1),
            'unit_price'   => (float) ($sale['unit_price'] ?? ($sale['total_price'] ?? 0)),
            'subtotal'     => (float) ($sale['total_price'] ?? 0),
        ]];

        $sale['total_amount'] = (float) ($sale['total_price'] ?? 0);

        return view('sales/show', [
            'sale'  => $sale,
            'items' => $items,
        ]);
    }

    // DELETE: Remove a sale record (and its sale_items rows if present)
    public function delete($id = null)
    {
        $saleId = (int) $id;
        $sale = $this->saleModel->find($saleId);

        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale record not found.');
        }

        $db = Database::connect();
        if ($db->tableExists('sale_items')) {
            $db->table('sale_items')->where('sale_id', $saleId)->delete();
        }

        $this->saleModel->delete($saleId);

        return redirect()->to('/sales')->with('message', 'Sale deleted successfully.');
    }
}