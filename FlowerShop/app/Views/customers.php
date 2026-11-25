<?= view('templates/header') ?>
<h1>Customer Accounts</h1>
<p><a href="<?= base_url('customers/new') ?>">+ Add New Customer</a></p>

<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= esc($customer['full_name']) ?></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['phone']) ?></td>
                    <td><?= esc($customer['created_at']) ?></td>
                    <td>
                        <a href="<?= base_url('customers/edit/' . $customer['id']) ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No customer records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= view('templates/footer') ?>