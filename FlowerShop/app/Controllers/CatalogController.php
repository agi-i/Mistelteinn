<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Config\Database;

class CatalogController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products = $productModel->orderBy('id', 'DESC')->findAll();

        foreach ($products as &$product) {
            $product['image_url'] = $this->resolveProductImage($product['image'] ?? null);
        }
        unset($product);

        return view('catalog/index', [
            'products' => $products,
        ]);
    }

    public function addToCart()
    {
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) $this->request->getPost('quantity');

        $productModel = new ProductModel();
        $product = $productModel->find($productId);

        if (!$product) {
            return redirect()->to('/catalog')->with('error', 'Product not found.');
        }

        $stockQty = (int) ($product['stock_quantity'] ?? 0);
        if ($stockQty <= 0 || $quantity < 1) {
            return redirect()->to('/catalog')->with('error', 'Invalid quantity or product is out of stock.');
        }

        $cart = session()->get('cart') ?? [];
        $currentInCart = (int) ($cart[$productId]['quantity'] ?? 0);
        $newTotalQty = min($stockQty, $currentInCart + $quantity);

        $cart[$productId] = [
            'id'       => $productId,
            'name'     => $product['name'],
            'price'    => (float) $product['price'],
            'quantity' => $newTotalQty,
            'image'    => $product['image'] ?? null,
        ];

        session()->set('cart', $cart);

        return redirect()->to('/catalog')->with('message', 'Added "' . $product['name'] . '" (' . $quantity . ') to cart.');
    }

    public function cart()
    {
        $cart = session()->get('cart') ?? [];
        $productModel = new ProductModel();
        $items = [];
        $subtotal = 0.0;

        foreach ($cart as $productId => $item) {
            $product = $productModel->find((int) $productId);
            if (!$product) {
                continue;
            }

            $maxStock = max(0, (int) ($product['stock_quantity'] ?? 0));
            $qty = min(max(1, (int) ($item['quantity'] ?? 1)), max(1, $maxStock));
            $price = (float) ($product['price'] ?? $item['price'] ?? 0);
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;

            $items[] = [
                'id'         => (int) $product['id'],
                'name'       => $product['name'],
                'price'      => $price,
                'quantity'   => $qty,
                'max_stock'  => $maxStock,
                'line_total' => $lineTotal,
                'image_url'  => $this->resolveProductImage($product['image'] ?? null),
            ];
        }

        return view('catalog/cart', [
            'items'    => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function updateCart()
    {
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) $this->request->getPost('quantity');
        $cart = session()->get('cart') ?? [];

        if (!isset($cart[$productId])) {
            return redirect()->to('/cart')->with('error', 'Item not found in cart.');
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
            return redirect()->to('/cart')->with('message', 'Item removed from cart.');
        }

        $productModel = new ProductModel();
        $product = $productModel->find($productId);
        $maxStock = (int) ($product['stock_quantity'] ?? 0);

        if (!$product || $maxStock <= 0) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
            return redirect()->to('/cart')->with('error', 'Product is no longer available.');
        }

        $cart[$productId]['quantity'] = min($quantity, $maxStock);
        $cart[$productId]['price']    = (float) $product['price'];
        $cart[$productId]['name']     = $product['name'];
        session()->set('cart', $cart);

        return redirect()->to('/cart')->with('message', 'Cart quantity updated.');
    }

    public function removeFromCart()
    {
        $productId = (int) $this->request->getPost('product_id');
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
        }

        return redirect()->to('/cart')->with('message', 'Item removed from cart.');
    }

    public function clearCart()
    {
        session()->remove('cart');

        return redirect()->to('/cart')->with('message', 'Cart cleared.');
    }

    public function checkout()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        $db = Database::connect();
        $productModel = new ProductModel();

        // Resolve sold_by from session or fallback to first available user ID if FK exists
        $soldBy = (int) (session()->get('user_id') ?? session()->get('id') ?? 0);
        if ($soldBy <= 0 && $db->tableExists('users')) {
            $firstUser = $db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
            $soldBy = (int) ($firstUser['id'] ?? 0);
        }

        $customerIdPost = (int) ($this->request->getPost('customer_id') ?? 0);
        $customerId = $customerIdPost > 0 ? $customerIdPost : null;
        $now = date('Y-m-d H:i:s');

        $db->transBegin();

        try {
            $insertedCount = 0;

            foreach ($cart as $productId => $item) {
                $product = $productModel->find((int) $productId);
                $qty = (int) ($item['quantity'] ?? 0);

                if (!$product || $qty < 1) {
                    throw new \RuntimeException('One of the items in your cart is invalid.');
                }

                $currentStock = (int) ($product['stock_quantity'] ?? 0);
                if ($qty > $currentStock) {
                    throw new \RuntimeException(
                        'Not enough stock for "' . $product['name'] . '" (' . $currentStock . ' available).'
                    );
                }

                $unitPrice = (float) ($product['price'] ?? 0);
                $lineTotal = round($unitPrice * $qty, 2);

                $stockUpdated = $db->table('products')
                    ->where('id', (int) $product['id'])
                    ->where('stock_quantity >=', $qty)
                    ->set('stock_quantity', 'stock_quantity - ' . $qty, false)
                    ->update();

                if ($stockUpdated === false) {
                    $dbErr = $db->error();
                    throw new \RuntimeException($dbErr['message'] ?: 'Failed updating product stock.');
                }

                $saleRow = [
                    'product_id'  => (int) $product['id'],
                    'customer_id' => $customerId,
                    'sold_by'     => $soldBy,
                    'quantity'    => $qty,
                    'total_price' => $lineTotal,
                    'created_at'  => $now,
                ];

                $saleInserted = $db->table('sales')->insert($saleRow);
                if ($saleInserted === false) {
                    $dbErr = $db->error();
                    throw new \RuntimeException($dbErr['message'] ?: 'Failed inserting into sales table.');
                }

                $insertedCount++;
            }

            if ($db->transStatus() === false) {
                $dbErr = $db->error();
                throw new \RuntimeException($dbErr['message'] ?: 'Transaction status returned false.');
            }

            $db->transCommit();
            session()->remove('cart');

            return redirect()->to('/sales')->with(
                'message',
                'Checkout completed (' . $insertedCount . ' item(s) recorded).'
            );
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Checkout failed: ' . $e->getMessage());

            return redirect()->to('/cart')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    private function resolveProductImage(?string $filename): string
    {
        if (!empty($filename) && is_file(FCPATH . 'uploads/products/' . $filename)) {
            return base_url('uploads/products/' . $filename);
        }

        return base_url('uploads/products/default-product.png');
    }
}