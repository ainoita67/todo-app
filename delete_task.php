<?php
require_once 'config/database.php';
session_start();

// Verificar que sea una petición GET con ID válido
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$task_id = intval($_GET['id']);

// Validar ID de tarea
if ($task_id <= 0) {
    $_SESSION['message'] = 'ID de tarea inválido.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

try {
    // Inicializar base de datos
    $database = new Database();
    $pdo = $database->pdo;
    
    // Verificar que la tarea existe antes de eliminar
    $check_stmt = $pdo->prepare("SELECT id, title FROM tasks WHERE id = ?");
    $check_stmt->execute([$task_id]);
    $task = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task) {
        $_SESSION['message'] = 'La tarea no existe o ya fue eliminada.';
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    
    // Eliminar la tarea
    $delete_stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $result = $delete_stmt->execute([$task_id]);
    
    if ($result && $delete_stmt->rowCount() > 0) {
        $_SESSION['message'] = 'Tarea "' . htmlspecialchars($task['title']) . '" eliminada exitosamente.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error al eliminar la tarea.';
        $_SESSION['message_type'] = 'error';
    }
    
} catch (PDOException $e) {
    $_SESSION['message'] = 'Error de base de datos: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    error_log("Error al eliminar tarea: " . $e->getMessage());
}

// Redirigir de vuelta a la página principal
header('Location: index.php');
exit;
?>
