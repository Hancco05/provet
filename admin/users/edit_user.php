<?php 
include "../../includes/auth_check.php";
include "../../includes/role_check.php";

include "../../database.php";

// Obtener datos del usuario a editar
$id = $_GET["id"];
$sql = "SELECT id, username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $role = $_POST["role"];
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;
    
    if ($password) {
        $sql = "UPDATE users SET username=?, password=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $password, $role, $id);
    } else {
        $sql = "UPDATE users SET username=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: list_users.php?success=Usuario actualizado");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<!-- Formulario similar a add_user.php pero con datos precargados -->