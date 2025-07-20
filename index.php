<?php
require_once __DIR__ . '/includes/auth_check.php'; // Ruta corregida
require_once __DIR__ . '/database.php'; // Conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Veterinario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-heart-pulse"></i> VetSys
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="pets/list_pets.php">
                                <i class="bi bi-list-ul"></i> Mascotas
                            </a>
                        </li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/users/list_users.php">
                                <i class="bi bi-people-fill"></i> Usuarios
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex">
                        <span class="navbar-text me-3">
                            <i class="bi bi-person-circle"></i> <?= $_SESSION['username'] ?>
                        </span>
                        <a href="auth/logout.php" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Panel de bienvenida -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto text-center">
                <h2 class="display-6">
                    <i class="bi bi-heart-fill text-danger"></i> Bienvenid@, 
                    <?= $_SESSION['role'] == 'admin' ? 'Administrador' : 'Veterinario' ?>
                </h2>
                <p class="lead">Sistema de Gestión Veterinaria</p>
            </div>
        </div>

        <!-- Tarjetas de acceso rápido -->
        <div class="row g-4">
            <!-- Tarjeta Mascotas -->
            <div class="col-md-4">
                <div class="card card-hover h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-paw text-primary" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">Mascotas</h5>
                        <p class="card-text">Gestión completa de pacientes animales</p>
                        <a href="pets/list_pets.php" class="btn btn-primary stretched-link">
                            <i class="bi bi-arrow-right-circle"></i> Acceder
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Médica (visible para todos) -->
            <div class="col-md-4">
                <div class="card card-hover h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-heart-pulse text-danger" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">Historial Médico</h5>
                        <p class="card-text">Registro de vacunas y tratamientos</p>
                        <a href="pets/medical.php?id=1" class="btn btn-danger stretched-link">
                            <i class="bi bi-clipboard2-pulse"></i> Ver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Usuarios (solo admin) -->
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="col-md-4">
                <div class="card card-hover h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-lock text-success" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">Administración</h5>
                        <p class="card-text">Gestión de usuarios y permisos</p>
                        <a href="admin/dashboard.php" class="btn btn-success stretched-link">
                            <i class="bi bi-gear"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sección de estadísticas (ejemplo) -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-graph-up"></i> Estadísticas Rápidas
                    </div>
                    <div class="row text-center">
    <?php
    function safe_table_count($conn, $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        return ($result->num_rows > 0) 
            ? $conn->query("SELECT COUNT(*) FROM $table")->fetch_row()[0] 
            : 0;
    }
    ?>
    
    <div class="col-md-4">
        <h3><?= safe_table_count($conn, 'pets') ?></h3>
        <p class="text-muted">Mascotas registradas</p>
    </div>
    <div class="col-md-4">
        <h3><?= safe_table_count($conn, 'vaccines') ?></h3>
        <p class="text-muted">Vacunas aplicadas</p>
    </div>
    <div class="col-md-4">
        <h3><?= safe_table_count($conn, 'users') ?></h3>
        <p class="text-muted">Usuarios activos</p>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>