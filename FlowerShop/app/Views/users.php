<?= view('templates/header') ?>
<h1>User Accounts</h1>
<p><a href="<?= base_url('users/new') ?>">+ Add New User</a></p>

<table>
    <thead>
        <tr>
            <th>Avatar</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Date Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?php if (!empty($user['avatar']) && file_exists(FCPATH . 'uploads/' . $user['avatar'])): ?>
                            <img src="<?= base_url('uploads/' . $user['avatar']) ?>" width="40" height="40" style="border-radius: 50%; object-fit: cover;" alt="Avatar">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/40" width="40" height="40" style="border-radius: 50%;" alt="Default Placeholder">
                        <?php endif; ?>
                    </td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><?= esc($user['created_at']) ?></td>
                    <td>
                        <a href="<?= base_url('users/edit/' . $user['id']) ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No user records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= view('templates/footer') ?>