<?= view('partials/header', ['pageTitle' => 'Home - Mistelteinn']) ?>

<style>
    /* Hero Banner */
    .hero {
        background: #2c3e50;
        color: #ffffff;
        border-radius: 8px;
        padding: 32px 28px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        border-bottom: 4px solid #1c8ff7;
    }
    .hero h1 {
        margin: 0 0 8px 0;
        font-size: 28px;
        color: #ffffff;
    }
    .hero p {
        margin: 0;
        color: #dce4e8;
        font-size: 15px;
        line-height: 1.5;
    }

    /* Product Showcase Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
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
    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .product-price {
        font-size: 16px;
        font-weight: bold;
        color: #16a085;
    }
    .badge-low {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-out {
        background: #fee2e2;
        color: #991b1b;
    }
    .empty-state {
        text-align: center;
        padding: 28px 16px;
        color: #475569;
        font-size: 14px;
    }
</style>

<section class="hero">
    <div>
        <h1>Welcome to Mistelteinn!</h1>
        <p>Your one stop shop for all your floral needs!</p>
    </div>
    <div>
        <a href="<?= base_url('/catalog') ?>" class="btn btn-accent">Flower Catalog</a>
    </div>
</section>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h2>Featured Products</h2>
        </div>
    </div>

    <?php if (!empty($products)): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $imgSrc = $product['image_url']
                    ?? (!empty($product['image'])
                        ? base_url('uploads/products/' . $product['image'])
                        : base_url('uploads/products/default-product.png'));
                $stockQty = (int) ($product['stock_quantity'] ?? 0);

                if ($stockQty <= 0) {
                    $badgeClass = 'badge-out';
                    $badgeLabel = 'Out of Stock';
                } elseif ($stockQty <= 5) {
                    $badgeClass = 'badge-low';
                    $badgeLabel = $stockQty . ' Left';
                } else {
                    $badgeClass = '';
                    $badgeLabel = 'In Stock (' . $stockQty . ')';
                }
                ?>
                <article class="product-card">
                    <img
                        src="<?= esc($imgSrc) ?>"
                        alt="<?= esc($product['name'] ?? 'Product') ?>"
                        class="product-thumb"
                    >
                    <div class="product-body">
                        <h3 class="product-title"><?= esc($product['name'] ?? 'Unnamed Product') ?></h3>
                        <div class="product-meta">
                            <span class="product-price"><?= number_format((float) ($product['price'] ?? 0), 2) ?></span>
                            <span class="badge <?= $badgeClass ?>"><?= esc($badgeLabel) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>No featured products available right now.</p>
        </div>
    <?php endif; ?>
</div>

    </div>
</body>
</html>