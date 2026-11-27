<?php
$isEdit = isset($user) && is_array($user) && !empty($user['id']);
$pageTitle = ($isEdit ? 'Edit User' : 'Add User') . ' - Mistelteinn';
?>
<?= view('partials/header', ['pageTitle' => $pageTitle]) ?>

<style>
    .card {
        max-width: 760px;
        margin: 0 auto;
    }
    .error-box {
        background: #fef2f2;
        border-left: 4px solid #b91c1c;
        color: #7f1d1d;
        padding: 12px 16px;
        border-radius: 5px;
        margin-bottom: 18px;
        font-size: 14px;
    }
    .error-box ul {
        margin: 0;
        padding-left: 18px;
    }
    .form-group {
        margin-bottom: 16px;
    }
    label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 6px;
        font-size: 14px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="file"],
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-size: 14px;
        background: #fafbfc;
        color: #333;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
        outline: none;
        border-color: #1abc9c;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
    }
    .preview-box {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: #475569;
    }
    .preview-img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #d5dce0;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 22px;
        padding-top: 16px;
        border-top: 1px solid #eef2f4;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1><?= $isEdit ? 'Edit User' : 'Add User' ?></h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="error-box">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $isEdit ? base_url('/users/' . esc($user['id'])) : base_url('/users') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= esc(old('name', $user['name'] ?? '')) ?>"
                placeholder="e.g., Maria Santos"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email', $user['email'] ?? '')) ?>"
                placeholder="e.g., maria@example.com"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password <?= $isEdit ? '(leave blank to keep current password)' : '' ?></label>
            <input
                type="password"
                id="password"
                name="password"
                <?= $isEdit ? '' : 'required' ?>
            >
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <?php $selectedRole = old('role', $user['role'] ?? 'staff'); ?>
            <select id="role" name="role" required>
                <option value="staff" <?= $selectedRole === 'staff' ? 'selected' : '' ?>>Staff</option>
                <option value="cashier" <?= $selectedRole === 'cashier' ? 'selected' : '' ?>>Cashier</option>
                <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="avatar">Avatar</label>
            <?php if ($isEdit && !empty($user['avatar_url'])): ?>
                <div class="preview-box">
                    <img src="<?= esc($user['avatar_url']) ?>" alt="Current avatar" class="preview-img">
                    <span>Current avatar (upload a new file to replace it)</span>
                </div>
            <?php endif; ?>
            <input type="file" id="avatar" name="avatar" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">
                <?= $isEdit ? 'Update User' : 'Save User' ?>
            </button>
            <a href="<?= base_url('/users') ?>" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>

    </div>
</body>
</html>