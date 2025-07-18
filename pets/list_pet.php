<?php
require_once "../includes/auth_check.php";
require_once "../database.php";

// Paginación
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Consulta principal
$sql = "SELECT pets.id, pets.name, pets.species, pets.breed, owners.name AS owner_name 
        FROM pets 
        JOIN owners ON pets.owner_id = owners.id
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Contar total para paginación
$total_pets = $conn->query("SELECT COUNT(*) FROM pets")->fetch_row()[0];
$total_pages = ceil($total_pets / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Mascotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🐾 Mascotas Registradas</h2>
            <a href="add_pet.php" class="btn btn-success">+ Nueva Mascota</a>
        </div>

        <!-- Filtros (opcional) -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre...">
                </div>
                <div class="col-md-3">
                    <select name="species" class="form-select">
                        <option value="">Todas las especies</option>
                        <option value="Perro">Perro</option>
                        <option value="Gato">Gato</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Dueño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['species']) ?></td>
                    <td><?= $row['breed'] ? htmlspecialchars($row['breed']) : 'N/A' ?></td>
                    <td><?= htmlspecialchars($row['owner_name']) ?></td>
                    <td>
                        <a href="edit_pet.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="delete_pet.php?id=<?= $row['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar esta mascota?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>