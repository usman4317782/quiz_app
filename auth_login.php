<?php
require_once 'bootstrap.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. CSRF Check
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $logger->log('CSRF Failure', null, session_id(), 403, "CSRF token mismatch");
        $_SESSION['error'] = "Security check failed (CSRF). Please try again.";
        header("Location: index.php");
        exit;
    }

    $login_input = trim($_POST['login_input']);
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // 2. Input Validation
    if (empty($login_input) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: index.php");
        exit;
    }

    // 3. Rate Limiting Check
    if (!$rateLimiter->check($ip_address, $login_input)) {
        $logger->log('Rate Limit Exceeded', null, session_id(), 429, "Too many attempts from $ip_address for $login_input");
        $_SESSION['error'] = "Too many login attempts. Please try again later.";
        header("Location: index.php");
        exit;
    }

    try {
        // 4. User Authentication
        $sql = "SELECT id, username, password_hash FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $login_input, 'email' => $login_input]);
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Success
            
            // Prevent Session Fixation
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['created'] = time(); // Reset creation time for rotation
            $_SESSION['last_activity'] = time();

            // Clear rate limit for this IP
            $rateLimiter->clearAttempts($ip_address);
            
            // Log Success
            $logger->log('Login Success', $user['id'], session_id(), 200, "User logged in successfully");

            header("Location: dashboard.php");
            exit;
        } else {
            // Failure
            
            // Record failed attempt
            $rateLimiter->recordAttempt($ip_address, $login_input);
            
            // Log Failure
            // Don't log password!
            $logger->log('Login Failure', null, session_id(), 401, "Invalid credentials for $login_input");
            
            $_SESSION['error'] = "Invalid username/email or password.";
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        $logger->log('Database Error', null, session_id(), 500, $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
