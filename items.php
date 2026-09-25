<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$search = trim($_GET['search'] ?? '');
$type = trim($_GET['type'] ?? '');
$category = trim($_GET['category'] ?? '');
$claim = trim($_GET['claim'] ?? '');
$q = "SELECT * FROM items WHERE status IN ('approved','claimed','issued')";
$p = [];
if ($search !== '') {
    $q .= " AND (title LIKE :search OR description LIKE :search OR brand LIKE :search OR model LIKE :search)";
    $p['search'] = "%$search%";
}
if (in_array($type, ['lost', 'found'], true)) {
    $q .= ' AND item_type=:type';
    $p['type'] = $type;
}
if ($category !== '') {
    $q .= ' AND category=:category';
    $p['category'] = $category;
}
if ($claim === 'claimed') $q .= " AND status IN ('claimed','issued')";
elseif ($claim === 'not_claimed') $q .= " AND status='approved'";
$q .= ' ORDER BY id DESC';
$s = $pdo->prepare($q);
$s->execute($p);
$items = $s->fetchAll();
$user = currentUser();
require_once __DIR__ . '/includes/header.php'; ?>
<div class="page-heading">
    <div>
        <h1>Lost &amp; Found Items</h1>
        <p>Browse published reports. Reporting that an item was found requires login.</p>
    </div><?php if ($user): ?><a class="btn" href="<?php echo appUrl('report-found.php'); ?>">+ Report Found Item</a><?php endif; ?>
</div>
<div class="card">
    <form class="filters" method="GET"><input name="search" placeholder="Search items..." value="<?php echo e($search); ?>"><select name="type">
            <option value="">Lost &amp; Found</option>
            <option value="lost" <?php echo $type === 'lost' ? 'selected' : ''; ?>>Lost</option>
            <option value="found" <?php echo $type === 'found' ? 'selected' : ''; ?>>Found</option>
        </select><select name="claim">
            <option value="">Claimed & Not Claimed</option>
            <option value="claimed" <?php echo $claim === 'claimed' ? 'selected' : ''; ?>>Claimed</option>
            <option value="not_claimed" <?php echo $claim === 'not_claimed' ? 'selected' : ''; ?>>Not Claimed</option>
        </select><select name="category">
            <option value="">All categories</option><?php foreach (itemCategories() as $cat): ?><option value="<?php echo e($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo e($cat); ?></option><?php endforeach; ?>
        </select><button class="btn">Filter</button></form>
    <div class="item-grid"><?php if (!$items): ?><div class="empty-state">No matching items found.</div><?php endif; ?><?php foreach ($items as $item): ?><article class="card item-card"><?php if ($item['image']): ?><img src="<?php echo appUrl('uploads/' . $item['image']); ?>" alt="Item image"><?php else: ?><div class="item-image placeholder">No image</div><?php endif; ?><div class="item-card-body">
                    <div class="meta"><span class="badge <?php echo $item['item_type'] === 'found' ? 'success' : 'danger'; ?>"><?php echo e(itemTypeLabel($item['item_type'])); ?></span><span><?php echo e($item['category']); ?></span><span class="<?php echo badgeClass($item['status']); ?>"><?php echo e(statusLabel($item['status'])); ?></span></div>
                    <h3><?php echo e($item['title']); ?></h3>
                    <p class="small"><?php echo e($item['location']); ?> · <?php echo e($item['date_reported']); ?></p>
                    <div class="card-actions"><a class="btn outline" href="<?php echo appUrl('item.php?id=' . (int)$item['id']); ?>">More Details</a><?php if ($item['status'] === 'approved'): ?><a class="btn" href="<?php echo appUrl('claim.php?id=' . (int)$item['id']); ?>">Claim Item</a><?php endif; ?><?php if ($item['item_type'] === 'lost' && $item['status'] === 'approved'): ?><a class="btn secondary" href="<?php echo appUrl('report-found.php?lost_id=' . (int)$item['id']); ?>">I Found It</a><?php endif; ?></div>
                </div>
            </article><?php endforeach; ?></div>
</div><?php require_once __DIR__ . '/includes/footer.php'; ?>