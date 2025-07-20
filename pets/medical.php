<?php
require_once "../includes/auth_check.php";
require_once "../database.php";

// Verifica que el ID de mascota exista
$pet_id = $_GET['id'] ?? null;
if (!$pet_id || !is_numeric($pet_id)) {
    header("Location: list_pets.php?error=ID de mascota no válido");
    exit();
}

// Consulta segura con prepared statements
$stmt = $conn->prepare("SELECT pets.*, owners.name AS owner_name 
                       FROM pets 
                       JOIN owners ON pets.owner_id = owners.id
                       WHERE pets.id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet = $stmt->get_result()->fetch_assoc();

// Verifica que la mascota exista
if (!$pet) {
    header("Location: list_pets.php?error=Mascota no encontrada");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Historial Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-heart-pulse"></i> Historial Médico de 
                <span class="text-primary"><?= htmlspecialchars($pet['name']) ?></span>
            </h2>
            <a href="list_pets.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <!-- Tarjeta Info Mascota -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Foto de la mascota - Versión corregida -->
                <div class="col-md-2 text-center">
                    <?php if (!empty($pet['photo_path'])): ?>
                        <img src="../<?= htmlspecialchars($pet['photo_path']) ?>" 
                            class="img-thumbnail" 
                            style="width:100px;height:100px;object-fit:cover"
                            alt="Foto de <?= htmlspecialchars($pet['name']) ?>">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                            style="width:100px;height:100px;border-radius:50%;">
                            <i class="bi bi-camera" style="font-size:2rem;color:#6c757d;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                    <div class="container mt-5">
        <!-- Encabezado con datos de la mascota -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-heart-pulse"></i> Historial de <?= htmlspecialchars($pet['name']) ?>
                <small class="text-muted">Dueño: <?= htmlspecialchars($pet['owner_name']) ?></small>
            </h2>
            <a href="list_pets.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="medicalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="vaccines-tab" data-bs-toggle="tab" 
                        data-bs-target="#vaccines" type="button" role="tab">
                    <i class="bi bi-shield-plus"></i> Vacunas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="treatments-tab" data-bs-toggle="tab" 
                        data-bs-target="#treatments" type="button" role="tab">
                    <i class="bi bi-heart-pulse"></i> Tratamientos
                </button>
            </li>
        </ul>

        <!-- Contenido de Pestañas -->
        <div class="tab-content p-3 border border-top-0 rounded-bottom">
            <!-- Pestaña Vacunas -->
            <div class="tab-pane fade show active" id="vaccines" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Registro de Vacunas</h5>
                    <a href="../medical/vaccines/add.php?pet_id=<?= $pet_id ?>" 
                       class="btn btn-sm btn-success">
                        <i class="bi bi-plus"></i> Nueva Vacuna
                    </a>
                </div>
                
                <?php
                $vaccines = $conn->query("SELECT * FROM vaccines WHERE pet_id = $pet_id ORDER BY date DESC");
                if ($vaccines->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha</th>
                                    <th>Próxima</th>
                                    <th>Veterinario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($vac = $vaccines->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($vac['name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($vac['date'])) ?></td>
                                    <td>
                                        <?= $vac['next_date'] ? date('d/m/Y', strtotime($vac['next_date'])) : '--' ?>
                                        <?php if ($vac['next_date'] && strtotime($vac['next_date']) <= time()): ?>
                                            <span class="badge bg-danger ms-2">¡Pendiente!</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $vac['veterinarian'] ?: '--' ?></td>
                                    <td>
                                        <a href="../medical/vaccines/list.php?pet_id=<?= $pet_id ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">No hay vacunas registradas</div>
                <?php endif; ?>
            </div>

            <!-- Pestaña Tratamientos -->
            <div class="tab-pane fade" id="treatments" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Registro de Tratamientos</h5>
                    <a href="../medical/treatments/add.php?pet_id=<?= $pet_id ?>" 
                       class="btn btn-sm btn-success">
                        <i class="bi bi-plus"></i> Nuevo Tratamiento
                    </a>
                </div>
                <div class="alert alert-info">
                    Módulo en desarrollo - Próximamente
                </div>
            </div>
            <!-- Cambiar el alert por este contenido -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <?php
                    $treatments = $conn->query("SELECT * FROM treatments 
                                            WHERE pet_id = $pet_id 
                                            ORDER BY start_date DESC 
                                            LIMIT 3");
                    if ($treatments->num_rows > 0): ?>
                        <tbody>
                            <?php while ($t = $treatments->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-<?= 
                                        $t['type'] == 'medicación' ? 'primary' : 
                                        ($t['type'] == 'cirugía' ? 'danger' : 'warning') 
                                    ?>">
                                        <?= ucfirst($t['type']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($t['start_date'])) ?></td>
                                <td><?= substr($t['description'], 0, 30) ?>...</td>
                                <td>
                                    <a href="../medical/treatments/list.php?pet_id=<?= $pet_id ?>" 
                                    class="btn btn-sm btn-outline-info">
                                        Ver todos
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    <?php else: ?>
                        <div class="alert alert-warning">No hay tratamientos registrados</div>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>