<?php
// Secure Session Settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes

// Check if HTTPS is enabled
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
if ($secure) {
    ini_set('session.cookie_secure', 1);
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/classes/DatabaseSessionHandler.php';
require_once __DIR__ . '/includes/classes/RateLimiter.php';
require_once __DIR__ . '/includes/classes/Logger.php';

// Initialize Custom Session Handler
$handler = new DatabaseSessionHandler($pdo);
session_set_save_handler($handler, true);

session_start();

// Initialize Helpers
$rateLimiter = new RateLimiter($pdo);
$logger = new Logger($pdo);

// CSRF Token Generation (if not exists)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Session Timeout Management (Absolute & Idle)
$timeout_duration = 1800; // 30 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Session expired due to inactivity
    session_unset();
    session_destroy();
    // Log timeout (requires a way to pass this info or log before destroy)
    // Since session is destroyed, we can't easily log "who" it was unless we stored it before.
    // For now, just restart session.
    session_start();
    $_SESSION['error'] = "Session expired due to inactivity.";
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > $timeout_duration * 2) {
    // Force regenerate session ID periodically (Absolute timeout/rotation)
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}
?>
