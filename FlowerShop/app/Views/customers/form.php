<!-- app/Views/customers/form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($customer) && $customer ? 'Edit Customer' : 'Add Customer' ?></title>
</head>
<body>
    <h1><?= isset($customer) && $customer ? 'Edit Customer' : 'Add Customer' ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php $isEdit = isset($customer) && $customer; ?>
    <form action="<?= $isEdit ? site_url('customers/' . $customer['id']) : site_url('customers') ?>" method="post">
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= esc(old('name', $customer['name'] ?? '')) ?>" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= esc(old('email', $customer['email'] ?? '')) ?>">
        </p>

        <p>
            <label>Phone:</label><br>
            <input type="text" name="phone" value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>">
        </p>

        <button type="submit"><?= $isEdit ? 'Update Customer' : 'Save Customer' ?></button>
        <a href="<?= site_url('customers') ?>">Cancel</a>
    </form>
</body>
</html>