<?php include "database.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Mascota</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="pet_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Especie</label>
                <select name="species" class="form-control" required>
                    <option value="Perro">Perro</option>
                    <option value="Gato">Gato</option>
                    <option value="Ave">Ave</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Raza (opcional)</label>
                <input type="text" name="breed" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Dueño</label>
                <select name="owner_id" class="form-control" required>
                    <?php
                    $sql = "SELECT id, name FROM owners";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $pet_name = $_POST["pet_name"];
            $species = $_POST["species"];
            $breed = $_POST["breed"];
            $owner_id = $_POST["owner_id"];

            $sql = "INSERT INTO pets (name, species, breed, owner_id) 
                    VALUES ('$pet_name', '$species', '$breed', $owner_id)";
            
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3'>Mascota registrada!</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
            }
        }
        ?>
    </div>
</body>
</html>