<?php
require_once "../includes/auth_check.php";
require_once "../database.php";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'] ?? null;
    $owner_id = $_POST['owner_id'];

    $stmt = $conn->prepare("INSERT INTO pets (name, species, breed, owner_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $species, $breed, $owner_id);

    if ($stmt->execute()) {
        header("Location: list_pets.php?success=Mascota registrada exitosamente");
        exit();
    } else {
        $error = "Error al registrar: " . $conn->error;
    }
}

// Obtener lista de dueños
$owners = $conn->query("SELECT id, name FROM owners");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Nueva Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar Nueva Mascota</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la mascota *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Especie *</label>
                                <select name="species" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Perro">Perro</option>
                                    <option value="Gato">Gato</option>
                                    <option value="Ave">Ave</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Raza (opcional)</label>
                                <input type="text" name="breed" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dueño *</label>
                                <select name="owner_id" class="form-select" required>
                                    <option value="">Seleccionar dueño...</option>
                                    <?php while ($owner = $owners->fetch_assoc()): ?>
                                        <option value="<?= $owner['id'] ?>"><?= htmlspecialchars($owner['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <a href="list_pets.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>