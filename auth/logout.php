<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
if (isUserLoggedIn()) {
    $u = currentUser();
    if ($u) {
        logActivity($pdo, $u['role'], (int)$u['id'], $u['name'], 'Logged out', ucfirst($u['role']).' logout.');
    }
}
$_SESSION = [];
session_destroy();
header('Location: ' . APP_URL . '/index.php');
exit;
