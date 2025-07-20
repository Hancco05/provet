<?php
require_once "../../includes/auth_check.php";
require_once "../../database.php";

$pet_id = $_GET['pet_id'] ?? null;
$pet = $conn->query("SELECT name FROM pets WHERE id = $pet_id")->fetch_assoc();
$treatments = $conn->query("SELECT * FROM treatments WHERE pet_id = $pet_id ORDER BY start_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tratamientos de <?= htmlspecialchars($pet['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .badge-treatment {
            font-size: 0.85em;
        }
        .badge-medication { background-color: #6f42c1; }
        .badge-surgery { background-color: #d63384; }
        .badge-therapy { background-color: #fd7e14; }
        .badge-other { background-color: #20c997; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-heart-pulse"></i> Tratamientos de <?= htmlspecialchars($pet['name']) ?>
            </h2>
            <div>
                <a href="add.php?pet_id=<?= $pet_id ?>" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nuevo
                </a>
                <a href="../../pets/medical.php?id=<?= $pet_id ?>" class="btn btn-outline-secondary">
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
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Fechas</th>
                                <th>Veterinario</th>
                                <th>Costo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($t = $treatments->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="badge rounded-pill badge-treatment 
                                        badge-<?= str_replace('ó', 'o', strtolower($t['type'])) ?>">
                                        <?= ucfirst($t['type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars(substr($t['description'], 0, 30)) ?>...</div>
                                    <small class="text-muted"><?= substr(strip_tags($t['description']), 0, 50) ?>...</small>
                                </td>
                                <td>
                                    <div><strong>Inicio:</strong> <?= date('d/m/Y', strtotime($t['start_date'])) ?></div>
                                    <?php if ($t['end_date']): ?>
                                        <div><strong>Fin:</strong> <?= date('d/m/Y', strtotime($t['end_date'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $t['veterinarian'] ?: '--' ?></td>
                                <td>
                                    <?= $t['cost'] ? '$' . number_format($t['cost'], 2) : '--' ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal<?= $t['id'] ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal con Detalles -->
                            <div class="modal fade" id="detailsModal<?= $t['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <?= ucfirst($t['type']) ?> - 
                                                <?= date('d/m/Y', strtotime($t['start_date'])) ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Descripción completa:</strong></p>
                                                    <p><?= nl2br(htmlspecialchars($t['description'])) ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Detalles:</strong></p>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <strong>Veterinario:</strong> 
                                                            <?= $t['veterinarian'] ?: 'No especificado' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Período:</strong> 
                                                            <?= date('d/m/Y', strtotime($t['start_date'])) ?> 
                                                            a 
                                                            <?= $t['end_date'] ? date('d/m/Y', strtotime($t['end_date'])) : 'En curso' ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Costo:</strong> 
                                                            <?= $t['cost'] ? '$' . number_format($t['cost'], 2) : 'No registrado' ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>