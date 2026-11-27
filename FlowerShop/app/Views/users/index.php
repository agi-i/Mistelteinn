<?= view('partials/header', ['pageTitle' => 'User Accounts - Mistelteinn']) ?>

<style>
    .users-table th,
    .users-table td {
        padding: 8px 12px;
        vertical-align: middle;
    }
    .users-table th.col-avatar,
    .users-table td.col-avatar {
        width: 68px;
        text-align: center;
        padding-left: 8px;
        padding-right: 8px;
    }
    .users-table th.col-username,
    .users-table td.col-username {
        width: 16%;
    }
    .users-table th.col-name,
    .users-table td.col-name {
        width: 26%;
    }
    .users-table .avatar-img {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border-radius: 50%;
        display: block;
        margin: 0 auto;
    }
    .users-table .inline-delete-form {
        display: inline-flex;
        margin: 0;
    }
    .users-table .btn-delete {
        background: #dc2626;
        color: #ffffff;
    }
    .users-table .btn-delete:hover {
        background: #b91c1c;
    }
</style>

<div class="card">
    <div class="page-header">
        <div class="header-left">
            <h1>User Accounts</h1>
        </div>
        <div class="btn-group">
            <a href="<?= base_url('/users/new') ?>" class="btn btn-accent">+ Add User</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th class="col-avatar">Avatar</th>
                    <th class="col-username">Username</th>
                    <th class="col-name">Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                        $username = $user['username'] ?? '';
                        $displayName = $user['full_name'] ?? $user['name'] ?? '';
                        $labelName = $displayName !== '' ? $displayName : ($username !== '' ? $username : 'this user');
                        $avatarSrc = $user['avatar_url']
                            ?? (!empty($user['avatar'])
                            ? base_url('uploads/avatars/' . $user['avatar'])
                            : base_url('uploads/avatars/default-avatar.png'));
                        ?>
                        <tr>
                            <td class="col-avatar">
                                <img src="<?= esc($avatarSrc) ?>" alt="<?= esc($displayName !== '' ? $displayName : 'User') ?>" class="avatar-img">
                            </td>
                            <td class="col-username"><?= esc($username) ?></td>
                            <td class="col-name"><?= esc($displayName) ?></td>
                            <td><?= esc($user['email'] ?? '') ?></td>
                            <td><span class="badge"><?= esc(ucfirst($user['role'] ?? 'Staff')) ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/users/edit/' . esc($user['id'])) ?>" class="btn">Edit</a>
                                    <form
                                        action="<?= base_url('/users/delete/' . esc($user['id'])) ?>"
                                        method="post"
                                        class="inline-delete-form"
                                        onsubmit="return confirm('Delete user &quot;<?= esc($labelName, 'js') ?>&quot;?');"
                                    >
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>