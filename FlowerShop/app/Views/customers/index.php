<!-- app/Views/customers/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers</title>
</head>
<body>
    <h1>Customers</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <a href="<?= site_url('customers/new') ?>">+ Add Customer</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= esc($customer['name']) ?></td>
                        <td><?= esc($customer['email'] ?? '-') ?></td>
                        <td><?= esc($customer['phone'] ?? '-') ?></td>
                        <td>
                            <a href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>">Edit</a>
                            <form action="<?= site_url('customers/' . $customer['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Delete this customer?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No customers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>