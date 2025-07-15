<?php include "database.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Dueños</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Dueños Registrados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM owners";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["phone"] . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>