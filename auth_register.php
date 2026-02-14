<?php
require_once 'bootstrap.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "Security check failed (CSRF). Please try again.";
        header("Location: register.php");
        exit;
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side Validation
    $errors = [];

    // Username validation
    if (empty($username) || !preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
        $errors[] = "Invalid username format.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    // Minimum 8 chars, at least 1 uppercase, 1 lowercase, 1 number, 1 special char
    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W]/', $password)) { // \W matches any non-word character (equivalent to [^a-zA-Z0-9_])
        $errors[] = "Password does not meet complexity requirements.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: register.php");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Check if username or email already exists (using FOR UPDATE to lock rows if needed, but unique index handles it mostly)
        // Here we just check. The unique constraint is the real guard.
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $pdo->rollBack();
            $_SESSION['error'] = "Username or Email already exists.";
            header("Location: register.php");
            exit;
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute(['username' => $username, 'email' => $email, 'password_hash' => $password_hash])) {
            $pdo->commit();
            $_SESSION['success'] = "Registration successful! You can now login.";
            header("Location: index.php");
            exit;
        } else {
            $pdo->rollBack();
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header("Location: register.php");
            exit;
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: register.php");
        exit;
    }
} else {
    header("Location: register.php");
    exit;
}
?>
