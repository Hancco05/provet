<?php 
require_once "includes/auth_check.php"; // Verifica sesión
require_once "database.php";

// Obtener ID de la URL
$id = $_GET['id'];

// Obtener datos actuales de la mascota
$sql = "SELECT pets.*, owners.name AS owner_name 
        FROM pets 
        JOIN owners ON pets.owner_id = owners.id
        WHERE pets.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$pet = $stmt->get_result()->fetch_assoc();

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $species = $_POST["species"];
    $breed = $_POST["breed"];
    $owner_id = $_POST["owner_id"];

    $sql = "UPDATE pets SET name=?, species=?, breed=?, owner_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $species, $breed, $owner_id, $id);

    if ($stmt->execute()) {
        header("Location: list_pets.php?success=Mascota actualizada");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar <?php echo htmlspecialchars($pet['name']); ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Campos del formulario (igual que en el código anterior) -->
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($pet['name']); ?>" required>
            </div>
            <!-- ... (otros campos) ... -->
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="list_pets.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>