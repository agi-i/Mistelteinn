<?php
$cartItems = session()->get('cart') ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int) ($item['quantity'] ?? 0);
}

$savedCheckout = session()->get('checkout_options') ?? [];
$selectedPayment = old('payment_method') ?? ($savedCheckout['payment_method'] ?? 'cash');
$savedNote = old('note') ?? ($savedCheckout['note'] ?? '');
$hasNoteOption = old('has_flower_note') !== null
    ? (bool) old('has_flower_note')
    : (!empty($savedCheckout['has_flower_note']) || $savedNote !== '');
?>
<?= view('partials/header', ['pageTitle' => 'Catalog - Mistelteinns']) ?>

<style>
    .cart-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f4f7f6;
        border: 1px solid #cfd8de;
        color: #2c3e50;
        padding: 8px 14px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    .cart-status:hover {
        background: #2c3e50;
        border-color: #2c3e50;
        color: #1abc9c;
    }
    .cart-count-pill {
        background: #16a085;
        color: #ffffff;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 12px;
    }

    /* Order / Flower Note & Payment Options Panel */
    .catalog-order-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px 18px;
        margin-bottom: 20px;
    }
    .catalog-order-panel h2 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #2c3e50;
    }
    .order-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        align-items: start;
    }
    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .field-label {
        font-size: 13px;
        font-weight: bold;
        color: #334155;
    }
    .field-select,
    .field-textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        padding: 8px 10px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        box-sizing: border-box;
    }
    .field-textarea {
        resize: vertical;
        min-height: 76px;
        font-family: inherit;
    }
    .checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
        cursor: pointer;
        user-select: none;
    }
    .note-box {
        margin-top: 8px;
    }
    .panel-actions {
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .btn-save-options {
        background: #2c3e50;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .btn-save-options:hover {
        background: #16a085;
    }
    .option-hint {
        font-size: 12px;
        color: #64748b;
    }

    /* Catalog Grid & Cards */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
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
        height: 175px;
        object-fit: cover;
        background: #eef2f4;
        border-bottom: 1px solid #e3e8ec;
    }
    .product-body {
        padding: 15px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .product-title {
        margin: 0 0 10px 0;
        font-size: 17px;
        color: #2c3e50;
    }
    .product-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        gap: 8px;
    }
    .product-price {
        font-size: 16px;
        font-weight: bold;
        color: #16a085;
    }
    .stock-qty {
        font-size: 13px;
        font-weight: bold;
        color: #475569;
    }

    /* Quantity Stepper & Add to Cart */
    .cart-form {
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .qty-control {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        overflow: hidden;
        background: #f8fafc;
    }
    .qty-btn {
        width: 36px;
        height: 34px;
        border: none;
        background: #eef2f4;
        color: #2c3e50;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    .qty-btn:hover:not(:disabled) {
        background: #2c3e50;
        color: #1abc9c;
    }
    .qty-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }
    .qty-input {
        width: 100%;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        color: #2c3e50;
        padding: 6px 4px;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .btn-cart {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1c8ff7;
        color: #ffffff;
        padding: 9px 14px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .btn-cart:hover:not(:disabled) {
        background: #12876f;
    }
    .btn-cart:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }
    .empty-state {
        text-align: center;
        padding: 28px 16px;
        color: #475569;
        font-size: 14px;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Catalog</h1>
        </div>
        <a href="<?= base_url('/cart') ?>" class="cart-status">
            <span>View Cart</span>
            <span class="cart-count-pill" id="header-cart-count"><?= (int) $cartCount ?></span>
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Customer Order Preferences: Payment Method & Optional Flower Note -->
    <section class="catalog-order-panel" aria-label="Order Preferences">
        <h2>Order Options</h2>
        <form action="<?= base_url('/cart/options') ?>" method="post" id="catalog-options-form">
            <?= csrf_field() ?>

            <div class="order-options-grid">
                <div class="field-group">
                    <label for="catalog_payment_method" class="field-label">Payment Method</label>
                    <select id="catalog_payment_method" name="payment_method" class="field-select">
                        <option value="cash" <?= $selectedPayment === 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="card" <?= $selectedPayment === 'card' ? 'selected' : '' ?>>Card</option>
                        <option value="ewallet" <?= $selectedPayment === 'ewallet' ? 'selected' : '' ?>>E-Wallet (GCash / Maya)</option>
                    </select>
                </div>

                <div class="field-group">
                    <label class="checkbox-label">
                        <input
                            type="checkbox"
                            id="catalog_has_flower_note"
                            name="has_flower_note"
                            value="1"
                            <?= $hasNoteOption ? 'checked' : '' ?>
                        >
                        <span>Add a note with the flowers?</span>
                    </label>

                    <div id="catalog_flower_note_box" class="note-box" style="<?= $hasNoteOption ? '' : 'display: none;' ?>">
                        <label for="catalog_flower_note" class="field-label">Flower / Card Note</label>
                        <textarea
                            id="catalog_flower_note"
                            name="note"
                            class="field-textarea"
                            maxlength="500"
                            placeholder="Write a card message or arrangement note for your flowers..."
                        ><?= esc($savedNote) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="panel-actions">
                <span class="option-hint">
                    These choices carry over when adding items and completing checkout.
                </span>
                <button type="submit" class="btn-save-options">Save Options</button>
            </div>
        </form>
    </section>

    <?php if (!empty($products)): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $productId = (int) ($product['id'] ?? 0);
                $imgSrc = $product['image_url']
                    ?? (!empty($product['image'])
                        ? base_url('uploads/products/' . $product['image'])
                        : base_url('uploads/products/default-product.png'));
                $stockQty = max(0, (int) ($product['stock_quantity'] ?? 0));
                $isAvailable = $stockQty > 0;
                ?>
                <article class="product-card">
                    <img
                        src="<?= esc($imgSrc) ?>"
                        alt="<?= esc($product['name'] ?? 'Product') ?>"
                        class="product-thumb"
                    >
                    <div class="product-body">
                        <h2 class="product-title"><?= esc($product['name'] ?? 'Unnamed Product') ?></h2>

                        <div class="product-info-row">
                            <span class="product-price"><?= number_format((float) ($product['price'] ?? 0), 2) ?></span>
                            <span class="stock-qty">Qty: <?= $stockQty ?></span>
                        </div>

                        <form action="<?= base_url('/cart/add') ?>" method="post" class="cart-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <input type="hidden" name="payment_method" class="sync-payment-input" value="<?= esc($selectedPayment) ?>">
                            <input type="hidden" name="has_flower_note" class="sync-has-note-input" value="<?= $hasNoteOption ? '1' : '0' ?>">
                            <input type="hidden" name="note" class="sync-note-input" value="<?= esc($savedNote) ?>">

                            <div class="qty-control">
                                <button
                                    type="button"
                                    class="qty-btn btn-minus"
                                    aria-label="Decrease quantity"
                                    <?= $isAvailable ? '' : 'disabled' ?>
                                >&minus;</button>

                                <input
                                    type="number"
                                    name="quantity"
                                    class="qty-input"
                                    value="<?= $isAvailable ? 1 : 0 ?>"
                                    min="<?= $isAvailable ? 1 : 0 ?>"
                                    max="<?= $stockQty ?>"
                                    <?= $isAvailable ? '' : 'disabled' ?>
                                >

                                <button
                                    type="button"
                                    class="qty-btn btn-plus"
                                    aria-label="Increase quantity"
                                    <?= $isAvailable ? '' : 'disabled' ?>
                                >+</button>
                            </div>

                            <button
                                type="submit"
                                class="btn-cart"
                                <?= $isAvailable ? '' : 'disabled' ?>
                            >
                                <?= $isAvailable ? 'Add to Cart' : 'Out of Stock' ?>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>No products are available in the catalog yet.</p>
        </div>
    <?php endif; ?>
</div>

    </div>

    <script>
        (function () {
            const paymentSelect = document.getElementById('catalog_payment_method');
            const noteCheckbox = document.getElementById('catalog_has_flower_note');
            const noteBox = document.getElementById('catalog_flower_note_box');
            const noteTextarea = document.getElementById('catalog_flower_note');

            function syncCatalogOptionsToCartForms() {
                const paymentVal = paymentSelect ? paymentSelect.value : 'cash';
                const hasNoteVal = noteCheckbox && noteCheckbox.checked ? '1' : '0';
                const noteVal = (noteCheckbox && noteCheckbox.checked && noteTextarea)
                    ? noteTextarea.value
                    : '';

                if (noteBox && noteCheckbox) {
                    noteBox.style.display = noteCheckbox.checked ? '' : 'none';
                    if (!noteCheckbox.checked && noteTextarea) {
                        noteTextarea.value = '';
                    }
                }

                document.querySelectorAll('.cart-form').forEach(function (form) {
                    const pInput = form.querySelector('.sync-payment-input');
                    const hInput = form.querySelector('.sync-has-note-input');
                    const nInput = form.querySelector('.sync-note-input');
                    if (pInput) pInput.value = paymentVal;
                    if (hInput) hInput.value = hasNoteVal;
                    if (nInput) nInput.value = noteVal;
                });
            }

            if (paymentSelect) {
                paymentSelect.addEventListener('change', syncCatalogOptionsToCartForms);
            }
            if (noteCheckbox) {
                noteCheckbox.addEventListener('change', syncCatalogOptionsToCartForms);
            }
            if (noteTextarea) {
                noteTextarea.addEventListener('input', syncCatalogOptionsToCartForms);
            }

            syncCatalogOptionsToCartForms();
        })();

        document.querySelectorAll('.cart-form').forEach(function (form) {
            const minusBtn = form.querySelector('.btn-minus');
            const plusBtn  = form.querySelector('.btn-plus');
            const qtyInput = form.querySelector('.qty-input');

            if (!minusBtn || !plusBtn || !qtyInput || qtyInput.disabled) {
                return;
            }

            const minQty = parseInt(qtyInput.getAttribute('min'), 10) || 1;
            const maxQty = parseInt(qtyInput.getAttribute('max'), 10) || 1;

            function syncQuantity(val) {
                let num = parseInt(val, 10);
                if (isNaN(num) || num < minQty) {
                    num = minQty;
                } else if (num > maxQty) {
                    num = maxQty;
                }
                qtyInput.value = num;
                minusBtn.disabled = num <= minQty;
                plusBtn.disabled = num >= maxQty;
            }

            minusBtn.addEventListener('click', function () {
                syncQuantity((parseInt(qtyInput.value, 10) || minQty) - 1);
            });

            plusBtn.addEventListener('click', function () {
                syncQuantity((parseInt(qtyInput.value, 10) || minQty) + 1);
            });

            qtyInput.addEventListener('change', function () {
                syncQuantity(qtyInput.value);
            });

            syncQuantity(qtyInput.value);
        });
    </script>
</body>
</html>