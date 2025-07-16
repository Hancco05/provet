<?php 
include "../../includes/auth_check.php";
include "../../includes/role_check.php";

include "../../database.php";

$id = $_GET["id"];
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: list_users.php?success=Usuario eliminado");
} else {
    header("Location: list_users.php?error=No se pudo eliminar");
}
exit();
?>