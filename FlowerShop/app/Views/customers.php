<?= view('partials/header', ['pageTitle' => 'Customer Accounts - Mistelteinn']) ?>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>Customer Accounts</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/customers/new') ?>" class="btn btn-accent">+ Add Customer</a>
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
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><strong><?= esc($customer['full_name'] ?? $customer['name'] ?? '') ?></strong></td>
                            <td><?= esc($customer['email'] ?? '') ?></td>
                            <td><?= esc($customer['phone'] ?? '') ?></td>
                            <td><?= esc($customer['created_at'] ?? '—') ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/customers/edit/' . esc($customer['id'])) ?>" class="btn">Edit</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>