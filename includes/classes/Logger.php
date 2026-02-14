<?php

class Logger {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function log($eventType, $userId = null, $sessionId = null, $statusCode = 200, $message = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        $sql = "INSERT INTO auth_logs (user_id, event_type, ip_address, user_agent, session_id, status_code, message) 
                VALUES (:user_id, :event_type, :ip, :user_agent, :session_id, :status_code, :message)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':event_type' => $eventType,
                ':ip' => $ip,
                ':user_agent' => $userAgent,
                ':session_id' => $sessionId,
                ':status_code' => $statusCode,
                ':message' => $message
            ]);
        } catch (PDOException $e) {
            // Silently fail or log to file system if DB logging fails
            error_log("Database logging failed: " . $e->getMessage());
        }
    }
}
?>
