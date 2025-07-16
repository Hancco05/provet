<?php
// Debe incluirse DESPUÉS de auth_check.php
if ($_SESSION["role"] != 'admin') {
    header("Location: ../vet/dashboard.php"); // Redirige a veterinarios si no es admin
    exit();
}
?>