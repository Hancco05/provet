<?php include "includes/auth_check.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">🐾 Bienvenido a VetSys</h1>
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="list-group">
                    <?php if ($_SESSION["role"] == 'admin'): ?>
                        <a href="admin/dashboard.php" class="list-group-item list-group-item-action active">
                            Panel de Administración
                        </a>
                    <?php else: ?>
                        <a href="vet/dashboard.php" class="list-group-item list-group-item-action active">
                            Panel Veterinario
                        </a>
                    <?php endif; ?>
                    
                    <a href="pets/list_pets.php" class="list-group-item list-group-item-action">
                        Mascotas
                    </a>
                    <a href="auth/logout.php" class="list-group-item list-group-item-action text-danger">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>