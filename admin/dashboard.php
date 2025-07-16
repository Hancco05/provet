<?php 
include "../includes/auth_check.php";
include "../includes/role_check.php"; // Solo admins pueden ver esto
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido Admin: <?php echo $_SESSION["username"]; ?></h1>
        
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Usuarios</div>
                <div class="card-body">
                    <h5 class="card-title">Gestionar acceso</h5>
                    <a href="users/list_users.php" class="btn btn-light">Administrar</a>
                </div>
            
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Estadísticas</div>
                    <div class="card-body">
                        <h5 class="card-title">Reportes del sistema</h5>
                        <a href="#" class="btn btn-light">Generar</a>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="../auth/logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
    </div>
</body>
</html>