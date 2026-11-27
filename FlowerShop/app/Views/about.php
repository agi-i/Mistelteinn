<?= view('partials/header', ['pageTitle' => 'About - Mistelteinn']) ?>

<style>
    .about-lead {
        color: #475569;
        font-size: 15px;
        line-height: 1.65;
        margin-bottom: 24px;
    }
    .about-highlights {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .highlight-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
    }
    .highlight-box h3 {
        margin: 0 0 6px;
        font-size: 15px;
        color: #1e293b;
    }
    .highlight-box p {
        margin: 0;
        font-size: 13.5px;
        color: #475569;
        line-height: 1.5;
    }
    .section-divider {
        border: 0;
        border-top: 2px solid #eef2f4;
        margin: 28px 0;
    }
    .section-heading {
        margin: 0 0 16px;
        font-size: 20px;
        color: #1e293b;
    }
    .section-subtext {
        margin: 0 0 18px;
        color: #475569;
        font-size: 14px;
    }

    /* Dedicated Owner Area & Wide Split Card */
    .owner-area {
        background: #cfe2f3;
        border: 1px solid #cfe2f3;
        border-radius: 12px;
        padding: 22px 24px;
    }
    .owner-card {
        width: 100%;
        max-width:;
        margin: 0 auto;
        border:;
        border-radius: 12px;
        padding: 22px 28px;
        background: #ffffff;
        box-shadow: 0 6px 16px rgba(22, 160, 133, 0.12);
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: left;
        gap: 28px;
    }
    .owner-media {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .owner-avatar-img {
        width: 140px;
        height: 140px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #1c8ff7;
        background: #f1f5f9;
        display: block;
    }
    .owner-avatar-fallback {
        width: 140px;
        height: 140px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        font-weight: bold;
        font-size: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #16a085;
    }
    .owner-details {
        flex: 1 1 auto;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .owner-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #0f766e;
        background: #ccfbf1;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .owner-name {
        margin: 0;
        font-size: 21px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        word-break: break-word;
    }
    .owner-bio {
        margin: 0;
        font-size: 14px;
        color: #475569;
        line-height: 1.5;
    }
    @media (max-width: 600px) {
        .owner-card {
            flex-direction: column;
            text-align: center;
            padding: 20px;
            gap: 16px;
        }
        .owner-details {
            align-items: center;
        }
    }

    /* Meet the Staff Grid */
    .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 18px;
    }
    .staff-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 22px 16px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .staff-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.08);
    }
    .staff-avatar-img {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #1c8ff7;
        background: #f1f5f9;
    }
    .staff-avatar-fallback {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: #16a085;
        color: #ffffff;
        font-weight: bold;
        font-size: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #0f766e;
    }
    .staff-name {
        margin: 0;
        font-size: 15.5px;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    .empty-staff {
        color: #64748b;
        font-size: 14px;
        padding: 12px 0;
    }
</style>

<div class="card">
    <div class="page-header">
        <h1>About Mistelteinn</h1>
        <div class="btn-group">
            <a href="<?= base_url('/catalog') ?>" class="btn btn-accent">Browse Catalog</a>
        </div>
    </div>

    <p class="about-lead">
        Mistelteinn is a neighborhood flower shop dedicated to thoughtfully arranged blooms, dependable daily
        inventory, and a smooth shopping experience from catalog selection to counter checkout.
    </p>

    <hr class="section-divider">

    <section class="owner-area" aria-label="Shop Owner">
        <h2 class="section-heading">Owner</h2>

        <?php if (!empty($owner)): ?>
            <div class="owner-card">
                <div class="owner-media">
                    <?php if (!empty($owner['avatar_url'])): ?>
                        <img
                            src="<?= esc($owner['avatar_url']) ?>"
                            alt="<?= esc($owner['full_name']) ?>"
                            class="owner-avatar-img"
                        >
                    <?php else: ?>
                        <div class="owner-avatar-fallback"><?= esc($owner['initials']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="owner-details">
                    <h3 class="owner-name"><?= esc($owner['full_name']) ?></h3>
                </div>
            </div>
        <?php else: ?>
            <p class="empty-staff">Owner account not found.</p>
        <?php endif; ?>
    </section>

    <hr class="section-divider">

    <section aria-label="Meet the Staff">
        <h2 class="section-heading">Meet the Staff</h2>
        <p class="section-subtext">Our Mistelteinn team is here to help you find the perfect flowers for any occasion.</p>

        <?php if (!empty($staff)): ?>
            <div class="staff-grid">
                <?php foreach ($staff as $member): ?>
                    <div class="staff-card">
                        <?php if (!empty($member['avatar_url'])): ?>
                            <img
                                src="<?= esc($member['avatar_url']) ?>"
                                alt="<?= esc($member['full_name']) ?>"
                                class="staff-avatar-img"
                            >
                        <?php else: ?>
                            <div class="staff-avatar-fallback"><?= esc($member['initials']) ?></div>
                        <?php endif; ?>

                        <h3 class="staff-name"><?= esc($member['full_name']) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-staff">No staff accounts found.</p>
        <?php endif; ?>
    </section>
</div>

    </div>
</body>
</html>