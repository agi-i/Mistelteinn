<?= view('partials/header', ['pageTitle' => 'User Accounts - Mistelteinn']) ?>

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
        <table>
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
                        <?php
                        $avatarSrc = !empty($user['avatar'])
                            ? base_url('uploads/users/' . $user['avatar'])
                            : base_url('uploads/users/default-avatar.png');
                        ?>
                        <tr>
                            <td><img src="<?= esc($avatarSrc) ?>" alt="<?= esc($user['name'] ?? 'User') ?>" class="avatar-img"></td>
                            <td><strong><?= esc($user['name'] ?? '') ?></strong></td>
                            <td><?= esc($user['email'] ?? '') ?></td>
                            <td><span class="badge"><?= esc(ucfirst($user['role'] ?? 'Staff')) ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/users/edit/' . esc($user['id'])) ?>" class="btn">Edit</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</body>
</html>