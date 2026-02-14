<?php
// debug_db.php
// Upload this file to your live server's public_html folder to diagnose connection issues.
// Delete this file immediately after fixing the issue!

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>DB Connection Debugger</title><style>body{font-family:sans-serif;padding:20px;} .success{color:green;} .error{color:red;}</style></head><body>";
echo "<h1>Database Connection Debugger</h1>";

// 1. Check EnvLoader
$loaderPath = __DIR__ . '/includes/classes/EnvLoader.php';
if (file_exists($loaderPath)) {
    echo "<p class='success'>&#10003; EnvLoader.php found.</p>";
    require_once $loaderPath;
} else {
    echo "<p class='error'>&#10007; EnvLoader.php NOT found at $loaderPath. Please ensure you uploaded the 'includes' directory.</p>";
    die("</body></html>");
}

// 2. Detect Environment
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
echo "<p><strong>Detected Host:</strong> " . htmlspecialchars($httpHost) . "</p>";

$isProduction = (strpos($httpHost, 'mindtechnology.online') !== false);
echo "<p><strong>Environment Mode:</strong> " . ($isProduction ? "Production" : "Local") . "</p>";

// 3. Check .env File
$envFile = $isProduction ? __DIR__ . '/.env.production' : __DIR__ . '/.env';
echo "<p><strong>Looking for config file:</strong> " . htmlspecialchars(basename($envFile)) . "</p>";

if (file_exists($envFile)) {
    echo "<p class='success'>&#10003; Config file found.</p>";
    try {
        (new EnvLoader($envFile))->load();
    } catch (Exception $e) {
        echo "<p class='error'>Error loading config file: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>&#10007; Config file NOT found! Please upload <strong>" . htmlspecialchars(basename($envFile)) . "</strong> to the server root.</p>";
}

// 4. Check Credentials
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

echo "<h3>Loaded Credentials:</h3>";
echo "<ul>";
echo "<li><strong>DB_HOST:</strong> " . ($host ? htmlspecialchars($host) : "<span class='error'>Not Set</span>") . "</li>";
echo "<li><strong>DB_NAME:</strong> " . ($dbname ? htmlspecialchars($dbname) : "<span class='error'>Not Set</span>") . "</li>";
echo "<li><strong>DB_USER:</strong> " . ($user ? htmlspecialchars($user) : "<span class='error'>Not Set</span>") . "</li>";
echo "<li><strong>DB_PASS:</strong> " . ($pass !== false ? "******" : "<span class='error'>Not Set</span>") . "</li>";
echo "</ul>";

// 5. Attempt Connection
echo "<h3>Attempting Connection...</h3>";

if (!$host || !$dbname || !$user) {
    echo "<p class='error'>Cannot attempt connection because credentials are missing.</p>";
} else {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<h2 class='success'>&#10003; SUCCESS: Connected to database successfully!</h2>";
    } catch (PDOException $e) {
        echo "<h2 class='error'>&#10007; FAILURE: Could not connect.</h2>";
        echo "<div style='background:#fee;border:1px solid red;padding:15px;border-radius:5px;'>";
        echo "<strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br><br>";
        echo "<strong>Troubleshooting Tips:</strong><ul>";
        echo "<li>If error is 'Access denied', check Username and Password.</li>";
        echo "<li>If error is 'Unknown database', check Database Name.</li>";
        echo "<li>If error is 'Connection refused' or 'Network is unreachable', check DB_HOST. It might not be 'localhost'. Check your hosting control panel for the correct Database Host.</li>";
        echo "</ul></div>";
    }
}

echo "</body></html>";
?>
