<?php
class Setting {
    private $db;

    public function __construct($connection = null) {
        if ($connection) {
            $this->db = $connection;
        } else {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $this->db = $database->getConnection();
        }
    }

    public function all() {
        $stmt = $this->db->query('SELECT * FROM settings ORDER BY `key_name`');
        return $stmt->fetchAll();
    }

    public function get($key, $default = null) {
        $stmt = $this->db->prepare('SELECT value FROM settings WHERE `key_name` = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    }

    public function set($key, $value) {
        // Check if key exists
        $stmt = $this->db->prepare('SELECT id FROM settings WHERE `key_name` = :key');
        $stmt->execute([':key' => $key]);
        $exists = $stmt->fetch();

        if ($exists) {
            // Update
            $stmt = $this->db->prepare('UPDATE settings SET value = :value WHERE `key_name` = :key');
            return $stmt->execute([':value' => $value, ':key' => $key]);
        } else {
            // Insert
            $stmt = $this->db->prepare('INSERT INTO settings (`key_name`, value) VALUES (:key, :value)');
            return $stmt->execute([':key' => $key, ':value' => $value]);
        }
    }

    public function delete($key) {
        $stmt = $this->db->prepare('DELETE FROM settings WHERE `key_name` = :key');
        return $stmt->execute([':key' => $key]);
    }
}
?>
