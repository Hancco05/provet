<?php
require_once "../../includes/auth_check.php";
require_once "../../database.php";

$pet_id = $_GET['pet_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'] ?? null;
    $cost = $_POST['cost'] ?? null;
    $vet = $_POST['veterinarian'] ?? null;

    $stmt = $conn->prepare("INSERT INTO treatments (pet_id, type, description, start_date, end_date, cost, veterinarian) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssds", $pet_id, $type, $description, $start_date, $end_date, $cost, $vet);
    
    if ($stmt->execute()) {
        header("Location: list.php?pet_id=$pet_id&success=Tratamiento registrado");
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
    <title>Nuevo Tratamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4><i class="bi bi-heart-pulse"></i> Nuevo Tratamiento para <?= htmlspecialchars($pet['name']) ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="pet_id" value="<?= $pet_id ?>">
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de Tratamiento *</label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="medicación">Medicación</option>
                                        <option value="cirugía">Cirugía</option>
                                        <option value="terapia">Terapia</option>
                                        <option value="otros">Otros</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Veterinario</label>
                                    <input type="text" name="veterinarian" class="form-control">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Descripción *</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Fecha Inicio *</label>
                                    <input type="date" name="start_date" class="form-control" required 
                                           value="<?= date('Y-m-d') ?>">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Fecha Fin (opcional)</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Costo (opcional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="cost" class="form-control" step="0.01" min="0">
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="list.php?pet_id=<?= $pet_id ?>" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Guardar Tratamiento
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>