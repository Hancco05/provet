<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../database.php';

// Validación del ID
$pet_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$pet_id) {
    header("Location: list_pets.php?error=ID de mascota inválido");
    exit();
}

// Consulta segura con JOIN para obtener datos completos
$stmt = $conn->prepare("SELECT 
    pets.id, 
    pets.name, 
    pets.species, 
    pets.breed, 
    pets.photo_path,
    owners.name AS owner_name,
    owners.phone AS owner_phone
    FROM pets 
    JOIN owners ON pets.owner_id = owners.id
    WHERE pets.id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$pet = $stmt->get_result()->fetch_assoc();

// Verificar si la mascota existe
if (!$pet) {
    header("Location: list_pets.php?error=Mascota no encontrada");
    exit();
}

// Obtener estadísticas médicas
$stats = [
    'vaccines' => getRecordCount($conn, 'vaccines', $pet_id),
    'treatments' => getRecordCount($conn, 'treatments', $pet_id)
];

// Función auxiliar para conteo seguro
function getRecordCount($conn, $table, $pet_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM $table WHERE pet_id = ?");
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico - <?= htmlspecialchars($pet['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .medical-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .medical-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .pet-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">
                    <i class="bi bi-heart-pulse text-primary"></i> Historial Médico
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="list_pets.php">Mascotas</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($pet['name']) ?></li>
                    </ol>
                </nav>
            </div>
            <a href="list_pets.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <!-- Tarjeta de información básica -->
        <div class="card medical-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Foto de la mascota -->
                    <div class="col-md-2 text-center">
                        <?php if (!empty($pet['photo_path']) && file_exists("../{$pet['photo_path']}")): ?>
                            <img src="../<?= htmlspecialchars($pet['photo_path']) ?>" 
                                 class="pet-photo" 
                                 alt="Foto de <?= htmlspecialchars($pet['name']) ?>">
                        <?php else: ?>
                            <div class="pet-photo bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-camera" style="font-size:2rem;color:#6c757d;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Datos de la mascota -->
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="mb-3"><?= htmlspecialchars($pet['name']) ?></h2>
                                <p class="mb-1">
                                    <strong><i class="bi bi-tag"></i> Especie:</strong> 
                                    <span class="badge bg-info"><?= htmlspecialchars($pet['species']) ?></span>
                                </p>
                                <?php if (!empty($pet['breed'])): ?>
                                <p class="mb-1">
                                    <strong><i class="bi bi-diagram-3"></i> Raza:</strong> 
                                    <?= htmlspecialchars($pet['breed']) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong><i class="bi bi-person"></i> Dueño:</strong> 
                                    <?= htmlspecialchars($pet['owner_name']) ?>
                                </p>
                                <p class="mb-1">
                                    <strong><i class="bi bi-telephone"></i> Contacto:</strong> 
                                    <?= !empty($pet['owner_phone']) ? htmlspecialchars($pet['owner_phone']) : 'No registrado' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card medical-card text-white bg-primary">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= $stats['vaccines'] ?></h3>
                        <p class="card-text">Vacunas registradas</p>
                        <a href="../medical/vaccines/list.php?pet_id=<?= $pet_id ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-eye"></i> Ver todas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card medical-card text-white bg-success">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= $stats['treatments'] ?></h3>
                        <p class="card-text">Tratamientos aplicados</p>
                        <a href="../medical/treatments/list.php?pet_id=<?= $pet_id ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-eye"></i> Ver todos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card medical-card text-white bg-warning">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= date('d/m/Y') ?></h3>
                        <p class="card-text">Última visita</p>
                        <a href="../medical/vaccines/add.php?pet_id=<?= $pet_id ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Nueva vacuna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas de historial -->
        <ul class="nav nav-tabs" id="medicalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="vaccines-tab" data-bs-toggle="tab" data-bs-target="#vaccines" type="button">
                    <i class="bi bi-shield-plus"></i> Vacunas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="treatments-tab" data-bs-toggle="tab" data-bs-target="#treatments" type="button">
                    <i class="bi bi-heart-pulse"></i> Tratamientos
                </button>
            </li>
        </ul>

        <!-- Contenido de pestañas -->
        <div class="tab-content border border-top-0 p-3 rounded-bottom">
            <!-- Pestaña de Vacunas -->
            <div class="tab-pane fade show active" id="vaccines" role="tabpanel">
                <?php include '../medical/vaccines/partials/list_by_pet.php'; ?>
            </div>
            
            <!-- Pestaña de Tratamientos -->
            <div class="tab-pane fade" id="treatments" role="tabpanel">
                <?php include '../medical/treatments/partials/list_by_pet.php'; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>