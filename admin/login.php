<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (isPost()) {
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $_POST['email']]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($_POST['password'], $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        logActivity($pdo,'admin',(int)$admin['id'],$admin['name'],'Logged in','Administrator login successful.');
        redirect('dashboard.php');
    }

    flash('error', 'Invalid admin login credentials.');
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?php echo appUrl('assets/css/style.css'); ?>">
</head>
<body>
<main class="container main-content">
    <div class="card auth-shell">
        <h2>Admin Login</h2>
        <?php if ($msg = flash('error')): ?><div class="alert error"><?php echo e($msg); ?></div><?php endif; ?>
        <form method="POST" data-validate>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Login</button>
        </form>
        <p class="small">Default admin login is included in the SQL seed.</p>
    </div>
</main>
</body>
</html>