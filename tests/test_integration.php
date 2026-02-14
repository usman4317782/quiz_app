<?php
// Simple Integration Test Script
// Run from CLI: php tests/test_integration.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/classes/RateLimiter.php';
require_once __DIR__ . '/../includes/classes/Logger.php';
require_once __DIR__ . '/../includes/classes/DatabaseSessionHandler.php';

echo "Starting Integration Tests...\n";

// 1. Test Rate Limiter
echo "\n[Test] RateLimiter...\n";
$rateLimiter = new RateLimiter($pdo, 3, 60); // 3 attempts in 60s
$testIp = '127.0.0.100'; // Unique IP for testing
$testUser = 'test_user';

// Clear previous tests
$rateLimiter->clearAttempts($testIp);

// Check initial state
if ($rateLimiter->check($testIp, $testUser)) {
    echo "PASS: Initial check allowed.\n";
} else {
    echo "FAIL: Initial check blocked.\n";
}

// Record 3 attempts
$rateLimiter->recordAttempt($testIp, $testUser);
$rateLimiter->recordAttempt($testIp, $testUser);
$rateLimiter->recordAttempt($testIp, $testUser);

// Check blocked state
if (!$rateLimiter->check($testIp, $testUser)) {
    echo "PASS: Blocked after 3 attempts.\n";
} else {
    echo "FAIL: Not blocked after 3 attempts.\n";
}

// Clear and check
$rateLimiter->clearAttempts($testIp);
if ($rateLimiter->check($testIp, $testUser)) {
    echo "PASS: Allowed after clear.\n";
} else {
    echo "FAIL: Blocked after clear.\n";
}

// 2. Test Logger
echo "\n[Test] Logger...\n";
$logger = new Logger($pdo);
$testEvent = 'Test Event ' . time();
$logger->log($testEvent, null, 'test_session_id');

$stmt = $pdo->prepare("SELECT * FROM auth_logs WHERE event_type = :event");
$stmt->execute(['event' => $testEvent]);
if ($stmt->fetch()) {
    echo "PASS: Log entry found in database.\n";
} else {
    echo "FAIL: Log entry not found.\n";
}

// 3. Test Session Handler
echo "\n[Test] DatabaseSessionHandler...\n";
$handler = new DatabaseSessionHandler($pdo);
$sessionId = 'test_sess_' . time();
$sessionData = 'user_id|i:123;username|s:4:"test";';

// Write
if ($handler->write($sessionId, $sessionData)) {
    echo "PASS: Session write successful.\n";
} else {
    echo "FAIL: Session write failed.\n";
}

// Read
$readData = $handler->read($sessionId);
if ($readData === $sessionData) {
    echo "PASS: Session read matches written data.\n";
} else {
    echo "FAIL: Session read mismatch. Got: $readData\n";
}

// Destroy
$handler->destroy($sessionId);
$readDataAfterDestroy = $handler->read($sessionId);
if (empty($readDataAfterDestroy)) {
    echo "PASS: Session destroyed successfully.\n";
} else {
    echo "FAIL: Session data still exists after destroy.\n";
}

echo "\nTests Completed.\n";
?>
