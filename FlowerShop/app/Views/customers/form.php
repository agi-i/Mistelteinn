<?php
$isEdit = isset($customer) && is_array($customer) && !empty($customer['id']);
$pageTitle = ($isEdit ? 'Edit Customer' : 'Add Customer') . ' - Mistelteinn';
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
    input[type="email"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-size: 14px;
        background: #fafbfc;
        color: #333;
    }
    input[type="text"]:focus,
    input[type="email"]:focus {
        outline: none;
        border-color: #1abc9c;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.15);
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
            <h1><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?></h1>
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

    <form action="<?= $isEdit ? base_url('/customers/' . esc($customer['id'])) : base_url('/customers') ?>" method="post">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>"
                placeholder="e.g., Juan Dela Cruz"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email', $customer['email'] ?? '')) ?>"
                placeholder="e.g., juan@example.com"
            >
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"
                placeholder="e.g., 09171234567"
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">
                <?= $isEdit ? 'Update Customer' : 'Save Customer' ?>
            </button>
            <a href="<?= base_url('/customers') ?>" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>

    </div>
</body>
</html>