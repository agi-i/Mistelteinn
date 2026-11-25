<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - POS System - TFA1</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; color: #333; }
        .page-wrapper { max-width: 1100px; margin: 0 auto; }
        nav { background: #2c3e50; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); }
        nav a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; color: #1abc9c; }
        .card { background: #ffffff; border-radius: 8px; padding: 20px 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: 1px solid #e3e8ec; }
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding-bottom: 14px; border-bottom: 2px solid #eef2f4; margin-bottom: 16px; }
        .header-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        .btn { display: inline-block; background: #2c3e50; color: #fff; padding: 8px 14px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; border: none; cursor: pointer; transition: background-color 0.15s ease, color 0.15s ease; }
        .btn:hover { background: #1f2d3d; color: #1abc9c; }
        .btn-back { background: #eef2f4; color: #2c3e50; border: 1px solid #cfd8de; }
        .btn-back:hover { background: #dfe6ea; color: #2c3e50; }
        .btn-accent { background: #16a085; color: #fff; }
        .btn-accent:hover { background: #12876f; color: #fff; }
        .btn-danger { background: #b91c1c; color: #fff; }
        .btn-danger:hover { background: #991b1b; color: #fff; }
        .alert { background: #fff; border-left: 4px solid #1abc9c; padding: 12px 16px; margin-bottom: 16px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
        .alert-error { border-left-color: #b91c1c; background: #fef2f2; color: #7f1d1d; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #e1e6ea; padding: 12px; text-align: left; vertical-align: middle; }
        th { background-color: #f8f9fa; color: #2c3e50; font-size: 14px; }
        tr:nth-child(even) { background-color: #fafbfc; }
        tr:hover { background-color: #f2f8f7; }
        .actions { display: flex; gap: 8px; align-items: center; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <nav>
            <a href="<?= base_url('/') ?>">Home</a>
            <a href="<?= base_url('/about') ?>">About</a>
            <a href="<?= base_url('/products') ?>">Products</a>
            <a href="<?= base_url('/customers') ?>">Customer Accounts</a>
            <a href="<?= base_url('/users') ?>">User Accounts</a>
            <a href="<?= base_url('/sales') ?>">Sales</a>
        </nav>

        <div class="card">
            <div class="page-header">
                <div class="header-left">
                    <a href="<?= base_url('/') ?>" class="btn btn-back">&larr; Back</a>
                    <h1>Sales History</h1>
                </div>
                <a href="<?= base_url('/sales/new') ?>" class="btn btn-accent">+ Record Sale</a>
            </div>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert"><?= esc(session()->getFlashdata('message')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Sale #</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sales)): ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td><strong>#<?= esc($sale['id']) ?></strong></td>
                                    <td><?= esc($sale['customer_name'] ?? 'Walk-in Customer') ?></td>
                                    <td><?= esc(ucfirst($sale['payment_method'] ?? 'Cash')) ?></td>
                                    <td><strong><?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></strong></td>
                                    <td><?= esc($sale['created_at'] ?? '-') ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= base_url('/sales/' . esc($sale['id'])) ?>" class="btn">Receipt</a>
                                            <form action="<?= base_url('/sales/' . esc($sale['id'])) ?>" method="post" onsubmit="return confirm('Delete this sale record?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No sales recorded yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>