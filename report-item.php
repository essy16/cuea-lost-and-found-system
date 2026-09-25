<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$user = currentUser();
$admin = isAdminLoggedIn();

if (!$user && !$admin) {
   flash('error', 'Please login to report an item.');
   redirect(appUrl('auth/login.php'));
}

$isStaff = $user && ($user['role'] ?? '') === 'staff';

$type = $_GET['type'] ?? 'found';

if (!in_array($type, ['lost', 'found'], true)) {
   $type = 'found';
}


$lostId = (int)($_GET['lost_id'] ?? 0);

$linkedLost = null;

if ($lostId > 0) {
   $stmt = $pdo->prepare("
        SELECT *
        FROM items
        WHERE id = ?
          AND item_type = 'lost'
          AND status IN ('approved', 'claimed')
        LIMIT 1
    ");

   $stmt->execute([$lostId]);
   $linkedLost = $stmt->fetch(PDO::FETCH_ASSOC);
}


if ($admin) {

   $actorId = (int)($_SESSION['admin_id'] ?? 0);
   $actorType = 'admin';
   $actorName = $_SESSION['admin_name'] ?? 'Administrator';

   $reporter = [
      'name' => $actorName,
      'email' => '',
      'phone' => '',
      'reg_no' => null,
      'staff_no' => null
   ];
} else {

   $actorId = (int)$user['id'];
   $actorType = $user['role'] ?? 'student';
   $actorName = $user['name'] ?? 'User';

   $reporter = [
      'name' => $user['name'] ?? '',
      'email' => $user['email'] ?? '',
      'phone' => $user['phone'] ?? '',
      'reg_no' => $user['reg_no'] ?? null,


      'staff_no' => ($user['role'] ?? '') === 'staff'
         ? ($user['staff_no'] ?? null)
         : null
   ];
}



if (isPost()) {

   try {

      $type = $_POST['item_type'] ?? $type;

      if (!in_array($type, ['lost', 'found'], true)) {
         throw new Exception('Invalid item type.');
      }


      $date = trim($_POST['date_reported'] ?? '');

      if ($date === '') {
         throw new Exception('Please provide the date the item was lost or found.');
      }

      if ($date >= date('Y-m-d')) {
         throw new Exception('The lost/found date must be before today.');
      }



      $category = trim($_POST['category'] ?? '');
      $title = trim($_POST['title'] ?? '');
      $location = trim($_POST['location'] ?? '');

      if ($category === '' || $title === '' || $location === '') {
         throw new Exception('Item name, category, location and date are required.');
      }



      $description = trim($_POST['description'] ?? '');


      $image = uploadImage($_FILES['image'] ?? []);



      $status = ($type === 'lost' || $admin || $isStaff)
         ? 'approved'
         : 'pending';



      $linked = (int)($_POST['matched_lost_item_id'] ?? 0);

      if ($linked <= 0) {
         $linked = null;
      }



      $brand = null;
      $model = null;
      $color = null;
      $serialNumber = null;

      if (isElectronicCategory($category)) {
         $brand = trim($_POST['brand'] ?? '');
         $model = trim($_POST['model'] ?? '');
         $color = trim($_POST['color'] ?? '');
         $serialNumber = trim($_POST['serial_number'] ?? '');
      }



      $stmt = $pdo->prepare("
            INSERT INTO items (
                user_id,
                item_type,
                category,
                title,
                description,
                location,
                date_reported,
                reporter_name,
                reporter_email,
                reporter_phone,
                reporter_reg_no,
                reporter_staff_no,
                image,
                status,
                brand,
                model,
                color,
                serial_number,
                matched_lost_item_id
            )
            VALUES (
                :uid,
                :type,
                :category,
                :title,
                :description,
                :location,
                :date,
                :rname,
                :remail,
                :rphone,
                :rreg,
                :rstaff,
                :image,
                :status,
                :brand,
                :model,
                :color,
                :serial,
                :linked
            )
        ");

      $stmt->execute([
         'uid' => $admin ? null : $user['id'],
         'type' => $type,
         'category' => $category,
         'title' => $title,
         'description' => $description !== '' ? $description : null,
         'location' => $location,
         'date' => $date,



         'rname' => $reporter['name'] ?? $actorName,
         'remail' => $reporter['email'] ?? '',
         'rphone' => $reporter['phone'] ?? '',
         'rreg' => $reporter['reg_no'] ?? null,
         'rstaff' => $reporter['staff_no'] ?? null,

         'image' => $image,
         'status' => $status,

         'brand' => $brand,
         'model' => $model,
         'color' => $color,
         'serial' => $serialNumber,

         'linked' => $linked
      ]);

      $newId = (int)$pdo->lastInsertId();



      logActivity(
         $pdo,
         $actorType,
         $actorId,
         $actorName,
         'Reported ' . ucfirst($type) . ' Item',
         'Item #' . $newId . ' - ' . $title,
         'item',
         $newId
      );



      if ($status === 'approved') {
         flash('success', 'Item report saved and published successfully.');
      } else {
         flash('success', 'Found-item report submitted for staff review.');
      }



      if ($admin) {

         redirect(appUrl('admin/dashboard.php?section=items'));
      } elseif ($isStaff) {

         redirect(appUrl('staff/dashboard.php?section=items'));
      } else {

         redirect(appUrl('dashboard.php'));
      }
   } catch (Throwable $e) {

      flash('error', $e->getMessage());

      $redirectUrl = 'report-item.php?type=' . urlencode($type);

      if ($lostId > 0) {
         $redirectUrl .= '&lost_id=' . $lostId;
      }

      redirect(appUrl($redirectUrl));
   }
}



require_once __DIR__ . '/includes/header.php';

$prefTitle = $linkedLost['title'] ?? '';
$prefCategory = $linkedLost['category'] ?? '';
?>

<div class="panel-page">

   <div class="page-heading">
      <div>

         <h1>
            <?php echo $type === 'lost'
               ? 'Report Lost Item'
               : 'Report Found Item'; ?>
         </h1>

         <p>
            Reporter details are taken automatically from the logged-in account.
         </p>

      </div>
   </div>

   <?php if ($message = flash('error')): ?>

      <div class="alert error">
         <?php echo e($message); ?>
      </div>

   <?php endif; ?>


   <?php if ($linkedLost): ?>

      <div class="card highlight">

         <strong>Item you are reporting as found:</strong>

         <?php echo e($linkedLost['title']); ?>

         —

         <?php echo e($linkedLost['category']); ?>

         <?php if (!empty($linkedLost['location'])): ?>

            · Lost at <?php echo e($linkedLost['location']); ?>

         <?php endif; ?>

      </div>

   <?php endif; ?>


   <div class="card form-card">

      <form method="POST" enctype="multipart/form-data">

         <input
            type="hidden"
            name="item_type"
            value="<?php echo e($type); ?>">

         <input
            type="hidden"
            name="matched_lost_item_id"
            value="<?php echo (int)$lostId; ?>">


         <?php if ($linkedLost): ?>



            <input
               type="hidden"
               name="title"
               value="<?php echo e($linkedLost['title']); ?>">

            <input
               type="hidden"
               name="category"
               id="category"
               value="<?php echo e($linkedLost['category']); ?>">


         <?php else: ?>


            <div class="form-grid">

               <div>

                  <label>Item name/title</label>

                  <input
                     type="text"
                     name="title"
                     required>

               </div>


               <div>

                  <label>Category</label>

                  <select
                     name="category"
                     id="category"
                     required>

                     <option value="">
                        Select category
                     </option>

                     <?php foreach (itemCategories() as $cat): ?>

                        <option value="<?php echo e($cat); ?>">
                           <?php echo e($cat); ?>
                        </option>

                     <?php endforeach; ?>

                  </select>

               </div>

            </div>

         <?php endif; ?>


         <div class="form-grid">

            <div>

               <label>
                  <?php echo $type === 'lost'
                     ? 'Date lost'
                     : 'Date found'; ?>
               </label>

               <input
                  type="date"
                  name="date_reported"
                  max="<?php echo yesterday(); ?>"
                  required>

               <span class="small">
                  The date must be before today.
               </span>

            </div>


            <div>

               <label>Location</label>

               <select name="location" required>

                  <option value="">
                     Select location
                  </option>

                  <?php foreach (campusLocations() as $loc): ?>

                     <option value="<?php echo e($loc); ?>">
                        <?php echo e($loc); ?>
                     </option>

                  <?php endforeach; ?>

               </select>

            </div>

         </div>


         <label>
            Description
            <span class="small">(optional)</span>
         </label>

         <textarea
            name="description"
            placeholder="Add identifying information if available"></textarea>



         <div
            class="electronics-box"
            id="electronicsBox"
            style="display:none;">

            <h3>Electronic Item Details</h3>

            <div class="form-grid">

               <div>

                  <label>Brand</label>

                  <input
                     type="text"
                     name="brand">

               </div>


               <div>

                  <label>Model</label>

                  <input
                     type="text"
                     name="model">

               </div>


               <div>

                  <label>Colour</label>

                  <input
                     type="text"
                     name="color">

               </div>


               <div>

                  <label>Serial Number / IMEI</label>

                  <input
                     type="text"
                     name="serial_number">

               </div>

            </div>

         </div>


         <label>
            Item image / evidence
            <span class="small">(optional)</span>
         </label>

         <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp">


         <div class="card highlight">

            <strong>Reporter:</strong>

            <?php echo e($reporter['name'] ?? $actorName); ?>


            <?php if (!empty($reporter['phone'])): ?>

               · <?php echo e($reporter['phone']); ?>

            <?php endif; ?>


            <?php if (!empty($reporter['reg_no'])): ?>

               · Registration No:
               <?php echo e($reporter['reg_no']); ?>

            <?php endif; ?>


            <?php if (!empty($reporter['staff_no'])): ?>

               · Staff No:
               <?php echo e($reporter['staff_no']); ?>

            <?php endif; ?>

         </div>


         <button
            type="submit"
            class="btn"
            style="margin-top:16px;">
            Save Report
         </button>

      </form>

   </div>

</div>


<script>
   const categoryField = document.getElementById('category');
   const electronicsBox = document.getElementById('electronicsBox');

   function toggleElectronicDetails() {

      if (!categoryField || !electronicsBox) {
         return;
      }

      const electronicCategories = [
         'Electronics',
         'Phone',
         'Laptop'
      ];

      if (electronicCategories.includes(categoryField.value)) {
         electronicsBox.style.display = 'block';
      } else {
         electronicsBox.style.display = 'none';
      }
   }

   if (categoryField) {



      categoryField.addEventListener(
         'change',
         toggleElectronicDetails
      );



      toggleElectronicDetails();
   }
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>