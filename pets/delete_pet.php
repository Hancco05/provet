<?php
require_once "../includes/auth_check.php";
require_once "../database.php";

// Verificar si se recibió ID por GET
if (!isset($_GET['id'])) {
    header("Location: list_pets.php?error=ID no proporcionado");
    exit();
}

$id = $_GET['id'];

// Opcional: Verificar permisos adicionales (ej: solo admin puede eliminar)
if ($_SESSION['role'] != 'admin') {
    header("Location: list_pets.php?error=Solo administradores pueden eliminar");
    exit();
}

// Consulta preparada para evitar SQL injection
$sql = "DELETE FROM pets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Registro en logs (opcional)
    if (function_exists('log_activity')) {
        log_activity(
            $conn,
            $_SESSION['user_id'],
            'delete_pet',
            "Se eliminó la mascota ID $id"
        );
    }
    header("Location: list_pets.php?success=Mascota eliminada");
} else {
    header("Location: list_pets.php?error=Error al eliminar");
}
exit();
?>