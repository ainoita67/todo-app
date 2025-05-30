<?php
require_once 'config/database.php';

// Inicializar base de datos
$database = new Database();
$pdo = $database->pdo;

// Obtener todas las tareas
$stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY completed ASC, created_at DESC");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manejar mensajes de sesión
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message'], $_SESSION['message_type']);

include 'includes/header.php';
?>

<main class="main-content">
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para agregar nueva tarea -->
    <form id="taskForm" action="add_task.php" method="POST" class="add-task-form">
        <h2><i class="fas fa-plus-circle"></i> Agregar Nueva Tarea</h2>
        
        <div class="form-group">
            <label for="title">Título de la Tarea *</label>
            <input type="text" id="title" name="title" class="form-control" required maxlength="255" 
                   placeholder="Ej: Completar proyecto de programación">
        </div>
        
        <div class="form-group">
            <label for="description">Descripción (Opcional)</label>
            <textarea id="description" name="description" class="form-control" 
                      placeholder="Detalles adicionales sobre la tarea..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Tarea
        </button>
    </form>

    <!-- Lista de tareas -->
    <div class="tasks-list">
        <h2><i class="fas fa-list"></i> Mis Tareas (<?php echo count($tasks); ?>)</h2>
        
        <?php if (empty($tasks)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No tienes tareas aún</h3>
                <p>¡Agrega tu primera tarea usando el formulario de arriba!</p>
            </div>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
                    <div class="task-header">
                        <h3 class="task-title"><?php echo htmlspecialchars($task['title']); ?></h3>
                        <span class="task-status <?php echo $task['completed'] ? 'status-completed' : 'status-pending'; ?>">
                            <?php echo $task['completed'] ? 'Completada' : 'Pendiente'; ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($task['description'])): ?>
                        <div class="task-description">
                            <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="task-meta">
                        <i class="fas fa-calendar-alt"></i>
                        Creada: <?php echo date('d/m/Y H:i', strtotime($task['created_at'])); ?>
                        <?php if ($task['updated_at'] != $task['created_at']): ?>
                            | <i class="fas fa-edit"></i>
                            Actualizada: <?php echo date('d/m/Y H:i', strtotime($task['updated_at'])); ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="task-actions">
                        <?php if (!$task['completed']): ?>
                            <a href="update_task.php?id=<?php echo $task['id']; ?>&action=complete" 
                               class="btn btn-success btn-small">
                                <i class="fas fa-check"></i> Completar
                            </a>
                        <?php else: ?>
                            <a href="update_task.php?id=<?php echo $task['id']; ?>&action=reopen" 
                               class="btn btn-success btn-small">
                                <i class="fas fa-undo"></i> Reabrir
                            </a>
                        <?php endif; ?>
                        
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" 
                           class="btn btn-danger btn-small btn-delete">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>