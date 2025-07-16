<?php 
include "../../includes/auth_check.php";
include "../../includes/role_check.php"; // Solo admins
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Usuarios Registrados</h2>
        <a href="add_user.php" class="btn btn-success mb-3">+ Nuevo Usuario</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "../../database.php";
                $sql = "SELECT id, username, role FROM users";
                $result = $conn->query($sql);
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>" . ucfirst($row['role']) . "</td>
                            <td>
                                <a href='edit_user.php?id={$row['id']}' class='btn btn-sm btn-warning'>Editar</a>
                                <a href='delete_user.php?id={$row['id']}' 
                                   class='btn btn-sm btn-danger' 
                                   onclick='return confirm(\"¿Eliminar usuario?\");'>Eliminar</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="../dashboard.php" class="btn btn-secondary">Volver al panel</a>
    </div>
</body>
</html>