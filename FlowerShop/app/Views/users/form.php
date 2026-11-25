<!-- app/Views/users/form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($user) && $user ? 'Edit Staff' : 'Add Staff' ?></title>
</head>
<body>
    <h1><?= isset($user) && $user ? 'Edit Staff' : 'Add Staff' ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php $isEdit = isset($user) && $user; ?>
    <form action="<?= $isEdit ? site_url('users/' . $user['id']) : site_url('users') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= esc(old('name', $user['name'] ?? '')) ?>" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
        </p>

        <p>
            <label>Password <?= $isEdit ? '(leave blank to keep current password)' : '' ?>:</label><br>
            <input type="password" name="password" <?= $isEdit ? '' : 'required' ?>>
        </p>

        <p>
            <label>Role:</label><br>
            <?php $selectedRole = old('role', $user['role'] ?? 'staff'); ?>
            <select name="role" required>
                <option value="staff" <?= $selectedRole === 'staff' ? 'selected' : '' ?>>Staff</option>
                <option value="cashier" <?= $selectedRole === 'cashier' ? 'selected' : '' ?>>Cashier</option>
                <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </p>

        <p>
            <label>Avatar:</label><br>
            <?php if ($isEdit && !empty($user['avatar_url'])): ?>
                <img src="<?= esc($user['avatar_url']) ?>" alt="Current avatar" width="70"><br>
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/*">
        </p>

        <button type="submit"><?= $isEdit ? 'Update Staff' : 'Save Staff' ?></button>
        <a href="<?= site_url('users') ?>">Cancel</a>
    </form>
</body>
</html>