<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'todo_app';
    private $username = 'todo_user';
    private $password = 'todo_password';
    private $port = '5433';
    public $pdo;

    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>