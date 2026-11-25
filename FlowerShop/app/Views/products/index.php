<!-- app/Views/products/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>

    <!-- Flash success or error messages -->
    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <a href="<?= site_url('products/new') ?>">+ Add Product</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <!-- Display-ready image URL prepared in controller -->
                            <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" width="60" height="60">
                        </td>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['sku']) ?></td>
                        <td><?= number_format((float) $product['price'], 2) ?></td>
                        <td><?= esc($product['stock_quantity']) ?></td>
                        <td>
                            <a href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a>
                            <!-- POST form for delete so destructive actions aren't triggered by GET links -->
                            <form action="<?= site_url('products/' . $product['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>