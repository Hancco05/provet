<?php
require_once "../../includes/auth_check.php";
require_once "../../database.php";

$pet_id = $_GET['pet_id'] ?? null;
$pet = $conn->query("SELECT name FROM pets WHERE id = $pet_id")->fetch_assoc();
$vaccines = $conn->query("SELECT * FROM vaccines WHERE pet_id = $pet_id ORDER BY date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vacunas de <?= htmlspecialchars($pet['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-shield-plus"></i> Vacunas de <?= htmlspecialchars($pet['name']) ?>
            </h2>
            <div>
                <a href="add.php?pet_id=<?= $pet_id ?>" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva
                </a>
                <a href="../../pets/list_pets.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Vacuna</th>
                                <th>Fecha</th>
                                <th>Próxima</th>
                                <th>Veterinario</th>
                                <th>Notas</th>
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
                                    <?= $vac['notes'] ? 
                                        '<button class="btn btn-sm btn-outline-info" data-bs-toggle="popover" 
                                         title="Notas" data-bs-content="'.htmlspecialchars($vac['notes']).'">
                                          <i class="bi bi-eye"></i>
                                         </button>' : 
                                        '--' ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activar popovers de notas
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        popoverTriggerList.map(function (element) {
            return new bootstrap.Popover(element)
        })
    </script>
    <a href="medical.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Historial Médico">
    <i class="bi bi-heart-pulse"></i>
</a>
</body>
</html>