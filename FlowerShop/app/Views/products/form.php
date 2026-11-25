<!-- app/Views/products/form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($product) && $product ? 'Edit Product' : 'Add Product' ?></title>
</head>
<body>
    <h1><?= isset($product) && $product ? 'Edit Product' : 'Add Product' ?></h1>

    <!-- Display validation errors if any -->
    <?php if (session()->getFlashdata('errors')): ?>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php $isEdit = isset($product) && $product; ?>
    <form action="<?= $isEdit ? site_url('products/' . $product['id']) : site_url('products') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <!-- Method spoofing for resource update route -->
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <p>
            <label>Product Name:</label><br>
            <input type="text" name="name" value="<?= esc(old('name', $product['name'] ?? '')) ?>" required>
        </p>

        <p>
            <label>SKU:</label><br>
            <input type="text" name="sku" value="<?= esc(old('sku', $product['sku'] ?? '')) ?>" required>
        </p>

        <p>
            <label>Price:</label><br>
            <input type="number" step="0.01" name="price" value="<?= esc(old('price', $product['price'] ?? '')) ?>" required>
        </p>

        <p>
            <label>Stock Quantity:</label><br>
            <input type="number" name="stock_quantity" value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? 0)) ?>" required>
        </p>

        <p>
            <label>Product Image:</label><br>
            <?php if ($isEdit && !empty($product['image_url'])): ?>
                <img src="<?= esc($product['image_url']) ?>" alt="Current image" width="80"><br>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </p>

        <button type="submit"><?= $isEdit ? 'Update Product' : 'Save Product' ?></button>
        <a href="<?= site_url('products') ?>">Cancel</a>
    </form>
</body>
</html>