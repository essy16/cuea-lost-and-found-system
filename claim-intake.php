<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$isAdmin = isAdminLoggedIn();
$user = currentUser();
$isStaff = $user && $user['role'] === 'staff';
if (!$isAdmin && !$isStaff) {
    flash('error', 'Claimant details are captured onsite by authorised staff or an administrator.');
    redirect(appUrl('index.php'));
}
$itemId = (int)($_GET['item_id'] ?? $_POST['item_id'] ?? 0);
$s = $pdo->prepare("SELECT * FROM items WHERE id=? AND status='approved'");
$s->execute([$itemId]);
$item = $s->fetch();
if (!$item) die('Item not found or no longer available for a new claim.');
if (isPost()) {
    try {
        $claimantType = trim($_POST['claimant_type'] ?? '');
        $name = trim($_POST['claimant_name'] ?? '');
        $idNo = trim($_POST['id_passport_no'] ?? '');
        $reg = trim($_POST['registration_no'] ?? '');
        $staffNo = trim($_POST['staff_no'] ?? '');
        $phone = trim($_POST['claimant_phone'] ?? '');
        $email = trim($_POST['claimant_email'] ?? '');
        $address = trim($_POST['physical_address'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        if (!in_array($claimantType, ['student', 'staff', 'other'], true)) throw new Exception('Select the claimant type.');
        if (!$name || !$idNo || !$phone) throw new Exception('Full name, ID/Passport number and phone number are required.');
        if ($claimantType === 'student' && !$reg) throw new Exception('Registration number is required for a student claimant.');
        if ($claimantType === 'staff' && !$staffNo) throw new Exception('Staff number is required for a staff claimant.');
        if (empty($_FILES['police_abstract']['name'])) throw new Exception('A valid Police Abstract file is required.');
        $evidence = uploadEvidence($_FILES['police_abstract']);
        $capturedByType = $isAdmin ? 'admin' : 'staff';
        $capturedById = $isAdmin ? (int)$_SESSION['admin_id'] : (int)$user['id'];
        $capturedByName = $isAdmin ? ($_SESSION['admin_name'] ?? 'Admin') : $user['name'];
        $pdo->beginTransaction();
        $q = $pdo->prepare("INSERT INTO claims(item_id,user_id,claimant_type,claimant_name,id_passport_no,registration_no,staff_no,claimant_phone,claimant_email,physical_address,gender,police_abstract_file,message,status,captured_by_type,captured_by_id,captured_by_name,verified_onsite,reviewed_by_admin_id,reviewed_by_staff_id,reviewed_at) VALUES(:item,NULL,:ctype,:name,:idno,:reg,:staff,:phone,:email,:address,:gender,:file,'Onsite claim captured with required identity and Police Abstract evidence.','approved',:btype,:bid,:bname,1,:admin_review,:staff_review,NOW())");
        $q->execute(['item' => $itemId, 'ctype' => $claimantType, 'name' => $name, 'idno' => $idNo, 'reg' => $reg ?: null, 'staff' => $staffNo ?: null, 'phone' => $phone, 'email' => $email ?: null, 'address' => $address ?: null, 'gender' => $gender ?: null, 'file' => $evidence, 'btype' => $capturedByType, 'bid' => $capturedById, 'bname' => $capturedByName, 'admin_review' => $isAdmin ? $capturedById : null, 'staff_review' => $isStaff ? $capturedById : null]);
        $claimId = (int)$pdo->lastInsertId();
        $pdo->prepare("UPDATE items SET status='claimed' WHERE id=?")->execute([$itemId]);
        $pdo->prepare("UPDATE claims SET status='rejected',reviewed_at=NOW() WHERE item_id=? AND id<>? AND status='pending'")->execute([$itemId, $claimId]);
        $pdo->commit();
        logActivity($pdo, $capturedByType, $capturedById, $capturedByName, 'Captured and approved onsite claim', 'Claim #' . $claimId . ' for Item #' . $itemId . ' — ' . $name, 'claim', $claimId);
        flash('success', 'Claim captured and approved automatically. The item is now ready for issuing.');
        redirect($isAdmin ? appUrl('admin/dashboard.php?section=issue') : appUrl('staff/dashboard.php?section=issue'));
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        flash('error', $e->getMessage());
        redirect(appUrl('claim-intake.php?item_id=' . $itemId));
    }
}
require_once __DIR__ . '/includes/header.php'; ?>
<div class="card form-card">
    <div class="page-heading">
        <div>
            <h1>Capture Onsite Claim</h1>
            <p>Item details are retrieved automatically. Capture the claimant's verified personal information and attach the Police Abstract.</p>
        </div>
    </div><?php if ($m = flash('error')): ?><div class="alert error"><?php echo e($m); ?></div><?php endif; ?>
    <div class="card highlight"><strong>Item #<?php echo $itemId; ?>:</strong> <?php echo e($item['title']); ?> · <?php echo e($item['category']); ?> · <?php echo e($item['location']); ?> · <?php echo e($item['date_reported']); ?><br><span class="small"><?php echo e($item['description'] ?: 'No description provided.'); ?></span></div>
    <div class="alert error"><strong>Required documents:</strong> A valid ID/Passport and a valid Police Abstract MUST be physically verified before the claim is captured.</div>
    <form method="POST" enctype="multipart/form-data"><input type="hidden" name="item_id" value="<?php echo $itemId; ?>"><label>Claimant type</label><select name="claimant_type" id="claimantType" required>
            <option value="">Select type</option>
            <option value="student">Student</option>
            <option value="staff">Staff</option>
            <option value="other">Other</option>
        </select>
        <div class="form-grid">
            <div><label>Full name</label><input name="claimant_name" required></div>
            <div><label>ID / Passport number</label><input name="id_passport_no" required></div>
        </div>
        <div class="form-grid">
            <div id="regBox"><label>Registration number</label><input name="registration_no"></div>
            <div id="staffBox"><label>Staff number</label><input name="staff_no"></div>
        </div>
        <div class="form-grid">
            <div><label>Phone number</label><input name="claimant_phone" required></div>
            <div><label>Email <span class="small">(optional)</span></label><input type="email" name="claimant_email"></div>
        </div>
        <div class="form-grid">
            <div><label>Gender <span class="small">(optional)</span></label><select name="gender">
                    <option value="">Select</option>
                    <option>Female</option>
                    <option>Male</option>
                    <option>Other</option>
                    <option>Prefer not to say</option>
                </select></div>
            <div><label>Physical address <span class="small">(optional)</span></label><input name="physical_address"></div>
        </div>
        <label>Police Abstract file</label><input type="file" name="police_abstract" accept="application/pdf,image/jpeg,image/png,image/webp" required>
        <p class="small">Accepted: PDF, JPG, PNG or WEBP. Maximum 5MB.</p><button class="btn">Capture Claim</button>
    </form>
</div>
<script>
    const ct = document.getElementById('claimantType'),
        rb = document.getElementById('regBox'),
        sb = document.getElementById('staffBox');

    function show() {
        rb.style.display = ct.value === 'student' ? 'block' : 'none';
        sb.style.display = ct.value === 'staff' ? 'block' : 'none';
        rb.querySelector('input').required = ct.value === 'student';
        sb.querySelector('input').required = ct.value === 'staff'
    }
    ct.addEventListener('change', show);
    show();
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>