<?php
require_once __DIR__ . '/includes/classes/EnvLoader.php';

// Environment Detection
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isProduction = (strpos($httpHost, 'mindtechnology.online') !== false);

// Load Configuration
try {
    if ($isProduction) {
        // Try to load production env file if it exists, otherwise assume server ENV vars are set
        $envFile = __DIR__ . '/.env.production';
        if (file_exists($envFile)) {
            (new EnvLoader($envFile))->load();
        }
    } else {
        // Local Environment
        $envFile = __DIR__ . '/.env';
        if (file_exists($envFile)) {
            (new EnvLoader($envFile))->load();
        }
    }
} catch (Exception $e) {
    // If loading .env fails, we continue. 
    // In production, real environment variables might be set directly on the server.
    // In local, this might mean missing configuration.
    error_log("Config Load Error: " . $e->getMessage());
}

// Retrieve Credentials
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'quiz_app';
$username = getenv('DB_USER') ?: 'root';
// Handle empty password correctly: getenv returns false if not set, or empty string if set to empty.
// If .env has DB_PASS=, it might be empty string.
$password = getenv('DB_PASS');
if ($password === false) {
    $password = ''; // Default for local root
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // In production, do not show detailed error messages to the user
    if ($isProduction) {
        error_log("Database Connection Error: " . $e->getMessage());
        die("Service temporarily unavailable. Please try again later.");
    } else {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}
?>
