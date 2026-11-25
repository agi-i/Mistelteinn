<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use CodeIgniter\Model;

/**
 * Inline SaleModel & SaleItemModel so you can run immediately.
 * Adjust $table and $allowedFields if your database columns differ.
 */
class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'customer_id',
        'user_id',
        'total_amount',
        'payment_method',
    ];
    protected $useTimestamps = false;
}

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];
    protected $useTimestamps = false;
}

class SaleController extends BaseController
{
    protected SaleModel $saleModel;
    protected SaleItemModel $saleItemModel;
    protected CustomerModel $customerModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->customerModel = new CustomerModel();
        $this->productModel  = new ProductModel();
    }

    // READ: List all recorded sales
    public function index()
    {
        $sales = $this->saleModel
            ->select('sales.*, customers.full_name AS customer_name')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
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

    // CREATE ACTION: Save sale, save line items, and deduct stock
    public function create()
    {
        $productIds = $this->request->getPost('product_id') ?? [];
        $quantities = $this->request->getPost('quantity') ?? [];
        $customerId = $this->request->getPost('customer_id');
        $payment    = $this->request->getPost('payment_method') ?: 'cash';

        $itemsToSave = [];
        $grandTotal  = 0;

        foreach ($productIds as $idx => $prodId) {
            $qty = (int) ($quantities[$idx] ?? 0);
            if (empty($prodId) || $qty <= 0) {
                continue;
            }

            $product = $this->productModel->find($prodId);
            if (!$product) {
                continue;
            }

            if ((int) $product['stock_quantity'] < $qty) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'Insufficient stock for ' . $product['name'] . ' (Available: ' . $product['stock_quantity'] . ').'
                );
            }

            $unitPrice   = (float) $product['price'];
            $subtotal    = $unitPrice * $qty;
            $grandTotal += $subtotal;

            $itemsToSave[] = [
                'product'    => $product,
                'product_id' => (int) $product['id'],
                'quantity'   => $qty,
                'unit_price' => $unitPrice,
                'subtotal'   => $subtotal,
            ];
        }

        if (empty($itemsToSave)) {
            return redirect()->back()->withInput()->with('error', 'Please select at least one product with a valid quantity.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Insert sale header
        $saleId = $this->saleModel->insert([
            'customer_id'    => ($customerId === '' || $customerId === null) ? null : (int) $customerId,
            'user_id'        => session()->get('user_id') ?? null,
            'total_amount'   => $grandTotal,
            'payment_method' => $payment,
        ], true);

        // Insert line items and deduct product stock
        foreach ($itemsToSave as $item) {
            $this->saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $item['subtotal'],
            ]);

            $newStock = max(0, (int) $item['product']['stock_quantity'] - $item['quantity']);
            $this->productModel->update($item['product_id'], [
                'stock_quantity' => $newStock,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to record sale. Please try again.');
        }

        return redirect()->to('/sales')->with('message', 'Sale #' . $saleId . ' recorded successfully.');
    }

    // SHOW: View single sale receipt & items
    public function show($id = null)
    {
        $sale = $this->saleModel
            ->select('sales.*, customers.full_name AS customer_name')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->where('sales.id', $id)
            ->first();

        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale record not found.');
        }

        $items = $this->saleItemModel
            ->select('sale_items.*, products.name AS product_name, products.sku')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->where('sale_items.sale_id', $id)
            ->findAll();

        return view('sales/show', [
            'sale'  => $sale,
            'items' => $items,
        ]);
    }

    // DELETE: Remove a sale record
    public function delete($id = null)
    {
        $sale = $this->saleModel->find($id);

        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale record not found.');
        }

        $this->saleItemModel->where('sale_id', $id)->delete();
        $this->saleModel->delete($id);

        return redirect()->to('/sales')->with('message', 'Sale deleted successfully.');
    }
}