<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db = null) {
        if ($db) {
            $this->conn = $db;
        } else {
            $database = new Database();
            $this->conn = $database->getConnection();
        }
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    public function createAdmin($username, $password, $role = 'admin') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO " . $this->table . " (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':username' => $username, ':password' => $hash, ':role' => $role]);
    }

    public function verifyPassword($username, $password) {
        $user = $this->findByUsername($username);
        if (!$user) return false;
        return password_verify($password, $user['password']);
    }

    public function all() {
        $sql = "SELECT id, username, role, created_at FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>
