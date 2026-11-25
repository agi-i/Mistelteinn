<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale - POS System - TFA1</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; color: #333; }
        .page-wrapper { max-width: 900px; margin: 0 auto; }
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
        .btn-danger { background: #b91c1c; color: #fff; padding: 6px 10px; }
        .alert-error { background: #fef2f2; border-left: 4px solid #b91c1c; color: #7f1d1d; padding: 12px 16px; border-radius: 5px; margin-bottom: 18px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 18px; }
        label { display: block; font-weight: bold; color: #2c3e50; margin-bottom: 6px; font-size: 14px; }
        select, input[type="number"] { width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 5px; font-size: 14px; background: #fafbfc; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e1e6ea; padding: 10px; text-align: left; }
        th { background: #f8f9fa; color: #2c3e50; }
        .total-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding: 14px 18px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e1e6ea; }
        .grand-total { font-size: 20px; font-weight: bold; color: #2c3e50; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #eef2f4; }
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
                <a href="<?= base_url('/sales') ?>" class="btn btn-back">&larr; Back</a>
                <h1>Record New Sale</h1>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/sales') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-row">
                    <div>
                        <label for="customer_id">Customer (Optional)</label>
                        <select id="customer_id" name="customer_id">
                            <option value="">-- Walk-in Customer --</option>
                            <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= esc($c['id']) ?>"><?= esc($c['full_name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash / E-Wallet</option>
                            <option value="card">Credit / Debit Card</option>
                        </select>
                    </div>
                </div>

                <label>Line Items</label>
                <table id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 48%;">Product</th>
                            <th style="width: 18%;">Unit Price</th>
                            <th style="width: 14%;">Qty</th>
                            <th style="width: 14%;">Subtotal</th>
                            <th style="width: 6%;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="product_id[]" class="product-select" required onchange="recalc()">
                                    <option value="" data-price="0">-- Select Product --</option>
                                    <?php if (!empty($products)): ?>
                                        <?php foreach ($products as $p): ?>
                                            <option value="<?= esc($p['id']) ?>" data-price="<?= esc($p['price']) ?>">
                                                <?= esc($p['name']) ?> (Stock: <?= esc($p['stock_quantity']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td class="unit-price">0.00</td>
                            <td>
                                <input type="number" name="quantity[]" class="qty-input" value="1" min="1" required oninput="recalc()">
                            </td>
                            <td class="row-subtotal">0.00</td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="total-bar">
                    <button type="button" class="btn" onclick="addRow()">+ Add Item</button>
                    <div class="grand-total">Total: <span id="grandTotal">0.00</span></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-accent">Complete Sale</button>
                    <a href="<?= base_url('/sales') ?>" class="btn btn-back">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addRow() {
            const tbody = document.getElementById('itemsBody');
            const firstRow = tbody.querySelector('.item-row');
            const clone = firstRow.cloneNode(true);
            clone.querySelector('.product-select').selectedIndex = 0;
            clone.querySelector('.qty-input').value = 1;
            clone.querySelector('.unit-price').textContent = '0.00';
            clone.querySelector('.row-subtotal').textContent = '0.00';
            tbody.appendChild(clone);
        }

        function removeRow(btn) {
            const tbody = document.getElementById('itemsBody');
            if (tbody.querySelectorAll('.item-row').length > 1) {
                btn.closest('.item-row').remove();
                recalc();
            }
        }

        function recalc() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
                const qty = parseInt(row.querySelector('.qty-input').value || 0, 10);
                const sub = price * qty;
                row.querySelector('.unit-price').textContent = price.toFixed(2);
                row.querySelector('.row-subtotal').textContent = sub.toFixed(2);
                total += sub;
            });
            document.getElementById('grandTotal').textContent = total.toFixed(2);
        }
    </script>
</body>
</html>