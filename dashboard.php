<?php


require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

requireUser();

$user = currentUser();

if (($user['role'] ?? '') === 'staff') {
    redirect(appUrl('staff/dashboard.php'));
}



$stmt = $pdo->prepare("
    SELECT
        i.*,

        (
            SELECT f.id
            FROM items f
            WHERE f.matched_lost_item_id = i.id
              AND f.item_type = 'found'
              AND f.status IN ('approved', 'claimed', 'issued')
            ORDER BY f.id DESC
            LIMIT 1
        ) AS matched_found_id,

        (
            SELECT f.status
            FROM items f
            WHERE f.matched_lost_item_id = i.id
              AND f.item_type = 'found'
              AND f.status IN ('approved', 'claimed', 'issued')
            ORDER BY f.id DESC
            LIMIT 1
        ) AS matched_found_status

    FROM items i

    WHERE i.user_id = ?

    ORDER BY i.id DESC
");

$stmt->execute([$user['id']]);

$myItems = $stmt->fetchAll(PDO::FETCH_ASSOC);




$available = $pdo->query("
    SELECT *
    FROM items
    WHERE status = 'approved'
    ORDER BY id DESC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);



$activities = [];

try {

    $a = $pdo->prepare("
        SELECT *
        FROM activity_logs
        WHERE actor_id = ?
          AND (
              actor_role = ?
              OR actor_type = ?
          )
        ORDER BY id DESC
        LIMIT 50
    ");

    $a->execute([
        $user['id'],
        $user['role'],
        $user['role']
    ]);

    $activities = $a->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {

    try {

        $a = $pdo->prepare("
            SELECT *
            FROM activity_logs
            WHERE actor_id = ?
              AND actor_role = ?
            ORDER BY id DESC
            LIMIT 50
        ");

        $a->execute([
            $user['id'],
            $user['role']
        ]);

        $activities = $a->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $ignore) {
        $activities = [];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-heading">

    <div>

        <h1>My Panel</h1>

        <p>
            Welcome, <?php echo e($user['name']); ?>.
            Track your lost and found reports and review your account activity.
        </p>

    </div>

    <div class="card-actions">

        <a
            class="btn outline"
            href="<?php echo appUrl('report-lost.php'); ?>">
            + Report Lost Item
        </a>

        <a
            class="btn"
            href="<?php echo appUrl('report-found.php'); ?>">
            + Report Found Item
        </a>

    </div>

</div>


<div class="quick-actions">

    <a
        class="quick-card"
        href="<?php echo appUrl('report-lost.php'); ?>">
        <strong>Report Lost</strong>
        <span>Create and track your lost-item report</span>
    </a>

    <a
        class="quick-card"
        href="<?php echo appUrl('report-found.php'); ?>">
        <strong>Report Found</strong>
        <span>Report property you have found</span>
    </a>

    <a
        class="quick-card"
        href="<?php echo appUrl('items.php?type=lost'); ?>">
        <strong>Lost Items</strong>
        <span>Browse reported lost property</span>
    </a>

    <a
        class="quick-card"
        href="<?php echo appUrl('items.php?type=found'); ?>">
        <strong>Found Items</strong>
        <span>Browse approved found reports</span>
    </a>

</div>


<div class="card section-gap">

    <p>

        <strong>Claim reminder:</strong>

        To claim property, visit the CUEA Lost &amp; Found desk with a valid
        ID/Passport and valid Police Abstract.

    </p>

</div>


<div class="card table-wrap section-gap">

    <h2>My Item Reports</h2>

    <table>

        <thead>

            <tr>

                <th>Item</th>

                <th>Type</th>

                <th>Status</th>

                <th>Date</th>

                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($myItems as $i): ?>

                <?php



                $hasApprovedFoundMatch =
                    $i['item_type'] === 'lost'
                    &&
                    !empty($i['matched_found_id']);



                if ($hasApprovedFoundMatch) {

                    $displayType = 'Found';
                } else {

                    $displayType = itemTypeLabel($i['item_type']);
                }




                if ($hasApprovedFoundMatch) {

                    $displayStatus = 'Found';
                    $displayStatusClass = 'badge success';
                } else {

                    $displayStatus = statusLabel($i['status']);
                    $displayStatusClass = badgeClass($i['status']);
                }

                ?>

                <tr>

                    <td>

                        <strong>
                            <?php echo e($i['title']); ?>
                        </strong>

                    </td>


                    <td>

                        <?php if ($hasApprovedFoundMatch): ?>

                            <span class="badge success">
                                Found
                            </span>

                        <?php else: ?>

                            <?php echo e($displayType); ?>

                        <?php endif; ?>

                    </td>


                    <td>

                        <span class="<?php echo e($displayStatusClass); ?>">

                            <?php echo e($displayStatus); ?>

                        </span>

                    </td>


                    <td>

                        <?php echo e($i['date_reported']); ?>

                    </td>


                    <td>

                        <a
                            class="btn outline btn-sm"
                            href="<?php echo appUrl(
                                        'item.php?id=' . (int)$i['id']
                                    ); ?>">
                            More Details
                        </a>


                        <?php if ($hasApprovedFoundMatch): ?>

                            <a
                                class="btn btn-sm"
                                href="<?php echo appUrl(
                                            'claim.php?id=' .
                                                (int)$i['matched_found_id']
                                        ); ?>">
                                Claim Item
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>


            <?php if (!$myItems): ?>

                <tr>

                    <td colspan="5">

                        You have not reported any items yet.

                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>


<div class="card table-wrap section-gap">

    <h2>My Account Activities</h2>

    <p class="small">
        This is your personal audit trail for account and reporting actions.
    </p>

    <table>

        <thead>

            <tr>

                <th>Action</th>

                <th>Details</th>

                <th>Date &amp; Time</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($activities as $a): ?>

                <tr>

                    <td>
                        <?php echo e(
                            $a['action'] ?? 'Activity'
                        ); ?>
                    </td>

                    <td>
                        <?php echo e(
                            $a['description']
                                ??
                                ($a['details'] ?? '')
                        ); ?>
                    </td>

                    <td>
                        <?php echo e(
                            $a['created_at'] ?? ''
                        ); ?>
                    </td>

                </tr>

            <?php endforeach; ?>


            <?php if (!$activities): ?>

                <tr>

                    <td colspan="3">
                        No activities have been recorded for this account yet.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>


<div class="card section-gap">

    <div class="page-heading compact">

        <div>

            <h2>Available Items</h2>

            <p>
                Open an item to see more information and claim requirements.
            </p>

        </div>

        <a href="<?php echo appUrl('items.php'); ?>">
            View all →
        </a>

    </div>


    <div class="item-grid">

        <?php foreach ($available as $i): ?>

            <article class="mini-item">

                <strong>
                    <?php echo e($i['title']); ?>
                </strong>

                <span>
                    <?php echo e(
                        itemTypeLabel($i['item_type'])
                            .
                            ' · '
                            .
                            $i['category']
                    ); ?>
                </span>

                <a
                    href="<?php echo appUrl(
                                'item.php?id=' . (int)$i['id']
                            ); ?>">
                    More Details
                </a>

            </article>

        <?php endforeach; ?>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>