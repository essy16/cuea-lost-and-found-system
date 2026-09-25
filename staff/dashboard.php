<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

requireStaff();

$staff = currentUser();
$section = $_GET['section'] ?? 'overview';




function staffOwnsItemReport(array $item, array $staff): bool
{

  if (
    !empty($item['item_reporter_user_id'])
    &&
    (int)$item['item_reporter_user_id'] === (int)$staff['id']
  ) {
    return true;
  }


  if (
    !empty($staff['staff_no'])
    &&
    !empty($item['reporter_staff_no'])
    &&
    trim($item['reporter_staff_no']) === trim($staff['staff_no'])
  ) {
    return true;
  }

  /*
    | Match using email
    */
  if (
    !empty($staff['email'])
    &&
    !empty($item['reporter_email'])
    &&
    strcasecmp(
      trim($item['reporter_email']),
      trim($staff['email'])
    ) === 0
  ) {
    return true;
  }

  return false;
}


/*
|--------------------------------------------------------------------------
| HANDLE STAFF ACTIONS
|--------------------------------------------------------------------------
*/

if (isPost()) {

  $action = $_POST['action'] ?? '';
  $id = (int)($_POST['id'] ?? 0);

  try {



    if (in_array($action, ['approve_item', 'reject_item'], true)) {

      $status =
        $action === 'approve_item'
        ? 'approved'
        : 'rejected';



      $check = $pdo->prepare("
                SELECT
                    id,
                    title,
                    item_type,
                    user_id AS item_reporter_user_id,
                    reporter_staff_no,
                    reporter_email,
                    matched_lost_item_id
                FROM items
                WHERE id = ?
                  AND status = 'pending'
                LIMIT 1
            ");

      $check->execute([$id]);

      $reportedItem = $check->fetch(PDO::FETCH_ASSOC);

      if (!$reportedItem) {
        throw new Exception('Pending item report not found.');
      }



      if (staffOwnsItemReport($reportedItem, $staff)) {
        throw new Exception(
          'You cannot approve or reject an item report that you personally submitted.'
        );
      }


      $q = $pdo->prepare("
                UPDATE items
                SET
                    status = ?,
                    reviewed_by_staff_id = ?
                WHERE id = ?
                  AND status = 'pending'
            ");

      $q->execute([
        $status,
        $staff['id'],
        $id
      ]);


      if ($q->rowCount()) {

        logActivity(
          $pdo,
          'staff',
          (int)$staff['id'],
          $staff['name'],
          ucfirst($status) . ' User Found Report',
          'Item #' . $id . ' - ' . $reportedItem['title'],
          'item',
          $id
        );
      }


      flash(
        'success',
        $status === 'approved'
          ? 'Found-item report approved successfully.'
          : 'Found-item report rejected.'
      );
    } elseif ($action === 'issue_item') {

      if (!canStaffIssue()) {
        throw new Exception(
          'Your account does not have Can Issue Item permission.'
        );
      }


      $c = $pdo->prepare("
                SELECT
                    c.*,

                    i.title,
                    i.item_type,

                    i.user_id AS item_reporter_user_id,
                    i.reporter_staff_no,
                    i.reporter_email

                FROM claims c

                JOIN items i
                    ON i.id = c.item_id

                WHERE c.id = ?
                  AND c.status = 'approved'

                LIMIT 1
            ");

      $c->execute([$id]);

      $claim = $c->fetch(PDO::FETCH_ASSOC);


      if (!$claim) {
        throw new Exception('Approved claim not found.');
      }




      if (
        $claim['item_type'] === 'lost'
        &&
        staffOwnsItemReport($claim, $staff)
      ) {

        throw new Exception(
          'You cannot issue a lost item that you personally reported. Another authorised staff member or an administrator must issue it.'
        );
      }


      $verificationNotes =
        trim($_POST['verification_notes'] ?? '');


      if ($verificationNotes === '') {
        throw new Exception(
          'Please provide final verification notes.'
        );
      }


      $pdo->beginTransaction();


      /*
            | Record official handover.
            */

      $issue = $pdo->prepare("
                INSERT INTO issued_items (
                    item_id,
                    claim_id,
                    issued_to_user_id,
                    issued_to_name,
                    issued_by_staff_id,
                    issued_by_name,
                    issuer_role,
                    verification_notes
                )
                VALUES (
                    ?,
                    ?,
                    NULL,
                    ?,
                    ?,
                    ?,
                    'staff',
                    ?
                )
            ");

      $issue->execute([
        $claim['item_id'],
        $id,
        $claim['claimant_name'],
        $staff['id'],
        $staff['name'],
        $verificationNotes
      ]);


      /*
            | Mark claim as issued.
            */

      $pdo->prepare("
                UPDATE claims
                SET
                    status = 'issued',
                    issued_at = NOW()
                WHERE id = ?
            ")->execute([$id]);


      /*
            | Mark item as issued.
            */

      $pdo->prepare("
                UPDATE items
                SET status = 'issued'
                WHERE id = ?
            ")->execute([
        $claim['item_id']
      ]);


      $pdo->commit();


      logActivity(
        $pdo,
        'staff',
        (int)$staff['id'],
        $staff['name'],
        'Issued Item',
        $claim['title']
          . ' issued to '
          . $claim['claimant_name'],
        'item',
        (int)$claim['item_id']
      );


      flash(
        'success',
        'Item issued and recorded successfully.'
      );
    }
  } catch (Throwable $e) {

    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }

    flash(
      'error',
      $e->getMessage()
    );
  }


  redirect(
    appUrl(
      'staff/dashboard.php?section='
        . urlencode($section)
    )
  );
}




$stats = [

  'pending' => $pdo->query("
        SELECT COUNT(*)
        FROM items
        WHERE status = 'pending'
    ")->fetchColumn(),

  'claims' => $pdo->query("
        SELECT COUNT(*)
        FROM claims
        WHERE status = 'approved'
    ")->fetchColumn(),

  'ready' => $pdo->query("
        SELECT COUNT(*)
        FROM claims
        WHERE status = 'approved'
    ")->fetchColumn(),

  'issued' => $pdo->query("
        SELECT COUNT(*)
        FROM items
        WHERE status = 'issued'
    ")->fetchColumn()
];



$filterType =
  trim($_GET['type'] ?? '');

$filterClaim =
  trim($_GET['claim'] ?? '');

$filterCategory =
  trim($_GET['category'] ?? '');




$itemSql = "
    SELECT *
    FROM items
    WHERE 1 = 1
";

$itemParams = [];



$itemSql .= "
    AND NOT (

        user_id = :self_uid

        OR

        (
            reporter_staff_no IS NOT NULL
            AND reporter_staff_no <> ''
            AND reporter_staff_no = :self_staff_no
        )

        OR

        (
            reporter_email IS NOT NULL
            AND reporter_email <> ''
            AND LOWER(reporter_email) = LOWER(:self_email)
        )
    )
";

$itemParams['self_uid'] =
  (int)$staff['id'];

$itemParams['self_staff_no'] =
  $staff['staff_no'] ?? '';

$itemParams['self_email'] =
  $staff['email'] ?? '';



if (
  in_array(
    $filterType,
    ['lost', 'found'],
    true
  )
) {

  $itemSql .= "
        AND item_type = :ftype
    ";

  $itemParams['ftype'] =
    $filterType;
}




if ($filterClaim === 'claimed') {

  $itemSql .= "
        AND status IN ('claimed', 'issued')
    ";
} elseif ($filterClaim === 'not_claimed') {

  $itemSql .= "
        AND status = 'approved'
    ";
}




if ($filterCategory !== '') {

  $itemSql .= "
        AND category = :fcategory
    ";

  $itemParams['fcategory'] =
    $filterCategory;
}


$itemSql .= "
    ORDER BY id DESC
";


$itemStmt =
  $pdo->prepare($itemSql);

$itemStmt->execute($itemParams);

$items =
  $itemStmt->fetchAll(PDO::FETCH_ASSOC);



$claims = $pdo->query("
    SELECT
        c.*,

        i.title,
        i.item_type,
        i.category,

        i.reporter_name,
        i.reporter_phone,
        i.reporter_email,
        i.reporter_staff_no,

        i.user_id AS item_reporter_user_id

    FROM claims c

    JOIN items i
        ON i.id = c.item_id

    ORDER BY c.id DESC
")->fetchAll(PDO::FETCH_ASSOC);



$issued = $pdo->query("
    SELECT
        x.*,
        i.title

    FROM issued_items x

    JOIN items i
        ON i.id = x.item_id

    ORDER BY x.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>

<html>

<head>

  <meta charset="utf-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1">

  <title>
    Staff Panel
  </title>

  <link
    rel="stylesheet"
    href="<?php echo appUrl('assets/css/style.css'); ?>">

</head>


<body class="portal-body">


  <div class="portal-layout">




    <aside class="sidebar">


      <a
        class="sidebar-brand"
        href="<?php echo appUrl(
                'staff/dashboard.php?section=overview'
              ); ?>">

        <span class="brand-mark">
          C
        </span>

        <span>

          <strong>
            CUEA
          </strong>

          <small>
            Staff Desk
          </small>

        </span>

      </a>


      <nav>


        <a
          class="<?php echo
                  $section === 'overview'
                    ? 'active'
                    : '';
                  ?>"
          href="?section=overview">
          Dashboard
        </a>


        <a
          class="<?php echo
                  $section === 'items'
                    ? 'active'
                    : '';
                  ?>"
          href="?section=items">
          Item Reports
        </a>


        <a
          href="<?php echo appUrl(
                  'report-item.php?type=lost'
                ); ?>">
          Report Lost Item
        </a>


        <a
          href="<?php echo appUrl(
                  'report-item.php?type=found'
                ); ?>">
          Report Found Item
        </a>


        <a
          class="<?php echo
                  $section === 'claims'
                    ? 'active'
                    : '';
                  ?>"
          href="?section=claims">
          Claim Requests
        </a>


        <?php if (canStaffIssue()): ?>

          <a
            class="<?php echo
                    in_array(
                      $section,
                      ['issue', 'issued'],
                      true
                    )
                      ? 'active'
                      : '';
                    ?>"
            href="?section=issue">
            Issue &amp; Issued Items
          </a>

        <?php endif; ?>


        <a
          href="<?php echo appUrl(
                  'auth/logout.php'
                ); ?>">
          Logout
        </a>


      </nav>


    </aside>




    <main class="portal-main">


      <div class="portal-top">

        <div>

          <p class="eyebrow">
            STAFF PANEL
          </p>

          <h1>
            <?php echo e($staff['name']); ?>
          </h1>

          <p class="small">

            Staff No:

            <?php echo e(
              $staff['staff_no']
                ?: 'Not set'
            ); ?>

            · Reporting: Enabled by default

            · Issuing:

            <?php echo
            canStaffIssue()
              ? 'Enabled'
              : 'Disabled';
            ?>

          </p>

        </div>

      </div>


      <?php if ($m = flash('success')): ?>

        <div class="alert success">

          <?php echo e($m); ?>

        </div>

      <?php endif; ?>


      <?php if ($m = flash('error')): ?>

        <div class="alert error">

          <?php echo e($m); ?>

        </div>

      <?php endif; ?>



      <?php if ($section === 'overview'): ?>


        <div class="stats">


          <div class="card kpi">

            <strong>
              <?php echo $stats['pending']; ?>
            </strong>

            <span>
              User Reports Pending
            </span>

          </div>


          <div class="card kpi">

            <strong>
              <?php echo $stats['claims']; ?>
            </strong>

            <span>
              Claims Ready to Issue
            </span>

          </div>


          <div class="card kpi">

            <strong>
              <?php echo $stats['ready']; ?>
            </strong>

            <span>
              Ready to Issue
            </span>

          </div>


          <div class="card kpi">

            <strong>
              <?php echo $stats['issued']; ?>
            </strong>

            <span>
              Issued Items
            </span>

          </div>


        </div>


        <div class="quick-actions">


          <a
            class="quick-card"
            href="<?php echo appUrl(
                    'report-item.php?type=lost'
                  ); ?>">

            <strong>
              Report Lost Item
            </strong>

            <span>
              Publishes immediately
            </span>

          </a>


          <a
            class="quick-card"
            href="<?php echo appUrl(
                    'report-item.php?type=found'
                  ); ?>">

            <strong>
              Report Found Item
            </strong>

            <span>
              Publishes immediately
            </span>

          </a>


          <a
            class="quick-card"
            href="?section=claims">

            <strong>
              Claims
            </strong>

            <span>
              Review onsite claim records
            </span>

          </a>


          <?php if (canStaffIssue()): ?>

            <a
              class="quick-card"
              href="?section=issue">

              <strong>
                Issue Item
              </strong>

              <span>
                Final verified handover
              </span>

            </a>

          <?php endif; ?>


        </div>


      <?php endif; ?>




      <?php if ($section === 'items'): ?>


        <div class="card table-wrap">


          <h2>
            Item Reports
          </h2>


          <p class="small">

            Reports created by you are not displayed here.

            Staff reports publish automatically and do not
            require self-approval.

          </p>


          <form
            class="filters"
            method="GET">


            <input
              type="hidden"
              name="section"
              value="items">


            <select name="type">

              <option value="">
                Lost &amp; Found
              </option>

              <option
                value="lost"
                <?php echo
                $filterType === 'lost'
                  ? 'selected'
                  : '';
                ?>>
                Lost
              </option>

              <option
                value="found"
                <?php echo
                $filterType === 'found'
                  ? 'selected'
                  : '';
                ?>>
                Found
              </option>

            </select>


            <select name="claim">

              <option value="">
                Claimed &amp; Not Claimed
              </option>

              <option
                value="claimed"
                <?php echo
                $filterClaim === 'claimed'
                  ? 'selected'
                  : '';
                ?>>
                Claimed
              </option>

              <option
                value="not_claimed"
                <?php echo
                $filterClaim === 'not_claimed'
                  ? 'selected'
                  : '';
                ?>>
                Not Claimed
              </option>

            </select>


            <select name="category">

              <option value="">
                All categories
              </option>

              <?php foreach (itemCategories() as $cat): ?>

                <option
                  value="<?php echo e($cat); ?>"
                  <?php echo
                  $filterCategory === $cat
                    ? 'selected'
                    : '';
                  ?>>
                  <?php echo e($cat); ?>
                </option>

              <?php endforeach; ?>

            </select>


            <button class="btn btn-sm">
              Filter
            </button>


            <a
              class="btn outline btn-sm"
              href="?section=items">
              Clear
            </a>


          </form>


          <table>


            <thead>

              <tr>

                <th>Item</th>

                <th>Type</th>

                <th>Reporter</th>

                <th>Status</th>

                <th>Actions</th>

              </tr>

            </thead>


            <tbody>


              <?php foreach ($items as $i): ?>


                <tr>


                  <td>

                    <strong>
                      <?php echo e($i['title']); ?>
                    </strong>

                    <br>

                    <span class="small">

                      <?php echo e(
                        $i['category']
                          . ' · '
                          . $i['location']
                      ); ?>

                    </span>

                  </td>


                  <td>

                    <?php echo e(
                      itemTypeLabel(
                        $i['item_type']
                      )
                    ); ?>

                  </td>


                  <td>

                    <?php echo e(
                      $i['reporter_name']
                    ); ?>

                  </td>


                  <td>

                    <span
                      class="<?php echo
                              badgeClass(
                                $i['status']
                              );
                              ?>">

                      <?php echo e(
                        statusLabel(
                          $i['status']
                        )
                      ); ?>

                    </span>

                  </td>


                  <td>


                    <a
                      class="btn outline btn-sm"
                      href="<?php echo appUrl(
                              'item.php?id='
                                . (int)$i['id']
                            ); ?>">
                      More Details
                    </a>


                    <?php if ($i['status'] === 'approved'): ?>

                      <a
                        class="btn btn-sm"
                        href="<?php echo appUrl(
                                'claim-intake.php?item_id='
                                  . (int)$i['id']
                              ); ?>">
                        Capture Claim
                      </a>

                    <?php endif; ?>


                    <?php if ($i['status'] === 'pending'): ?>

                      <form
                        method="POST"
                        class="inline-form"
                        style="margin-top:6px">

                        <input
                          type="hidden"
                          name="id"
                          value="<?php echo
                                  (int)$i['id'];
                                  ?>">


                        <button
                          class="btn success btn-sm"
                          name="action"
                          value="approve_item">
                          Approve User Found Report
                        </button>


                        <button
                          class="btn danger btn-sm"
                          name="action"
                          value="reject_item">
                          Reject
                        </button>


                      </form>

                    <?php endif; ?>


                  </td>


                </tr>


              <?php endforeach; ?>


              <?php if (!$items): ?>

                <tr>

                  <td colspan="5">

                    No item reports available.

                  </td>

                </tr>

              <?php endif; ?>


            </tbody>


          </table>


        </div>


      <?php endif; ?>


      <?php if ($section === 'claims'): ?>


        <div class="card table-wrap">


          <h2>
            Onsite Claim Records
          </h2>


          <div class="alert error">

            <strong>
              Required documents:
            </strong>

            Valid ID/Passport and valid Police Abstract.

          </div>


          <table>


            <thead>

              <tr>

                <th>Item</th>

                <th>Claimant</th>

                <th>Evidence</th>

                <th>Status</th>

                <th>Action</th>

              </tr>

            </thead>


            <tbody>


              <?php foreach ($claims as $c): ?>


                <tr>


                  <td>

                    <strong>
                      <?php echo e($c['title']); ?>
                    </strong>

                    <br>

                    <a
                      class="small"
                      href="<?php echo appUrl(
                              'item.php?id='
                                . (int)$c['item_id']
                            ); ?>">
                      More Details
                    </a>

                  </td>


                  <td>

                    <?php echo e(
                      $c['claimant_name']
                    ); ?>

                    <br>

                    <span class="small">

                      ID/Passport:

                      <?php echo e(
                        $c['id_passport_no']
                          ?? '—'
                      ); ?>

                      ·

                      <?php echo e(
                        $c['claimant_phone']
                      ); ?>

                    </span>

                  </td>


                  <td>

                    <?php if (
                      !empty($c['police_abstract_file'])
                    ): ?>

                      <a
                        class="btn outline btn-sm"
                        target="_blank"
                        href="<?php echo appUrl(
                                'uploads/'
                                  . $c['police_abstract_file']
                              ); ?>">
                        Police Abstract
                      </a>

                    <?php else: ?>

                      Missing

                    <?php endif; ?>

                  </td>


                  <td>

                    <span
                      class="<?php echo
                              badgeClass(
                                $c['status']
                              );
                              ?>">

                      <?php echo e(
                        statusLabel(
                          $c['status']
                        )
                      ); ?>

                    </span>

                  </td>


                  <td>

                    <span class="small">
                      Automatic after onsite capture
                    </span>

                  </td>


                </tr>


              <?php endforeach; ?>


            </tbody>


          </table>


        </div>


      <?php endif; ?>




      <?php if (
        in_array(
          $section,
          ['issue', 'issued'],
          true
        )
        &&
        canStaffIssue()
      ): ?>


        <div class="card table-wrap">


          <h2>
            Pending Issues
          </h2>


          <p class="small">

            Verified claims ready for handover.

            A staff member cannot issue a lost item
            that they personally reported.

          </p>


          <table>


            <thead>

              <tr>

                <th>Item</th>

                <th>Claimant</th>

                <th>Issue</th>

              </tr>

            </thead>


            <tbody>


              <?php

              $pendingIssueCount = 0;

              foreach ($claims as $c):


                if ($c['status'] !== 'approved') {
                  continue;
                }




                if (
                  $c['item_type'] === 'lost'
                  &&
                  staffOwnsItemReport(
                    $c,
                    $staff
                  )
                ) {
                  continue;
                }


                $pendingIssueCount++;

              ?>


                <tr>


                  <td>

                    <?php echo e(
                      $c['title']
                    ); ?>

                    <br>

                    <a
                      class="small"
                      href="<?php echo appUrl(
                              'item.php?id='
                                . (int)$c['item_id']
                            ); ?>">
                      More Details
                    </a>

                  </td>


                  <td>

                    <?php echo e(
                      $c['claimant_name']
                    ); ?>

                    <br>

                    <span class="small">

                      <?php echo e(
                        $c['claimant_phone']
                      ); ?>

                    </span>

                  </td>


                  <td>


                    <form method="POST">


                      <input
                        type="hidden"
                        name="id"
                        value="<?php echo
                                (int)$c['id'];
                                ?>">


                      <input
                        name="verification_notes"
                        placeholder="Final verification notes"
                        required>


                      <button
                        class="btn"
                        name="action"
                        value="issue_item">
                        Issue Item
                      </button>


                    </form>


                  </td>


                </tr>


              <?php endforeach; ?>


              <?php if (!$pendingIssueCount): ?>


                <tr>

                  <td colspan="3">
                    No pending issues.
                  </td>

                </tr>


              <?php endif; ?>


            </tbody>


          </table>


        </div>



        <div class="card table-wrap section-gap">


          <h2>
            Issued Items
          </h2>


          <p class="small">
            Completed handovers are listed below.
          </p>


          <table>


            <thead>

              <tr>

                <th>Item</th>

                <th>Issued to</th>

                <th>Issued by</th>

                <th>Verification</th>

                <th>Date</th>

              </tr>

            </thead>


            <tbody>


              <?php foreach ($issued as $x): ?>


                <tr>


                  <td>
                    <?php echo e(
                      $x['title']
                    ); ?>
                  </td>


                  <td>
                    <?php echo e(
                      $x['issued_to_name']
                    ); ?>
                  </td>


                  <td>
                    <?php echo e(
                      $x['issued_by_name']
                    ); ?>
                  </td>


                  <td>
                    <?php echo e(
                      $x['verification_notes']
                    ); ?>
                  </td>


                  <td>
                    <?php echo e(
                      $x['issued_at']
                    ); ?>
                  </td>


                </tr>


              <?php endforeach; ?>


              <?php if (!$issued): ?>


                <tr>

                  <td colspan="5">
                    No issued items yet.
                  </td>

                </tr>


              <?php endif; ?>


            </tbody>


          </table>


        </div>


      <?php endif; ?>


    </main>


  </div>


</body>

</html>