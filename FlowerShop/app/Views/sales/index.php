<?= view('partials/header', ['pageTitle' => 'Sales - Mistelteinn']) ?>

<style>
    nav a {
        font-size: 15px;
    }
    h1 {
        font-size: 24px;
    }
    h2 {
        font-size: 18px;
    }
    p,
    th,
    td,
    .btn,
    .badge {
        font-size: 14px;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Sales</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/sales/new') ?>" class="btn btn-accent">+ Record Sale</a>
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
                    <th>Sale ID</th>
                    <th>Customer</th>
                    <th>Cashier / User</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $sale): ?>
                        <?php
                        $totalAmount  = (float) ($sale['total_amount'] ?? $sale['total'] ?? 0);
                        $customerName = $sale['customer_name'] ?? $sale['customer'] ?? 'Walk-in Customer';
                        $userName     = $sale['user_name'] ?? $sale['cashier_name'] ?? $sale['user'] ?? 'Staff';
                        $status       = $sale['status'] ?? 'Completed';
                        $createdAt    = $sale['created_at'] ?? $sale['sale_date'] ?? '—';
                        ?>
                        <tr>
                            <td><strong>#<?= esc($sale['id'] ?? '') ?></strong></td>
                            <td><?= esc($customerName) ?></td>
                            <td><?= esc($userName) ?></td>
                            <td><strong><?= number_format($totalAmount, 2) ?></strong></td>
                            <td><span class="badge"><?= esc(ucfirst($status)) ?></span></td>
                            <td><?= esc($createdAt) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/sales/' . esc($sale['id'])) ?>" class="btn">View</a>
                                    <form action="<?= base_url('/sales/' . esc($sale['id'])) ?>" method="post" onsubmit="return confirm('Delete this sale record?');" style="margin:0;">
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
                        <td colspan="7">No sales found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>