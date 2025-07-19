<?php
require_once "../../includes/auth_check.php";
require_once "../../database.php";

$pet_id = $_GET['pet_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $next_date = $_POST['next_date'] ?? null;
    $notes = $_POST['notes'] ?? null;
    $vet = $_POST['veterinarian'] ?? null;

    $stmt = $conn->prepare("INSERT INTO vaccines (pet_id, name, date, next_date, notes, veterinarian) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $pet_id, $name, $date, $next_date, $notes, $vet);
    
    if ($stmt->execute()) {
        header("Location: list.php?pet_id=$pet_id&success=Vacuna registrada");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}

$pet = $conn->query("SELECT name FROM pets WHERE id = $pet_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Vacuna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Nueva Vacuna para <?= htmlspecialchars($pet['name']) ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="pet_id" value="<?= $pet_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre de Vacuna *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Aplicación *</label>
                                    <input type="date" name="date" class="form-control" required 
                                           value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Próxima Aplicación</label>
                                    <input type="date" name="next_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3 mt-3">
                                <label class="form-label">Veterinario</label>
                                <input type="text" name="veterinarian" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notas</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="list.php?pet_id=<?= $pet_id ?>" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Vacuna</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>