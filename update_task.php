<?php
require_once 'config/database.php';
session_start();

// Verificar que sea una petición GET con parámetros válidos
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: index.php');
    exit;
}

$task_id = intval($_GET['id']);
$action = $_GET['action'];

// Validar ID de tarea
if ($task_id <= 0) {
    $_SESSION['message'] = 'ID de tarea inválido.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

// Validar acción
if (!in_array($action, ['complete', 'reopen'])) {
    $_SESSION['message'] = 'Acción inválida.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

try {
    // Inicializar base de datos
    $database = new Database();
    $pdo = $database->pdo;
    
    // Verificar que la tarea existe
    $check_stmt = $pdo->prepare("SELECT id, completed FROM tasks WHERE id = ?");
    $check_stmt->execute([$task_id]);
    $task = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task) {
        $_SESSION['message'] = 'La tarea no existe.';
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    
    // Determinar nuevo estado
    $new_completed_status = ($action === 'complete') ? true : false;
    
    // Verificar si ya está en el estado deseado
    if (($task['completed'] && $action === 'complete') || (!$task['completed'] && $action === 'reopen')) {
        $message = ($action === 'complete') ? 'La tarea ya está completada.' : 'La tarea ya está pendiente.';
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    
    // Actualizar el estado de la tarea
    $update_sql = "UPDATE tasks SET completed = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $result = $update_stmt->execute([$new_completed_status, $task_id]);
    
    if ($result) {
        $message = ($action === 'complete') ? 'Tarea marcada como completada.' : 'Tarea reabierta exitosamente.';
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error al actualizar la tarea.';
        $_SESSION['message_type'] = 'error';
    }
    
} catch (PDOException $e) {
    $_SESSION['message'] = 'Error de base de datos: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    error_log("Error al actualizar tarea: " . $e->getMessage());
}

// Redirigir de vuelta a la página principal
header('Location: index.php');
exit;
?>