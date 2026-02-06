<?php
// controllers/categorias.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Categoria.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 1) {
    header('Location: ../index.php');
    exit;
}

$categoriaModel = new Categoria($pdo);
$mensaje = "";
$categoria_editar = null;

//// 1. CREAR CATEGORÍA
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $nombre = $_POST['nombre'];
//    if (!empty($nombre)) {
//        $categoriaModel->crear($nombre);
//        $mensaje = "Categoría creada con éxito.";
//    }
//}

// 1. ELIMINAR
if (isset($_GET['eliminar'])) {
    // OJO: Solo permitir eliminar si no tiene productos asociados (controlar en BD o try-catch)
    try {
        $categoriaModel->eliminar($_GET['eliminar']);
        $mensaje = "Categoría eliminada.";
    } catch (Exception $e) {
        $mensaje = "No se puede eliminar: Hay productos en esta categoría.";
    }
}

// 2. PROCESAR FORMULARIO (Crear o Editar)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);

    if (!empty($nombre)) {
        if (isset($_POST['id_categoria']) && !empty($_POST['id_categoria'])) {
            // MODO EDICIÓN
            $categoriaModel->actualizar($_POST['id_categoria'], $nombre);
            $mensaje = "Categoría actualizada correctamente.";
        } else {
            // MODO CREACIÓN
            $categoriaModel->crear($nombre);
            $mensaje = "Categoría creada correctamente.";
        }
    }
}

// 3. MODO EDICIÓN (Cargar datos al hacer clic en el lápiz)
if (isset($_GET['editar'])) {
    $categoria_editar = $categoriaModel->obtenerPorId($_GET['editar']);
}

// 4. Obtener lista para la tabla
$lista_categorias = $categoriaModel->obtenerTodas();

// Vista simple integrada aquí mismo para no crear otro archivo más
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="productos.php">
            <i class="bi bi-arrow-left"></i> Volver a Productos
        </a>
        <span class="navbar-text">Configuración de Categorías</span>
    </div>
</nav>

<div class="container">

    <?php if($mensaje): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header text-white <?php echo $categoria_editar ? 'bg-warning' : 'bg-info'; ?>">
                    <h5 class="mb-0">
                        <?php echo $categoria_editar
                                ? '<i class="bi bi-pencil-square"></i> Editar Categoría'
                                : '<i class="bi bi-plus-lg"></i> Nueva Categoría';
                        ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="categorias.php">

                        <?php if($categoria_editar): ?>
                            <input type="hidden" name="id_categoria" value="<?php echo $categoria_editar['id_categoria']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Sillas, Monitores..."
                                   value="<?php echo $categoria_editar ? $categoria_editar['nombre'] : ''; ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn <?php echo $categoria_editar ? 'btn-warning' : 'btn-info text-white'; ?>">
                                <i class="bi bi-save"></i> <?php echo $categoria_editar ? 'Guardar Cambios' : 'Guardar'; ?>
                            </button>

                            <?php if($categoria_editar): ?>
                                <a href="categorias.php" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancelar Edición
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">Listado de Categorías</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lista_categorias as $c): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $c['nombre']; ?></td>
                                <td class="text-end">
                                    <a href="categorias.php?editar=<?php echo $c['id_categoria']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <a href="categorias.php?eliminar=<?php echo $c['id_categoria']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta categoría?')" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if(empty($lista_categorias)): ?>
                        <p class="text-center p-3 text-muted">No hay categorías cargadas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>