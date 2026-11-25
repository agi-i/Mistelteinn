<?php $isEdit = isset($customer) && is_array($customer) && !empty($customer['id']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?> - POS System - TFA1</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; color: #333; }
        .page-wrapper { max-width: 760px; margin: 0 auto; }
        nav { background: #2c3e50; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); }
        nav a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; color: #1abc9c; }
        .card { background: #ffffff; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: 1px solid #e3e8ec; }
        .page-header { display: flex; align-items: center; gap: 12px; padding-bottom: 14px; border-bottom: 2px solid #eef2f4; margin-bottom: 20px; }
        h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        .btn { display: inline-block; background: #2c3e50; color: #fff; padding: 9px 16px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; border: none; cursor: pointer; transition: background-color 0.15s ease, color 0.15s ease; }
        .btn:hover { background: #1f2d3d; color: #1abc9c; }
        .btn-back { background: #eef2f4; color: #2c3e50; border: 1px solid #cfd8de; }
        .btn-back:hover { background: #dfe6ea; color: #2c3e50; }
        .btn-accent { background: #16a085; color: #fff; }
        .btn-accent:hover { background: #12876f; color: #fff; }
        .error-box { background: #fef2f2; border-left: 4px solid #b91c1c; color: #7f1d1d; padding: 12px 16px; border-radius: 5px; margin-bottom: 18px; }
        .error-box ul { margin: 0; padding-left: 18px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; color: #2c3e50; margin-bottom: 6px; font-size: 14px; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 5px; font-size: 14px; background: #fafbfc; }
        input[type="text"]:focus, input[type="email"]:focus { outline: none; border-color: #1abc9c; background: #ffffff; box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15); }
        .form-actions { display: flex; gap: 10px; align-items: center; margin-top: 22px; padding-top: 16px; border-top: 1px solid #eef2f4; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Shared single-bar navigation -->
        <nav>
            <a href="<?= base_url('/') ?>">Home</a>
            <a href="<?= base_url('/about') ?>">About</a>
            <a href="<?= base_url('/products') ?>">Products</a>
            <a href="<?= base_url('/customers') ?>">Customer Accounts</a>
            <a href="<?= base_url('/users') ?>">User Accounts</a>
        </nav>

        <div class="card">
            <div class="page-header">
                <a href="<?= base_url('/customers') ?>" class="btn btn-back">&larr; Back</a>
                <h1><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?></h1>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="error-box">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= $isEdit ? base_url('/customers/' . esc($customer['id'])) : base_url('/customers') ?>" method="post">
                <?= csrf_field() ?>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>"
                        placeholder="e.g., Juan Dela Cruz"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= esc(old('email', $customer['email'] ?? '')) ?>"
                        placeholder="e.g., juan@example.com"
                    >
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"
                        placeholder="e.g., 09171234567"
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-accent">
                        <?= $isEdit ? 'Update Customer' : 'Save Customer' ?>
                    </button>
                    <a href="<?= base_url('/customers') ?>" class="btn btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>