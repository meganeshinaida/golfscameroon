<?php
require_once __DIR__ . '/../config/database.php';

class Donor {
    private $conn;
    private $table = 'donors';

    public function __construct($db = null) {
        if ($db) $this->conn = $db; else { $database = new Database(); $this->conn = $database->getConnection(); }
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (name, email, location, phone) VALUES (:name, :email, :location, :phone)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':location' => $data['location'] ?? null,
            ':phone' => $data['phone'] ?? null,
        ]);
    }

    public function findLastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function find($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

    public function all() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
}

?>
