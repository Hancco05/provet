<?php 
include "../includes/auth_check.php";
if ($_SESSION["role"] == 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Veterinario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido Vet: <?php echo $_SESSION["username"]; ?></h1>
        
        <div class="card mt-4">
            <div class="card-header">Acciones rápidas</div>
            <div class="card-body">
                <a href="../appointments/add_appointment.php" class="btn btn-primary">Nueva cita</a>
                <a href="../pets/list_pets.php" class="btn btn-secondary">Ver mascotas</a>
            </div>
        </div>
    </div>
</body>
</html>