<?php

class RateLimiter {
    private $pdo;
    private $limit;
    private $window; // in seconds

    public function __construct($pdo, $limit = 5, $window = 900) {
        $this->pdo = $pdo;
        $this->limit = $limit;
        $this->window = $window;
    }

    public function check($ip, $username) {
        // Clean up old attempts first (optional, or rely on a cron job, but doing it here ensures accuracy)
        // Optimization: Don't delete on every check, maybe 1 in 100 calls, or just select count.
        // Let's just select count of recent attempts.
        
        $startTime = time() - $this->window;
        
        $sql = "SELECT COUNT(*) as count FROM login_attempts 
                WHERE ip_address = :ip 
                AND attempt_time > :startTime";
        // Optionally also check by username, but IP based is safer for brute force against many accounts.
        // If we want to protect a specific account, we can add OR username = :username
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ip' => $ip,
            ':startTime' => $startTime
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] < $this->limit;
    }

    public function recordAttempt($ip, $username) {
        $sql = "INSERT INTO login_attempts (ip_address, username, attempt_time) VALUES (:ip, :username, :time)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ip' => $ip,
            ':username' => $username,
            ':time' => time()
        ]);
    }

    public function clearAttempts($ip) {
        // Clear attempts for this IP after successful login
        $sql = "DELETE FROM login_attempts WHERE ip_address = :ip";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ip' => $ip]);
    }
}
?>
