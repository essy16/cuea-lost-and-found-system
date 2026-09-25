<?php
require_once __DIR__ . '/../config/mail.php';
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}
function appUrl(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}
function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }
    if (isset($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}
function isPost(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}
function currentYear(): string
{
    return date('Y');
}
function isUserLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}
function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}
function currentUser(): ?array
{
    if (!isUserLoggedIn()) return null;
    global $pdo;
    try {
        $s = $pdo->prepare("SELECT id,name,email,reg_no,staff_no,phone,gender,physical_address,id_passport_no,role,can_issue_items FROM users WHERE id=? LIMIT 1");
        $s->execute([(int)$_SESSION['user_id']]);
        if ($u = $s->fetch()) return $u;
    } catch (Throwable $e) {
    }
    return ['id' => (int)$_SESSION['user_id'], 'name' => $_SESSION['user_name'] ?? '', 'email' => $_SESSION['user_email'] ?? '', 'role' => $_SESSION['user_role'] ?? 'student', 'reg_no' => $_SESSION['user_reg_no'] ?? '', 'staff_no' => $_SESSION['user_staff_no'] ?? '', 'phone' => $_SESSION['user_phone'] ?? '', 'gender' => $_SESSION['user_gender'] ?? '', 'physical_address' => $_SESSION['user_physical_address'] ?? '', 'id_passport_no' => $_SESSION['user_id_passport_no'] ?? '', 'can_issue_items' => (int)($_SESSION['user_can_issue_items'] ?? 0)];
}
function requireUser(): void
{
    if (!isUserLoggedIn()) {
        flash('error', 'Please login to continue.');
        $return = $_SERVER['REQUEST_URI'] ?? '';
        redirect(appUrl('auth/login.php' . ($return ? '?return=' . urlencode($return) : '')));
    }
}
function safeReturnPath(?string $return): ?string
{
    $return = trim((string)$return);
    if ($return === '') return null;
    $parts = parse_url($return);
    if ($parts === false || isset($parts['scheme']) || isset($parts['host'])) return null;
    $path = $parts['path'] ?? '';
    if ($path === '' || str_contains($path, '..')) return null;
    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    return ltrim($path, '/') . $query;
}
function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        flash('error', 'Please login as admin.');
        redirect(appUrl('admin/login.php'));
    }
}
function requireStaff(): void
{
    if (!isUserLoggedIn() || ($_SESSION['user_role'] ?? '') !== 'staff') {
        flash('error', 'Please login with a staff account.');
        redirect(appUrl('auth/login.php'));
    }
}
function canStaffReport(): bool
{
    return isUserLoggedIn() && ($_SESSION['user_role'] ?? '') === 'staff';
}
function canStaffIssue(): bool
{
    if (!canStaffReport()) return false;
    global $pdo;
    try {
        $s = $pdo->prepare("SELECT can_issue_items FROM users WHERE id=? AND role='staff'");
        $s->execute([(int)$_SESSION['user_id']]);
        return (bool)$s->fetchColumn();
    } catch (Throwable $e) {
        return !empty($_SESSION['user_can_issue_items']);
    }
}
function requireStaffReportPermission(): void
{
    requireStaff();
}
function requireStaffIssuePermission(): void
{
    requireStaff();
    if (!canStaffIssue()) {
        flash('error', 'Your staff account is not permitted to issue items.');
        redirect(appUrl('staff/dashboard.php'));
    }
}
function uploadImage(array $file): ?string
{
    if (empty($file['name'])) return null;
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = (!empty($file['tmp_name']) && is_file($file['tmp_name'])) ? mime_content_type($file['tmp_name']) : ($file['type'] ?? '');
    if (!isset($allowed[$mime])) throw new Exception('Only JPG, PNG, and WEBP images are allowed.');
    if (($file['size'] ?? 0) > 3 * 1024 * 1024) throw new Exception('Image size must be 3MB or less.');
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
    $name = uniqid('item_', true) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) throw new Exception('Failed to upload image.');
    return $name;
}
function uploadEvidence(array $file): ?string
{
    if (empty($file['name'])) return null;
    $allowed = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = (!empty($file['tmp_name']) && is_file($file['tmp_name'])) ? mime_content_type($file['tmp_name']) : ($file['type'] ?? '');
    if (!isset($allowed[$mime])) throw new Exception('Police abstract must be PDF, JPG, PNG or WEBP.');
    if (($file['size'] ?? 0) > 5 * 1024 * 1024) throw new Exception('Police abstract file must be 5MB or less.');
    $dir = UPLOAD_DIR . 'evidence/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $name = uniqid('evidence_', true) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) throw new Exception('Failed to upload police abstract.');
    return 'evidence/' . $name;
}
function badgeClass(string $status): string
{
    return match ($status) {
        'pending' => 'badge warning',
        'approved' => 'badge success',
        'claimed' => 'badge primary',
        'issued' => 'badge info',
        'rejected' => 'badge danger',
        default => 'badge'
    };
}
function itemTypeLabel(string $type): string
{
    return $type === 'found' ? 'Found' : 'Lost';
}
function itemCategories(): array
{
    return ['Electronics', 'Identification Documents', 'Books', 'Bags', 'Clothing', 'Keys', 'Wallets/Purses', 'Accessories', 'Documents', 'Phone', 'Laptop', 'Other'];
}
function isElectronicCategory(string $category): bool
{
    return in_array($category, ['Electronics', 'Phone', 'Laptop'], true);
}
function campusLocations(): array
{
    return ['Main Gate', 'Library', 'Hostels', 'Cafeteria', 'Chapel', 'Lecture Hall', 'ICT Lab', 'Administration Block', 'Parking Area', 'Sports Ground', 'Other'];
}
function statusLabel(string $status): string
{
    return match ($status) {
        'claimed' => 'Claimed',
        'issued' => 'Issued',
        'pending' => 'Pending Review',
        'approved' => 'Not Claimed',
        'rejected' => 'Rejected',
        default => ucfirst(str_replace('_', ' ', $status))
    };
}
function claimState(string $status): string
{
    return in_array($status, ['claimed', 'issued'], true) ? 'claimed' : 'not_claimed';
}
function yesterday(): string
{
    return date('Y-m-d', strtotime('-1 day'));
}
function sendNotification(PDO $pdo, string $email, string $subject, string $message): bool
{
    if (!$email) return false;
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\nReply-To: " . MAIL_FROM . "\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    $sent = MAIL_ENABLED ? mail($email, $subject, $message, $headers) : false;
    try {
        $s = $pdo->prepare("INSERT INTO notifications_log(recipient_email,subject_line,message_body,sent_status) VALUES(:e,:s,:m,:st)");
        $s->execute(['e' => $email, 's' => $subject, 'm' => $message, 'st' => $sent ? 'sent' : 'queued']);
    } catch (Throwable $e) {
    }
    return $sent;
}
function logActivity(PDO $pdo, string $actorType, ?int $actorId, string $actorName, string $action, string $details = '', ?string $entityType = null, ?int $entityId = null): void
{
    try {
        // Support both the original activity_logs schema and the upgraded audit-trail schema.
        $cols = $pdo->query("SHOW COLUMNS FROM activity_logs")->fetchAll(PDO::FETCH_COLUMN);
        $data = [];
        if (in_array('actor_id', $cols, true)) $data['actor_id'] = $actorId;
        if (in_array('actor_role', $cols, true)) $data['actor_role'] = $actorType;
        if (in_array('actor_type', $cols, true)) $data['actor_type'] = $actorType;
        if (in_array('actor_name', $cols, true)) $data['actor_name'] = $actorName;
        if (in_array('action', $cols, true)) $data['action'] = $action;
        if (in_array('entity_type', $cols, true)) $data['entity_type'] = $entityType;
        if (in_array('entity_id', $cols, true)) $data['entity_id'] = $entityId;
        if (in_array('description', $cols, true)) $data['description'] = $details;
        if (in_array('details', $cols, true)) $data['details'] = $details;
        if (in_array('ip_address', $cols, true)) $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
        if (!$data) return;
        $names = array_keys($data);
        $sql = "INSERT INTO activity_logs (" . implode(',', $names) . ") VALUES (" . implode(',', array_fill(0, count($names), '?')) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
    } catch (Throwable $e) {
        // Audit logging must never stop the main transaction, but errors are still sent to PHP's error log.
        error_log('Activity log error: ' . $e->getMessage());
    }
}
