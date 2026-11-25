<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mistelteinn Flower Shop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; color: #333; }
        nav { background: #2c3e50; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
        nav a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; color: #1abc9c; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('/about') ?>">About</a>
        <a href="<?= base_url('/products') ?>">Products</a>
        <a href="<?= base_url('/sales') ?>">Sales</a>
        <a href="<?= base_url('/customers') ?>">Customer Accounts</a>
        <a href="<?= base_url('/users') ?>">User Accounts</a>
    </nav>