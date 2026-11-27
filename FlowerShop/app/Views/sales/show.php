<?= view('partials/header', ['pageTitle' => 'Sale #' . (int) ($sale['id'] ?? 0) . ' - Mistelteinn']) ?>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Sale #<?= (int) ($sale['id'] ?? 0) ?></h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/sales') ?>" class="btn">Back to Sales</a>
        </div>
    </div>

    <div class="form-group">
        <strong>Customer:</strong> <?= esc($sale['customer_name'] ?? 'Walk-in Customer') ?><br>
        <strong>Payment Method:</strong> <?= esc(strtoupper((string) ($sale['payment_method'] ?? 'cash'))) ?><br>
        <strong>Total Amount:</strong> <?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?>
    </div>

    <div class="form-group">
        <strong>Flower Note:</strong>
        <div class="alert" style="margin-top: 6px;">
            <?php if (!empty($sale['note'])): ?>
                <?= nl2br(esc($sale['note'])) ?>
            <?php else: ?>
                No note included with this order.
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= esc($item['product_name'] ?? 'Product #' . ($item['product_id'] ?? '')) ?></td>
                            <td><?= (int) ($item['quantity'] ?? 0) ?></td>
                            <td><?= number_format((float) ($item['unit_price'] ?? 0), 2) ?></td>
                            <td><?= number_format((float) ($item['subtotal'] ?? 0), 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No items found for this sale.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>