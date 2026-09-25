<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = '127.0.0.1';
$port = '8889';
$dbname = 'cuea_lost_found';
$username = 'root';
$password = 'root';
if (!defined('APP_NAME')) define('APP_NAME', 'CUEA Lost & Found');
if (!defined('APP_URL')) define('APP_URL', 'http://localhost:8000');
if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', __DIR__ . '/../uploads/');
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
