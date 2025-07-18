<?php 
include "../includes/auth_check.php";
include "../includes/role_check.php"; // Solo admins
include "../database.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historial de Actividades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>📝 Historial de Actividades</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT activity_logs.*, users.username 
                        FROM activity_logs 
                        JOIN users ON activity_logs.user_id = users.id
                        ORDER BY created_at DESC";
                $result = $conn->query($sql);
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['created_at'] . "</td>
                            <td>" . $row['username'] . "</td>
                            <td>" . $row['action'] . "</td>
                            <td>" . $row['description'] . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>