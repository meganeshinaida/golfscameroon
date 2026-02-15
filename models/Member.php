<?php
require_once __DIR__ . '/../config/database.php';

class Member {
    private $conn;
    private $table = 'members';

    public function __construct($db = null) {
        if ($db) $this->conn = $db; else { $database = new Database(); $this->conn = $database->getConnection(); }
    }

    public function all() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (name, role, bio, image) VALUES (:name, :role, :bio, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':role' => $data['role'] ?? null,
            ':bio' => $data['bio'] ?? null,
            ':image' => $data['image'] ?? null,
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET name = :name, role = :role, bio = :bio, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':role' => $data['role'] ?? null,
            ':bio' => $data['bio'] ?? null,
            ':image' => $data['image'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete($id) {
        $m = $this->find($id);
        if ($m && !empty($m['image'])) {
            @unlink(__DIR__ . '/../uploads/' . $m['image']);
            @unlink(__DIR__ . '/../uploads/thumbs/' . pathinfo($m['image'], PATHINFO_FILENAME) . '.webp');
        }
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

?>
