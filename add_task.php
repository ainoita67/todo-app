<?php
require_once 'config/database.php';
session_start();

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validar datos del formulario
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($title)) {
    $_SESSION['message'] = 'El título de la tarea es obligatorio.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

if (strlen($title) < 3) {
    $_SESSION['message'] = 'El título debe tener al menos 3 caracteres.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

if (strlen($title) > 255) {
    $_SESSION['message'] = 'El título no puede exceder 255 caracteres.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

try {
    // Inicializar base de datos
    $database = new Database();
    $pdo = $database->pdo;
    
    // Preparar la consulta SQL
    $sql = "INSERT INTO tasks (title, description, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la consulta
    $result = $stmt->execute([$title, $description]);
    
    if ($result) {
        $_SESSION['message'] = 'Tarea agregada exitosamente.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error al agregar la tarea.';
        $_SESSION['message_type'] = 'error';
    }
    
} catch (PDOException $e) {
    $_SESSION['message'] = 'Error de base de datos: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    error_log("Error al agregar tarea: " . $e->getMessage());
}

// Redirigir de vuelta a la página principal
header('Location: index.php');
exit;
?>