<?php
require_once 'bootstrap.php';
$logger->log('Logout', $_SESSION['user_id'] ?? null, session_id());
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>
