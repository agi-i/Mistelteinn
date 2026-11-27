<?= view('partials/header', ['pageTitle' => 'Record Sale - Mistelteinn']) ?>

<style>
    .card {
        max-width: 900px;
        margin: 0 auto;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }
    label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 14px;
    }
    select,
    input[type="number"] {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-size: 14px;
        background: #fafbfc;
        color: #333;
    }
    select:focus,
    input[type="number"]:focus {
        outline: none;
        border-color: #1abc9c;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
    }
    table {
        margin-top: 10px;
    }
    .btn-remove {
        padding: 6px 10px;
    }
    .total-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
        padding: 14px 18px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e1e6ea;
    }
    .grand-total {
        font-size: 20px;
        font-weight: bold;
        color: #2c3e50;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #eef2f4;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Record New Sale</h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
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
        <div class="table-responsive">
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
                            <button type="button" class="btn btn-danger btn-remove" onclick="removeRow(this)">X</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total-bar">
            <button type="button" class="btn" onclick="addRow()">+ Add Item</button>
            <div class="grand-total">Total: <span id="grandTotal">0.00</span></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">Complete Sale</button>
            <a href="<?= base_url('/sales') ?>" class="btn btn-light">Cancel</a>
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