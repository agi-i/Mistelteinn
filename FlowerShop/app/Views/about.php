<?= view('partials/header', ['pageTitle' => 'About - Mistelteinn']) ?>

<style>
    h2 {
        color: #2c3e50;
        margin: 22px 0 10px 0;
        font-size: 18px;
    }
    p {
        line-height: 1.6;
        color: #475569;
        margin: 0 0 12px 0;
        font-size: 14px;
    }

    /* Info Grid */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }
    .feature-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #1abc9c;
        border-radius: 6px;
        padding: 14px 16px;
    }
    .feature-box strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 15px;
    }
    .feature-box span {
        color: #475569;
        font-size: 14px;
        line-height: 1.5;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>About Mistelteinn</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/products') ?>" class="btn btn-accent">View Products</a>
            <a href="<?= base_url('/sales/new') ?>" class="btn">Record Sale</a>
        </div>
    </div>

    <p>
        This is a Point of Sale (POS) and inventory management web application built with <strong>CodeIgniter 4</strong> using a structured MVC architecture, RESTful routing, and a unified interface across all modules.
    </p>

    <h2>Core Modules</h2>
    <div class="feature-grid">
        <div class="feature-box">
            <strong>Products &amp; Inventory</strong>
            <span>Manage product catalog entries, pricing, stock levels, and uploaded product images.</span>
        </div>
        <div class="feature-box">
            <strong>Sales &amp; Receipts</strong>
            <span>Record transactions, compute line-item totals, deduct product stock, and view sale receipts.</span>
        </div>
        <div class="feature-box">
            <strong>Customer &amp; User Accounts</strong>
            <span>Maintain customer records for orders and sales alongside staff and user accounts.</span>
        </div>
    </div>
</div>

    </div>
</body>
</html>