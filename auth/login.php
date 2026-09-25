<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/functions.php';
$return=safeReturnPath($_POST['return']??$_GET['return']??null);
if(isPost()){
    $email=trim($_POST['email']??'');
    $password=$_POST['password']??'';

    $adminStmt=$pdo->prepare('SELECT * FROM admins WHERE email=:email LIMIT 1');
    $adminStmt->execute(['email'=>$email]);
    $admin=$adminStmt->fetch();
    if($admin&&password_verify($password,$admin['password'])){
        $_SESSION['admin_id']=$admin['id'];
        $_SESSION['admin_name']=$admin['name'];
        logActivity($pdo,'admin',(int)$admin['id'],$admin['name'],'Logged in','Administrator login successful.');
        redirect(appUrl('admin/dashboard.php'));
    }

    $s=$pdo->prepare('SELECT * FROM users WHERE email=:email LIMIT 1');
    $s->execute(['email'=>$email]);
    $u=$s->fetch();
    if($u&&password_verify($password,$u['password'])){
        foreach(['id'=>'user_id','name'=>'user_name','email'=>'user_email','role'=>'user_role','reg_no'=>'user_reg_no','staff_no'=>'user_staff_no','phone'=>'user_phone','gender'=>'user_gender','physical_address'=>'user_physical_address','id_passport_no'=>'user_id_passport_no','can_report_items'=>'user_can_report_items','can_issue_items'=>'user_can_issue_items'] as $db=>$sess){$_SESSION[$sess]=$u[$db]??0;}
        logActivity($pdo,$u['role'],(int)$u['id'],$u['name'],'Logged in',ucfirst($u['role']).' login successful.');
        if($return && $u['role']!=='staff') redirect(appUrl($return));
        redirect($u['role']==='staff'?appUrl('staff/dashboard.php'):appUrl('dashboard.php'));
    }
    flash('error','Invalid login credentials.');
    redirect(appUrl('auth/login.php'.($return?'?return='.urlencode('/'.$return):'')));
}
require_once __DIR__.'/../includes/header.php';?>
<div class="card auth-shell"><h2>Login</h2><p class="small">Students, visitors, staff and administrators can sign in here.</p><?php if($m=flash('success')):?><div class="alert success"><?php echo e($m);?></div><?php endif;?><?php if($m=flash('error')):?><div class="alert error"><?php echo e($m);?></div><?php endif;?>
<form method="POST" data-validate><?php if($return):?><input type="hidden" name="return" value="<?php echo e('/'.$return);?>"><?php endif;?><label>Email</label><input type="email" name="email" required><label>Password</label><input type="password" name="password" required><button class="btn">Login</button></form>
<div class="card" style="margin-top:16px;padding:14px;background:#f7f7f7"><strong>Administrator?</strong><p class="small">Administrators may use the dedicated administrator login page.</p><a class="btn btn-sm" href="<?php echo appUrl('admin/login.php');?>">Admin Login</a></div><p class="small">Staff accounts and issuing permissions are controlled by the administrator.</p></div>
<?php require_once __DIR__.'/../includes/footer.php';?>