<?= view('partials/header', ['pageTitle' => 'Sales - Mistelteinn']) ?>

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
                    <th>Flower Note</th>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $sale): ?>
                        <?php
                        $saleId = (int) ($sale['id'] ?? 0);
                        $amount = (float) ($sale['total_price'] ?? $sale['total_amount'] ?? 0);
                        $paymentLabel = strtoupper((string) ($sale['payment_method'] ?? 'cash'));
                        ?>
                        <tr>
                            <td><strong>#<?= $saleId ?></strong></td>
                            <td><?= esc($sale['customer_name'] ?? 'Walk-in Customer') ?></td>
                            <td>
                                <?php if (!empty($sale['note'])): ?>
                                    <?= esc($sale['note']) ?>
                                <?php else: ?>
                                    <span style="color: #888;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($paymentLabel) ?></td>
                            <td><strong><?= number_format($amount, 2) ?></strong></td>
                            <td><?= esc($sale['created_at'] ?? '—') ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/sales/show/' . $saleId) ?>" class="btn">View</a>
                                    <form
                                        action="<?= base_url('/sales/delete/' . $saleId) ?>"
                                        method="post"
                                        style="display: inline;"
                                        onsubmit="return confirm('Delete Sale #<?= $saleId ?>?');"
                                    >
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No sales recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>