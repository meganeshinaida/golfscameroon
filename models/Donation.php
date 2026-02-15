<?php
require_once __DIR__ . '/../config/database.php';

class Donation {
    private $conn;
    private $table = 'donations';

    public function __construct($db = null) {
        if ($db) $this->conn = $db; else { $database = new Database(); $this->conn = $database->getConnection(); }
    }

    public function create($donor_id, $project_id, $amount) {
        $sql = "INSERT INTO " . $this->table . " (donor_id, project_id, amount) VALUES (:donor_id, :project_id, :amount)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':donor_id'=>$donor_id, ':project_id'=>$project_id, ':amount'=>$amount]);
    }

    public function allWithDetails($filters = []) {
        $sql = "SELECT d.*, p.name as project_name, r.name as donor_name, r.email as donor_email FROM " . $this->table . " d LEFT JOIN projects p ON d.project_id = p.id LEFT JOIN donors r ON d.donor_id = r.id";
        $clauses = [];
        $params = [];
        if (!empty($filters['project_id'])) { $clauses[] = 'd.project_id = :pid'; $params[':pid'] = $filters['project_id']; }
        if (!empty($filters['from'])) { $clauses[] = 'd.created_at >= :from'; $params[':from'] = $filters['from']; }
        if (!empty($filters['to'])) { $clauses[] = 'd.created_at <= :to'; $params[':to'] = $filters['to']; }
        if ($clauses) $sql .= ' WHERE ' . implode(' AND ', $clauses);
        $sql .= ' ORDER BY d.created_at DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT d.*, p.name as project_name, r.name as donor_name, r.email as donor_email FROM " . $this->table . " d LEFT JOIN projects p ON d.project_id = p.id LEFT JOIN donors r ON d.donor_id = r.id WHERE d.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}

?>
