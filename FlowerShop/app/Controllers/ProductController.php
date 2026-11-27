<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data['products'] = $this->productModel->orderBy('id', 'DESC')->findAll();

        return view('products/index', $data);
    }

    public function new()
    {
        return view('products/form', [
            'product' => null,
        ]);
    }

    public function create()
    {
        $rules = [
            'name'           => 'required|min_length[2]|max_length[150]',
            'price'          => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => trim((string) $this->request->getPost('name')),
            'price'          => (float) $this->request->getPost('price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
        ];

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $data['image'] = $newName;
        }

        $this->productModel->insert($data);

        return redirect()->to('/products')->with('message', 'Product added successfully.');
    }

    public function edit($id = null)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        return view('products/form', [
            'product' => $product,
        ]);
    }

    public function update($id = null)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name'           => 'required|min_length[2]|max_length[150]',
            'price'          => 'required|numeric|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => trim((string) $this->request->getPost('name')),
            'price'          => (float) $this->request->getPost('price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
        ];

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $data['image'] = $newName;
        }

        $this->productModel->update($id, $data);

        return redirect()->to('/products')->with('message', 'Product updated successfully.');
    }

    public function delete($id = null)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $this->productModel->delete($id);

        return redirect()->to('/products')->with('message', 'Product deleted successfully.');
    }
}