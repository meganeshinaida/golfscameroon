<?php
require_once __DIR__ . '/../config/database.php';

class Blog {
    private $conn;
    private $table = 'blogs';

    public function __construct($db = null) {
        if ($db) $this->conn = $db; else { $database = new Database(); $this->conn = $database->getConnection(); }
    }

    public function all() {
        $sql = "SELECT b.*, u.username as author FROM " . $this->table . " b LEFT JOIN users u ON b.author_id = u.id ORDER BY b.created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT b.*, u.username as author FROM " . $this->table . " b LEFT JOIN users u ON b.author_id = u.id WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (title, content, author_id, thumbnail, image) VALUES (:title, :content, :author_id, :thumbnail, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':author_id' => $data['author_id'] ?? null,
            ':thumbnail' => $data['thumbnail'] ?? null,
            ':image' => $data['image'] ?? null,
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " SET title = :title, content = :content, thumbnail = :thumbnail, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':thumbnail' => $data['thumbnail'] ?? null,
            ':image' => $data['image'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete($id) {
        $post = $this->find($id);
        if ($post) {
            if (!empty($post['image'])) { @unlink(__DIR__ . '/../uploads/' . $post['image']); }
            if (!empty($post['thumbnail'])) { @unlink(__DIR__ . '/../uploads/thumbs/' . pathinfo($post['thumbnail'], PATHINFO_FILENAME) . '.webp'); }
        }
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

?>
