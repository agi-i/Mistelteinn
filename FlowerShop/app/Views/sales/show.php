<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale #<?= esc($sale['id']) ?> - POS System - TFA1</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; color: #333; }
        .page-wrapper { max-width: 800px; margin: 0 auto; }
        nav { background: #2c3e50; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; }
        nav a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; color: #1abc9c; }
        .card { background: #ffffff; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: 1px solid #e3e8ec; }
        .page-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 14px; border-bottom: 2px solid #eef2f4; margin-bottom: 18px; }
        .header-left { display: flex; align-items: center; gap: 12px; }
        h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        .btn { display: inline-block; background: #2c3e50; color: #fff; padding: 8px 14px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; border: none; cursor: pointer; }
        .btn-back { background: #eef2f4; color: #2c3e50; border: 1px solid #cfd8de; }
        .meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; background: #f8f9fa; padding: 14px; border-radius: 6px; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e1e6ea; padding: 10px 12px; text-align: left; }
        th { background: #f8f9fa; color: #2c3e50; }
        .total-row { font-size: 18px; font-weight: bold; text-align: right; margin-top: 16px; color: #2c3e50; }
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
                    <a href="<?= base_url('/sales') ?>" class="btn btn-back">&larr; Back</a>
                    <h1>Sale Receipt #<?= esc($sale['id']) ?></h1>
                </div>
                <button type="button" class="btn" onclick="window.print()">Print Receipt</button>
            </div>

            <div class="meta">
                <div><strong>Customer:</strong><br><?= esc($sale['customer_name'] ?? 'Walk-in Customer') ?></div>
                <div><strong>Payment:</strong><br><?= esc(ucfirst($sale['payment_method'] ?? 'Cash')) ?></div>
                <div><strong>Date:</strong><br><?= esc($sale['created_at'] ?? '-') ?></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc($item['product_name'] ?? 'Product') ?></td>
                                <td><?= esc($item['sku'] ?? '-') ?></td>
                                <td><?= number_format((float) $item['unit_price'], 2) ?></td>
                                <td><?= esc($item['quantity']) ?></td>
                                <td><?= number_format((float) $item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No items found for this sale.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="total-row">
                Grand Total: <?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?>
            </div>
        </div>
    </div>
</body>
</html>