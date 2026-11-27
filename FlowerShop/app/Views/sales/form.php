<?= view('partials/header', ['pageTitle' => 'Record Sale - Mistelteinn']) ?>

<style>
    .sale-form-stack {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-label {
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
    }
    .form-control,
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        box-sizing: border-box;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #16a085;
        box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.12);
    }
    .form-textarea {
        resize: vertical;
        min-height: 84px;
        font-family: inherit;
    }
    .table-control {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        box-sizing: border-box;
    }
    .qty-cell-input {
        max-width: 120px;
        text-align: center;
        font-weight: bold;
    }
    .note-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 16px;
    }
    .checkbox-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
        cursor: pointer;
        user-select: none;
    }
    .checkbox-row input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #16a085;
        cursor: pointer;
    }
    .note-collapse {
        margin-top: 12px;
    }
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid #eef2f4;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Record Sale</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/sales') ?>" class="btn">Back to Sales</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/sales') ?>" method="post" class="sale-form-stack">
        <?= csrf_field() ?>

        <div class="form-field">
            <label for="customer_id" class="form-label">Customer</label>
            <select id="customer_id" name="customer_id" class="form-control">
                <option value="">Walk-in Customer</option>
                <?php foreach ($customers ?? [] as $customer): ?>
                    <option value="<?= (int) $customer['id'] ?>" <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>>
                        <?= esc($customer['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-control">
                <?php $selectedPayment = old('payment_method') ?: 'cash'; ?>
                <option value="cash" <?= $selectedPayment === 'cash' ? 'selected' : '' ?>>Cash</option>
                <option value="card" <?= $selectedPayment === 'card' ? 'selected' : '' ?>>Card</option>
                <option value="ewallet" <?= $selectedPayment === 'ewallet' ? 'selected' : '' ?>>E-Wallet</option>
            </select>
        </div>

        <div class="form-field">
            <label class="form-label">Items</label>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Flower / Product</th>
                            <th style="width: 160px;">Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="sale-items-body">
                        <tr>
                            <td>
                                <select name="product_id[]" class="table-control">
                                    <option value="">-- Select Product --</option>
                                    <?php foreach ($products ?? [] as $product): ?>
                                        <option value="<?= (int) $product['id'] ?>">
                                            <?= esc($product['name']) ?> (₱<?= number_format((float) $product['price'], 2) ?>, Stock: <?= (int) $product['stock_quantity'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" min="1" value="1" class="table-control qty-cell-input">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="note-panel">
            <label class="checkbox-row">
                <input
                    type="checkbox"
                    id="has_flower_note"
                    name="has_flower_note"
                    value="1"
                    <?= (old('has_flower_note') || !empty(old('note'))) ? 'checked' : '' ?>
                >
                <span>Add a note with the flowers</span>
            </label>

            <div
                class="note-collapse"
                id="flower_note_container"
                style="<?= (old('has_flower_note') || !empty(old('note'))) ? '' : 'display: none;' ?>"
            >
                <div class="form-field">
                    <label for="note" class="form-label">Flower / Card Note</label>
                    <textarea
                        id="note"
                        name="note"
                        rows="3"
                        maxlength="500"
                        class="form-textarea"
                        placeholder="Write a card message or arrangement note for the flowers..."
                    ><?= esc(old('note') ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">Save Sale</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const checkbox = document.getElementById('has_flower_note');
        const container = document.getElementById('flower_note_container');
        const textarea = document.getElementById('note');

        if (!checkbox || !container || !textarea) return;

        checkbox.addEventListener('change', function () {
            container.style.display = this.checked ? '' : 'none';
            if (!this.checked) {
                textarea.value = '';
            }
        });
    })();
</script>

    </div>
</body>
</html>