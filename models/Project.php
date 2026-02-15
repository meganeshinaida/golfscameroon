<?php
require_once __DIR__ . '/../config/database.php';

class Project {
    private $conn;
    private $table = 'projects';

    public function __construct($db = null) {
        if ($db) $this->conn = $db; else { $database = new Database(); $this->conn = $database->getConnection(); }
    }

    public function all() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (name, description, target_amount, payment_link, image) VALUES (:name, :description, :target_amount, :payment_link, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':target_amount' => $data['target_amount'],
            ':payment_link' => $data['payment_link'],
            ':image' => $data['image'] ?? null,
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET name = :name, description = :description, target_amount = :target_amount, payment_link = :payment_link, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':target_amount' => $data['target_amount'],
            ':payment_link' => $data['payment_link'],
            ':image' => $data['image'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete($id) {
        // fetch image name to delete file
        $proj = $this->find($id);
        if ($proj && !empty($proj['image'])) {
            $path = __DIR__ . '/../uploads/' . $proj['image'];
            if (file_exists($path)) @unlink($path);
        }
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

?>