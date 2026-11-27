<?php
$isEdit     = !empty($product['id'] ?? null);
$pageTitle  = $isEdit ? 'Edit Product - Mistelteinn' : 'Add Product - Mistelteinn';
$heading    = $isEdit ? 'Edit Product' : 'Add Product';
$formAction = $isEdit
    ? base_url('/products/' . esc($product['id']))
    : base_url('/products');

$currentImg = !empty($product['image'])
    ? base_url('uploads/products/' . $product['image'])
    : base_url('uploads/products/default-product.png');
?>

<?= view('partials/header', ['pageTitle' => $pageTitle]) ?>

<style>
    .form-group {
        margin-bottom: 16px;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        background: #ffffff;
    }
    .form-control:focus {
        outline: none;
        border-color: #1abc9c;
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
    }
    .file-box {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .preview-img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #eef2f4;
    }
    .form-actions {
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid #eef2f4;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1><?= esc($heading) ?></h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="<?= $formAction ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Product Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                placeholder="e.g., Red Rose Bouquet"
                value="<?= esc(old('name', $product['name'] ?? '')) ?>"
                required
            >
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Price</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    id="price"
                    name="price"
                    class="form-control"
                    placeholder="0.00"
                    value="<?= esc(old('price', $product['price'] ?? '')) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input
                    type="number"
                    min="0"
                    id="stock_quantity"
                    name="stock_quantity"
                    class="form-control"
                    value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? 0)) ?>"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <div class="file-box">
                <?php if ($isEdit): ?>
                    <img src="<?= esc($currentImg) ?>" alt="<?= esc($product['name'] ?? 'Product') ?>" class="preview-img">
                <?php endif; ?>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/png, image/jpeg, image/jpg, image/webp, image/gif"
                >
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">Save Product</button>
            <a href="<?= base_url('/products') ?>" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>

    </div>
</body>
</html>