<?php
$cartItems = session()->get('cart') ?? [];
$cartCount = 0;
foreach ($cartItems as $item) {
    $cartCount += (int) ($item['quantity'] ?? 0);
}
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