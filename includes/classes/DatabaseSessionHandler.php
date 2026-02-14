<?php

class DatabaseSessionHandler implements SessionHandlerInterface {
    private $pdo;
    private $key;

    public function __construct($pdo, $key = '') {
        $this->pdo = $pdo;
        $this->key = $key; // Encryption key (optional, for future enhancement)
    }

    public function open($savePath, $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string|false {
        try {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id AND access > :time");
            // Check for session expiry (e.g., 30 mins idle) inside the query or handle GC separately.
            // But standard read just retrieves data. GC handles cleanup.
            // However, we should respect session.gc_maxlifetime
            $stmt->execute([
                ':id' => $id,
                ':time' => time() - (int)ini_get('session.gc_maxlifetime')
            ]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row['data'];
            }
            return '';
        } catch (PDOException $e) {
            // Log error
            return '';
        }
    }

    public function write($id, $data): bool {
        try {
            $access = time();
            // Use INSERT ... ON DUPLICATE KEY UPDATE to handle concurrency better
            $sql = "INSERT INTO sessions (id, access, data) VALUES (:id, :access, :data)
                    ON DUPLICATE KEY UPDATE access = :access, data = :data";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':access' => $access,
                ':data' => $data
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function destroy($id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function gc($max_lifetime): int|false {
        try {
            $old = time() - $max_lifetime;
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE access < :old");
            $stmt->execute([':old' => $old]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
