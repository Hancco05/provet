<?php include "database.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Dueño</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Dueño</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $phone = $_POST["phone"];

            $sql = "INSERT INTO owners (name, phone) VALUES ('$name', '$phone')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3'>Dueño registrado!</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
            }
        }
        ?>
    </div>
</body>
</html>