<?php
// config/app.php
define('APP_NAME', 'IT Asset Tracker');
define('BASE_URL', 'http://localhost/it-at/');
define('ASSET_URL', BASE_URL . 'assets/');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('QR_CODE_PATH', UPLOAD_PATH . 'qrcodes/');

// Timezone
date_default_timezone_set('Asia/Colombo'); // Or user's timezone

// Session configuration
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
session_set_cookie_params(1800);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit;
}

function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        redirect('index.php?controller=auth&action=login');
    }
    // Check timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        redirect('index.php?controller=auth&action=login&timeout=1');
    }
    $_SESSION['last_activity'] = time();
}

function require_role($allowed_roles = []) {
    check_auth();
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        die("Access Denied: You do not have permission to view this page.");
    }
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
