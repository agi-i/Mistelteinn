<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Pages extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products     = $productModel->orderBy('id', 'DESC')->findAll();

        foreach ($products as &$product) {
            $product['image_url'] = !empty($product['image'])
                ? base_url('uploads/products/' . $product['image'])
                : base_url('uploads/products/default-product.png');
        }
        unset($product);

        return view('home', [
            'products' => $products,
        ]);
    }

    public function about()
    {
        return view('about');
    }
}