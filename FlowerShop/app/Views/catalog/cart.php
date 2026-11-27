<?php
$savedCheckout = session()->get('checkout_options') ?? [];
$selectedPayment = old('payment_method') ?? ($savedCheckout['payment_method'] ?? 'cash');
$savedNote = old('note') ?? ($savedCheckout['note'] ?? '');
$hasNoteOption = old('has_flower_note') !== null
    ? (bool) old('has_flower_note')
    : (!empty($savedCheckout['has_flower_note']) || $savedNote !== '');
?>
<?= view('partials/header', ['pageTitle' => 'Cart & Checkout - Mistelteinn']) ?>

<style>
    .item-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .thumb-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #d5dce0;
        background: #f4f7f6;
    }
    .qty-inline-form {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
    }
    .qty-input {
        width: 64px;
        padding: 6px 8px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
    }
    .checkout-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px 18px;
        margin-bottom: 18px;
    }
    .checkout-panel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        align-items: start;
    }
    .checkout-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .checkout-label {
        font-size: 13px;
        font-weight: bold;
        color: #334155;
    }
    .checkout-select,
    .checkout-textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        padding: 8px 10px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        box-sizing: border-box;
    }
    .checkout-textarea {
        resize: vertical;
        min-height: 76px;
        font-family: inherit;
    }
    .checkout-checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
        cursor: pointer;
        user-select: none;
    }
    .checkout-note-box {
        margin-top: 8px;
    }
    .checkout-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding-top: 16px;
        border-top: 2px solid #eef2f4;
    }
    .total-box {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
    }
    .total-amount {
        color: #16a085;
        font-size: 22px;
        margin-left: 6px;
    }
    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: #475569;
        font-size: 14px;
    }
    table {
        margin-bottom: 20px;
    }
</style>

<div class="card">
    <div class="page-header">
        <h1>Shopping Cart</h1>
        <div class="btn-group">
            <a href="<?= base_url('/catalog') ?>" class="btn btn-light">Continue Shopping</a>
            <?php if (!empty($items)): ?>
                <form action="<?= base_url('/cart/clear') ?>" method="post" style="margin:0;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Clear Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (!empty($items)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Line Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="item-row">
                                    <img src="<?= esc($item['image_url']) ?>" alt="<?= esc($item['name']) ?>" class="thumb-img">
                                    <strong><?= esc($item['name']) ?></strong>
                                </div>
                            </td>
                            <td><?= number_format((float) $item['price'], 2) ?></td>
                            <td>
                                <form action="<?= base_url('/cart/update') ?>" method="post" class="qty-inline-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                    <input
                                        type="number"
                                        name="quantity"
                                        class="qty-input"
                                        value="<?= (int) $item['quantity'] ?>"
                                        min="1"
                                        max="<?= (int) $item['max_stock'] ?>"
                                    >
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            </td>
                            <td><strong><?= number_format((float) $item['line_total'], 2) ?></strong></td>
                            <td>
                                <form action="<?= base_url('/cart/remove') ?>" method="post" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form action="<?= base_url('/checkout') ?>" method="post" id="cart-checkout-form" style="margin:0;">
            <?= csrf_field() ?>

            <div class="checkout-panel">
                <div class="checkout-panel-grid">
                    <div class="checkout-field">
                        <label for="checkout_payment_method" class="checkout-label">Payment Method</label>
                        <select id="checkout_payment_method" name="payment_method" class="checkout-select">
                            <option value="cash" <?= $selectedPayment === 'cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="card" <?= $selectedPayment === 'card' ? 'selected' : '' ?>>Card</option>
                            <option value="ewallet" <?= $selectedPayment === 'ewallet' ? 'selected' : '' ?>>E-Wallet (GCash / Maya)</option>
                        </select>
                    </div>

                    <div class="checkout-field">
                        <label class="checkout-checkbox-label">
                            <input
                                type="checkbox"
                                id="checkout_has_flower_note"
                                name="has_flower_note"
                                value="1"
                                <?= $hasNoteOption ? 'checked' : '' ?>
                            >
                            <span>Include a note with the flowers?</span>
                        </label>

                        <div id="checkout_flower_note_box" class="checkout-note-box" style="<?= $hasNoteOption ? '' : 'display: none;' ?>">
                            <label for="checkout_flower_note" class="checkout-label">Flower / Card Note</label>
                            <textarea
                                id="checkout_flower_note"
                                name="note"
                                class="checkout-textarea"
                                maxlength="500"
                                placeholder="Write a card message or arrangement note for the flowers..."
                            ><?= esc($savedNote) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-bar">
                <div class="total-box">
                    Total: <span class="total-amount"><?= number_format((float) $subtotal, 2) ?></span>
                </div>

                <button type="submit" class="btn btn-accent">Complete Checkout</button>
            </div>
        </form>
    <?php else: ?>
        <div class="empty-state">
            <p>Your cart is currently empty.</p>
            <a href="<?= base_url('/catalog') ?>" class="btn btn-accent">Go to Catalog</a>
        </div>
    <?php endif; ?>
</div>

    </div>

    <script>
        (function () {
            const noteCheckbox = document.getElementById('checkout_has_flower_note');
            const noteBox = document.getElementById('checkout_flower_note_box');
            const noteTextarea = document.getElementById('checkout_flower_note');

            if (!noteCheckbox || !noteBox || !noteTextarea) return;

            function syncNoteVisibility() {
                noteBox.style.display = noteCheckbox.checked ? '' : 'none';
                if (!noteCheckbox.checked) {
                    noteTextarea.value = '';
                }
            }

            noteCheckbox.addEventListener('change', syncNoteVisibility);
            syncNoteVisibility();
        })();
    </script>
</body>
</html>