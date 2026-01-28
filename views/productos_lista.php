<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">⬅ Volver al Panel</a>
        <span class="navbar-text">Gestión de Productos</span>
    </div>
</nav>

<div class="container">

    <?php if($mensaje): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header <?php echo $producto_editar ? 'bg-warning' : 'bg-primary'; ?> text-white">
                    <h5 class="mb-0">
                        <?php echo $producto_editar ? '✏️ Editar Producto' : '➕ Nuevo Producto'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="productos.php">
                        <input type="hidden" name="accion" value="<?php echo $producto_editar ? 'editar' : 'crear'; ?>">

                        <?php if($producto_editar): ?>
                            <input type="hidden" name="id_producto" value="<?php echo $producto_editar['id_producto']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required
                                   value="<?php echo $producto_editar ? $producto_editar['nombre'] : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label>Categoría</label>
                            <div class="input-group">
                                <select name="categoria" class="form-select" required>
                                    <?php foreach($lista_categorias_bd as $cat): ?>
                                        <option value="<?php echo $cat['id_categoria']; ?>"
                                                <?php echo ($producto_editar && $producto_editar['id_categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <a href="categorias.php" class="btn btn-outline-secondary" title="Nueva Categoría">➕</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Costo</label>
                                <input type="number" step="0.01" name="costo" class="form-control" required
                                       value="<?php echo $producto_editar ? $producto_editar['precio_costo'] : ''; ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label>Venta</label>
                                <input type="number" step="0.01" name="venta" class="form-control" required
                                       value="<?php echo $producto_editar ? $producto_editar['precio_venta'] : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" required
                                   value="<?php echo $producto_editar ? $producto_editar['stock'] : ''; ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn <?php echo $producto_editar ? 'btn-warning' : 'btn-primary'; ?>">
                                <?php echo $producto_editar ? 'Actualizar Cambios' : 'Guardar Producto'; ?>
                            </button>

                            <?php if($producto_editar): ?>
                                <a href="productos.php" class="btn btn-secondary">Cancelar Edición</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>$ Venta</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lista_productos as $p): ?>
                            <tr class="<?php echo $p['activo'] == 0 ? 'table-secondary text-muted' : ''; ?>">
                                <td><?php echo $p['nombre']; ?></td>
                                <td>$<?php echo $p['precio_venta']; ?></td>
                                <td><?php echo $p['stock']; ?></td>
                                <td>
                                    <?php if($p['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="productos.php?editar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-warning">✏️</a>

                                    <?php if($p['activo']): ?>
                                        <a href="productos.php?eliminar=<?php echo $p['id_producto']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Dar de baja este producto?')">🗑️</a>
                                    <?php else: ?>
                                        <a href="productos.php?activar=<?php echo $p['id_producto']; ?>"
                                           class="btn btn-sm btn-success" title="Reactivar">♻️</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>