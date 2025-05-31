<?php
class Database {
    public $pdo;

    public function __construct() {
        try {
            // Datos extraídos directamente de la URL de Render
            $host = 'dpg-d0tcq7mmcj7s73dfbvhg-a';
            $port = '5432';
            $dbname = 'todo_app_ys5c';
            $user = 'todo_user';
            $pass = '26nfmIRwIQY2xhntT7kiM9ERnUBQ9QuD';

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
