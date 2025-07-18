<?php include "database.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Mascotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Mascotas Registradas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Dueño</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT pets.id, pets.name, pets.species, pets.breed, owners.name AS owner_name 
                        FROM pets 
                        JOIN owners ON pets.owner_id = owners.id";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["species"] . "</td>
                            <td>" . ($row["breed"] ?: "N/A") . "</td>
                            <td>" . $row["owner_name"] . "</td>
                          </tr>";
                }
                ?>
            </tbody>
            echo "<a href='edit_pet.php?id={$row['id']}' class='btn btn-sm btn-warning'>Editar</a>";

            <a href='pets/edit_pet.php?id={$row['id']}'>Editar</a>
        </table>
    </div>
</body>
</html>