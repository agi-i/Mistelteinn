<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        // Instantiate the ProductModel once for use across controller methods
        $this->productModel = new ProductModel();
    }

    // Helper to turn a stored filename into a browser-ready image URL
    private function prepareProductImage(?string $filename): string
    {
        // Default placeholder if no image filename is stored or file is missing
        if (empty($filename)) {
            return base_url('uploads/products/default-product.png');
        }

        // Verify the file actually exists inside public/uploads/products/
        $fullPath = FCPATH . 'uploads/products/' . $filename;
        if (!is_file($fullPath)) {
            return base_url('uploads/products/default-product.png');
        }

        // Return a public URL usable directly inside an <img src="..."> tag
        return base_url('uploads/products/' . $filename);
    }

    // Helper to validate and move an uploaded product image into public/uploads/products/
    private function handleImageUpload(): ?string
    {
        $file = $this->request->getFile('image');

        // If no new file was uploaded, return null so caller can keep existing image
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // Ensure upload succeeded and file hasn't already been moved
        if ($file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/products';

            // Create the target folder if it does not exist yet
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Generate a random collision-safe filename while keeping extension
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            return $newName;
        }

        return null;
    }

    // LIST all products with display-ready image URLs
    public function index()
    {
        $products = $this->productModel->orderBy('id', 'DESC')->findAll();

        // Attach 'image_url' to each product row so the view can render <img> directly
        foreach ($products as &$product) {
            $product['image_url'] = $this->prepareProductImage($product['image'] ?? null);
        }
        unset($product); // Break reference after foreach loop

        return view('products/index', [
            'products' => $products,
        ]);
    }

    // SHOW form to add a new product
    public function new()
    {
        return view('products/form', [
            'product' => null,
        ]);
    }

    // STORE a new product in the database
    public function create()
    {
        // Basic validation rules for incoming POST data
        $rules = [
            'name'           => 'required|min_length[2]|max_length[150]',
            'sku'            => 'required|max_length[60]',
            'price'          => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image'          => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save uploaded image if provided
        $imageName = $this->handleImageUpload();

        $this->productModel->insert([
            'name'           => $this->request->getPost('name'),
            'sku'            => $this->request->getPost('sku'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
            'image'          => $imageName,
        ]);

        return redirect()->to('/products')->with('message', 'Product created successfully.');
    }

    // SHOW form to edit an existing product
    public function edit($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $product['image_url'] = $this->prepareProductImage($product['image'] ?? null);

        return view('products/form', [
            'product' => $product,
        ]);
    }

    // UPDATE an existing product in the database
    public function update($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $rules = [
            'name'           => 'required|min_length[2]|max_length[150]',
            'sku'            => 'required|max_length[60]',
            'price'          => 'required|decimal|greater_than_equal_to[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image'          => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Keep old image unless a new valid image was uploaded
        $imageName = $product['image'] ?? null;
        $newImage  = $this->handleImageUpload();

        if ($newImage !== null) {
            // Remove old image file from disk if it exists
            if (!empty($imageName) && is_file(FCPATH . 'uploads/products/' . $imageName)) {
                @unlink(FCPATH . 'uploads/products/' . $imageName);
            }
            $imageName = $newImage;
        }

        $this->productModel->update($id, [
            'name'           => $this->request->getPost('name'),
            'sku'            => $this->request->getPost('sku'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => (int) $this->request->getPost('stock_quantity'),
            'image'          => $imageName,
        ]);

        return redirect()->to('/products')->with('message', 'Product updated successfully.');
    }

    // DELETE a product and clean up its image file
    public function delete($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        // Delete image file on disk if present
        if (!empty($product['image'])) {
            $filePath = FCPATH . 'uploads/products/' . $product['image'];
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        $this->productModel->delete($id);

        return redirect()->to('/products')->with('message', 'Product deleted successfully.');
    }
}