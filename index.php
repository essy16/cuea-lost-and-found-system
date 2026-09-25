<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$s = $pdo->query("SELECT * FROM items WHERE item_type='lost' AND status IN ('approved','claimed') ORDER BY id DESC LIMIT 12");
$items = $s->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <p class="eyebrow" style="color:#fff">CUEA LOST &amp; FOUND</p>
    <h1>Find what was lost. Help return what was found.</h1>
    <p>Browse items reported as lost at CUEA. You can view details without an account. To report that you found an item, you will be asked to login.</p>
    <div class="hero-actions"><a class="btn secondary" href="<?php echo appUrl('items.php?type=lost'); ?>">Browse Lost Items</a><?php if (!isUserLoggedIn() && !isAdminLoggedIn()): ?><a class="btn outline" style="color:#fff;border-color:#fff" href="<?php echo appUrl('auth/login.php'); ?>">User / Staff Login</a><a class="btn outline" style="color:#fff;border-color:#fff" href="<?php echo appUrl('admin/login.php'); ?>">Admin Login</a><?php endif; ?></div>
</section>
<div class="page-heading">
    <div> 
        <h2>Recently Reported Lost Items</h2>
        <p>Use More Details to see the complete description.</p>
    </div><a class="btn outline" href="<?php echo appUrl('items.php?type=lost'); ?>">View All</a>
</div>
<div class="item-grid"><?php if (!$items): ?><div class="card empty-state">No lost items have been published yet.</div><?php endif; ?><?php foreach ($items as $item): ?><article class="card item-card"><?php if ($item['image']): ?><img src="<?php echo appUrl('uploads/' . $item['image']); ?>" alt="Item image"><?php else: ?><div class="item-image placeholder">No image</div><?php endif; ?><div class="item-card-body">
                <div class="meta"><span class="badge danger">Lost</span><span><?php echo e($item['category']); ?></span><span class="<?php echo badgeClass($item['status']); ?>"><?php echo e(statusLabel($item['status'])); ?></span></div>
                <h3><?php echo e($item['title']); ?></h3>
                <p class="small"><?php echo e($item['location']); ?> · <?php echo e($item['date_reported']); ?></p>
                <div class="card-actions"><a class="btn outline" href="<?php echo appUrl('item.php?id=' . (int)$item['id']); ?>">More Details</a><?php if ($item['status'] === 'approved'): ?><a class="btn" href="<?php echo appUrl('report-found.php?lost_id=' . (int)$item['id']); ?>">I Found This Item</a><?php endif; ?></div>
            </div>
        </article><?php endforeach; ?></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>