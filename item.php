<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$id = (int)($_GET['id'] ?? 0);
$s = $pdo->prepare("SELECT * FROM items WHERE id=? AND status IN ('approved','claimed','issued')");
$s->execute([$id]);
$item = $s->fetch();
if (!$item) die('Item not found.');
require_once __DIR__ . '/includes/header.php'; ?>
<div class="grid-2">
    <div class="card"><?php if ($item['image']): ?><img class="detail-image" src="<?php echo appUrl('uploads/' . $item['image']); ?>" alt="Item image"><?php else: ?><div class="detail-image placeholder">No image</div><?php endif; ?></div>
    <div class="card">
        <div class="meta"><span class="badge <?php echo $item['item_type'] === 'found' ? 'success' : 'danger'; ?>"><?php echo e(itemTypeLabel($item['item_type'])); ?></span><span class="<?php echo badgeClass($item['status']); ?>"><?php echo e(statusLabel($item['status'])); ?></span></div>
        <h1><?php echo e($item['title']); ?></h1>
        <div class="detail-list">
            <p><strong>Category</strong><span><?php echo e($item['category']); ?></span></p>
            <p><strong>Location</strong><span><?php echo e($item['location']); ?></span></p>
            <p><strong>Date</strong><span><?php echo e($item['date_reported']); ?></span></p><?php if ($item['brand']): ?><p><strong>Brand</strong><span><?php echo e($item['brand']); ?></span></p><?php endif; ?><?php if ($item['model']): ?><p><strong>Model</strong><span><?php echo e($item['model']); ?></span></p><?php endif; ?><?php if ($item['color']): ?><p><strong>Colour</strong><span><?php echo e($item['color']); ?></span></p><?php endif; ?><?php if ($item['serial_number']): ?><p><strong>Serial / IMEI</strong><span><?php echo e($item['serial_number']); ?></span></p><?php endif; ?>
        </div>
        <h3>Description</h3>
        <p><?php echo $item['description'] !== '' ? nl2br(e($item['description'])) : 'No additional description was provided.'; ?></p>
        <div class="card-actions"><?php if ($item['status'] === 'approved'): ?><a class="btn" href="<?php echo appUrl('claim.php?id=' . $id); ?>">Claim Item</a><?php endif; ?><?php if ($item['item_type'] === 'lost' && $item['status'] === 'approved'): ?><a class="btn secondary" href="<?php echo appUrl('report-found.php?lost_id=' . $id); ?>">I Found This Item</a><?php endif; ?></div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>