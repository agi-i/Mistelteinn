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

        <div class="checkout-bar">
            <div class="total-box">
                Total: <span class="total-amount"><?= number_format((float) $subtotal, 2) ?></span>
            </div>

            <form action="<?= base_url('/checkout') ?>" method="post" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-accent">Complete Checkout</button>
            </form>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>Your cart is currently empty.</p>
            <a href="<?= base_url('/catalog') ?>" class="btn btn-accent">Go to Catalog</a>
        </div>
    <?php endif; ?>
</div>

    </div>
</body>
</html>