<?php
// Detect the first URI segment (e.g. "products", "customers", "users", "sales")
// so the matching navigation tab is automatically marked as active.
$currentSegment = service('uri')->getSegment(1);

// Define tabs matching your POS system's features
$tabs = [
    [
        'label'   => 'Products',
        'url'     => site_url('products'),
        'segment' => 'products',
    ],
    [
        'label'   => 'Customers',
        'url'     => site_url('customers'),
        'segment' => 'customers',
    ],
    [
        'label'   => 'Staff / Users',
        'url'     => site_url('users'),
        'segment' => 'users',
    ],
    [
        'label'   => 'Record Sale',
        'url'     => site_url('sales/new'),
        'segment' => 'sales',
    ],
    [
        'label'   => 'Sales History',
        'url'     => site_url('sales'),
        'segment' => 'sales-history',
    ],
];
?>

<nav class="pos-tabs" aria-label="POS System Modules">
    <?php foreach ($tabs as $tab): ?>
        <?php
        // Highlight "Record Sale" on /sales/new and "Sales History" on /sales
        $isActive = false;
        if ($tab['segment'] === 'sales' && $currentSegment === 'sales' && service('uri')->getSegment(2) === 'new') {
            $isActive = true;
        } elseif ($tab['segment'] === 'sales-history' && $currentSegment === 'sales' && service('uri')->getSegment(2) !== 'new') {
            $isActive = true;
        } elseif ($currentSegment === $tab['segment']) {
            $isActive = true;
        }
        ?>
        <a href="<?= esc($tab['url']) ?>" class="pos-tab <?= $isActive ? 'is-active' : '' ?>">
            <?= esc($tab['label']) ?>
        </a>
    <?php endforeach; ?>
</nav>