<!-- app/Views/users/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Members</title>
</head>
<body>
    <h1>Staff Members</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <a href="<?= site_url('users/new') ?>">+ Add Staff</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <!-- Display-ready avatar URL prepared in controller -->
                            <img src="<?= esc($user['avatar_url']) ?>" alt="<?= esc($user['name']) ?>" width="50" height="50">
                        </td>
                        <td><?= esc($user['name']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><?= esc($user['role']) ?></td>
                        <td>
                            <a href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a>
                            <form action="<?= site_url('users/' . $user['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Delete this staff member?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No staff members found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>