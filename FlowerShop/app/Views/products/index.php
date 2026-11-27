<?= view('partials/header', ['pageTitle' => 'Products - Mistelteinn']) ?>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Products</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/products/new') ?>" class="btn btn-accent">+ Add Product</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                        $imgSrc = $product['image_url']
                            ?? (!empty($product['image'])
                                ? base_url('uploads/products/' . $product['image'])
                                : base_url('uploads/products/default-product.png'));
                        $stockQty = (int) ($product['stock_quantity'] ?? 0);
                        ?>
                        <tr>
                            <td><img src="<?= esc($imgSrc) ?>" alt="<?= esc($product['name'] ?? 'Product') ?>" class="thumb-img"></td>
                            <td><strong><?= esc($product['name'] ?? '') ?></strong></td>
                            <td><?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                            <td>
                                <span class="badge <?= $stockQty <= 0 ? 'badge-out' : ($stockQty <= 5 ? 'badge-low' : '') ?>">
                                    <?= esc($stockQty) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/products/' . esc($product['id']) . '/edit') ?>" class="btn">Edit</a>
                                    <form action="<?= base_url('/products/' . esc($product['id'])) ?>" method="post" onsubmit="return confirm('Delete this product?');" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>