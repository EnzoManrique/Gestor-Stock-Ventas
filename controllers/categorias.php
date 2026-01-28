<?php
// controllers/categorias.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Categoria.php';

if (!isset($_SESSION['usuario_id'])) { header('Location: auth.php'); exit; }

$categoriaModel = new Categoria($pdo);
$mensaje = "";

// 1. CREAR CATEGORÍA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    if (!empty($nombre)) {
        $categoriaModel->crear($nombre);
        $mensaje = "Categoría creada con éxito.";
    }
}

// 2. ELIMINAR
if (isset($_GET['eliminar'])) {
    // OJO: Solo permitir eliminar si no tiene productos asociados (controlar en BD o try-catch)
    try {
        $categoriaModel->eliminar($_GET['eliminar']);
        $mensaje = "Categoría eliminada.";
    } catch (Exception $e) {
        $mensaje = "No se puede eliminar: Hay productos en esta categoría.";
    }
}

$lista_categorias = $categoriaModel->obtenerTodas();

// Vista simple integrada aquí mismo para no crear otro archivo más
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="productos.php">⬅ Volver a Productos</a>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">Nueva Categoría</div>
                <div class="card-body">
                    <?php if($mensaje) echo "<div class='alert alert-info'>$mensaje</div>"; ?>
                    <form method="POST">
                        <input type="text" name="nombre" class="form-control mb-3" placeholder="Ej: Sillas, Monitores..." required>
                        <button type="submit" class="btn btn-info w-100 text-white">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <ul class="list-group shadow">
                <li class="list-group-item active bg-secondary border-secondary">Listado de Categorías</li>
                <?php foreach($lista_categorias as $c): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $c['nombre']; ?>
                        <a href="categorias.php?eliminar=<?php echo $c['id_categoria']; ?>" class="btn btn-sm btn-danger text-white">x</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>