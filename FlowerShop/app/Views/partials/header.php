<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Mistelteinn POS') ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        .page-wrapper {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* Consistent Navigation Bar */
        nav {
            background: #2c3e50;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 22px;
        }
        nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            padding: 4px 0;
            transition: color 0.15s ease;
        }
        nav a:hover,
        nav a.active {
            color: #9fc5e8;
            text-decoration: underline;
        }

        /* Card & Header */
        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e3e8ec;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 14px;
            border-bottom: 2px solid #eef2f4;
            margin-bottom: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 20px;
        }

        /* Consistent Buttons */
        .btn-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #2c3e50;
            color: #ffffff;
            padding: 9px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.2;
            border: 1px solid transparent;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        }
        .btn:hover {
            background: #1f2d3d;
            color: #1c8ff7;
        }
        .btn-back {
            background: #eef2f4;
            color: #2c3e50;
            border-color: #cfd8de;
        }
        .btn-back:hover {
            background: #dfe6ea;
            color: #2c3e50;
        }
        .btn-accent {
            background: #1c8ff7;
            color: #ffffff;
        }
        .btn-accent:hover {
            background: #8bbdeb;
            color: #ffffff;
        }
        .btn-light {
            background: #eef2f4;
            color: #2c3e50;
            border-color: #cfd8de;
        }
        .btn-light:hover {
            background: #dfe6ea;
            color: #2c3e50;
        }
        .btn-danger {
            background: #b91c1c;
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #991b1b;
            color: #ffffff;
        }

        /* Alerts */
        .alert {
            background: #ffffff;
            border: 1px solid #e3e8ec;
            border-left: 4px solid #1abc9c;
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 5px;
        }
        .alert-error {
            border-left-color: #b91c1c;
            background: #fef2f2;
            color: #7f1d1d;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
        }
        th, td {
            border: 1px solid #e1e6ea;
            padding: 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #fafbfc;
        }
        tr:hover {
            background-color: #f2f8f7;
        }
        .thumb-img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #d5dce0;
            background: #f4f7f6;
        }
        .avatar-img {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #d5dce0;
            background: #f4f7f6;
        }

        /* Hero & Product Grid (Home) */
        .hero {
            background: #2c3e50;
            color: #ffffff;
            border-radius: 8px;
            padding: 26px 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            border-bottom: 4px solid #1abc9c;
        }
        .hero h1 {
            color: #ffffff;
            font-size: 26px;
            margin-bottom: 6px;
        }
        .hero p {
            margin: 0;
            color: #dce4e8;
            font-size: 15px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 18px;
        }
        .product-card {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e3e8ec;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .product-thumb {
            width: 100%;
            height: 165px;
            object-fit: cover;
            background: #eef2f4;
            border-bottom: 1px solid #e3e8ec;
        }
        .product-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-sku {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .product-title {
            margin: 0 0 10px 0;
            font-size: 17px;
            color: #2c3e50;
        }
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }
        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #16a085;
        }
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            background: #e6f7f4;
            color: #0f766e;
        }
        .badge-low {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-out {
            background: #fee2e2;
            color: #991b1b;
        }
        .empty-state {
            text-align: center;
            padding: 28px 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <nav>
            <a href="<?= base_url('/') ?>" class="<?= uri_string() === '' ? 'active' : '' ?>">Home</a>
            <a href="<?= base_url('/about') ?>" class="<?= url_is('about*') ? 'active' : '' ?>">About</a>
            <a href="<?= base_url('/catalog') ?>" class="<?= url_is('catalog*') || url_is('cart*') ? 'active' : '' ?>">Catalog</a>
            <a href="<?= base_url('/products') ?>" class="<?= url_is('products*') ? 'active' : '' ?>">Products</a>
            <a href="<?= base_url('/sales') ?>" class="<?= url_is('sales*') ? 'active' : '' ?>">Sales</a>
            <a href="<?= base_url('/customers') ?>" class="<?= url_is('customers*') ? 'active' : '' ?>">Customer Accounts</a>
            <a href="<?= base_url('/users') ?>" class="<?= url_is('users*') ? 'active' : '' ?>">User Accounts</a>
        </nav>