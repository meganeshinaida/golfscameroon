<?php
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'ngo_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            // In production, log instead of echo
            echo "Database connection error: " . $exception->getMessage();
            exit;
        }

        return $this->conn;
    }
}

?>