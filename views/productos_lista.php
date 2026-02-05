<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="bi bi-arrow-left-circle"></i> Volver al Panel</a>
        <span class="navbar-text">Gestión de Inventario</span>
    </div>
</nav>

<div class="container">

    <?php if($mensaje): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <?php echo $producto_editar ? '<i class="bi bi-pencil-square"></i> Editar Producto' : '<i class="bi bi-plus-circle"></i> Nuevo Producto'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="productos.php">
                        <input type="hidden" name="accion" value="<?php echo $producto_editar ? 'editar' : 'crear'; ?>">
                        <?php if($producto_editar): ?>
                            <input type="hidden" name="id_producto" value="<?php echo $producto_editar['id_producto']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required
                                   value="<?php echo $producto_editar ? $producto_editar['nombre'] : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoría</label>

                            <div class="input-group">
                                <select name="categoria" class="form-select" required>
                                    <option value="" disabled <?php echo !$producto_editar ? 'selected' : ''; ?>>Seleccione una opción...</option>

                                    <?php if (!empty($lista_categorias_bd)): ?>
                                        <?php foreach($lista_categorias_bd as $cat): ?>
                                            <option value="<?php echo $cat['id_categoria']; ?>"
                                                    <?php echo ($producto_editar && $producto_editar['id_categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                                <?php echo $cat['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled><i class="bi bi-exclamation-triangle"></i> No hay categorías cargadas</option>
                                    <?php endif; ?>
                                </select>
                                <a href="categorias.php" class="btn btn-secondary" title="Gestionar Categorías"><i class="bi bi-gear-fill"></i></a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Costo</label>
                                <input type="number" step="0.01" name="costo" class="form-control" required
                                       value="<?php echo $producto_editar ? $producto_editar['precio_costo'] : ''; ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Venta</label>
                                <input type="number" step="0.01" name="venta" class="form-control" required
                                       value="<?php echo $producto_editar ? $producto_editar['precio_venta'] : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Inicial</label>
                            <input type="number" name="stock" class="form-control" required
                                   value="<?php echo $producto_editar ? $producto_editar['stock'] : ''; ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Producto</button>
                            <?php if($producto_editar): ?>
                                <a href="productos.php" class="btn btn-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3 border-primary">
                <div class="card-body p-2">
                    <input type="text" id="txtBuscarProducto" class="form-control" placeholder="Buscar por nombre o categoría...">
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>
                                    <a href="productos.php?orden=stock_asc" class="text-white text-decoration-none" title="Ordenar por menor stock">
                                        Stock <i class="bi bi-sort-down"></i>
                                    </a>
                                </th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody id="cuerpoTablaProductos">
                            <?php foreach($lista_productos as $p): ?>
                                <tr class="<?php echo $p['activo'] == 0 ? 'table-secondary text-muted' : ''; ?>">
                                    <td class="fw-bold"><?php echo $p['nombre']; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $p['categoria_nombre'] ?? 'Sin Cat.'; ?></span></td>
                                    <td>$<?php echo number_format($p['precio_venta'], 2); ?></td>
                                    <td>
                                        <?php if($p['stock'] <= 2): ?>
                                            <span class="badge bg-danger rounded-pill"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $p['stock']; ?></span>
                                        <?php elseif($p['stock'] <= 5): ?>
                                            <span class="badge bg-warning text-dark rounded-pill"><?php echo $p['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success rounded-pill"><?php echo $p['stock']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $p['activo'] ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Activo</span>' : '<span class="text-danger">Inactivo</span>'; ?>
                                    </td>
                                    <td>
                                        <a href="productos.php?editar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                        <?php if($p['activo']): ?>
                                            <a href="productos.php?eliminar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Baja?')"><i class="bi bi-trash-fill"></i></a>
                                        <?php else: ?>
                                            <a href="productos.php?activar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-success"><i class="bi bi-arrow-clockwise"></i></a>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('txtBuscarProducto').addEventListener('input', function() {
        let termino = this.value;
        let formData = new FormData();
        formData.append('termino', termino);

        fetch('ajax_buscar_productos_admin.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                let cuerpo = document.getElementById('cuerpoTablaProductos');
                cuerpo.innerHTML = "";

                if(data.length === 0) {
                    cuerpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No se encontraron productos.</td></tr>';
                }

                data.forEach(p => {
                    let badgeStock = '';
                    if(p.stock <= 2) {
                        badgeStock = `<span class="badge bg-danger rounded-pill"><i class="bi bi-exclamation-triangle-fill"></i> ${p.stock}</span>`;
                    } else if(p.stock <= 5) {
                        badgeStock = `<span class="badge bg-warning text-dark rounded-pill">${p.stock}</span>`;
                    } else {
                        badgeStock = `<span class="badge bg-success rounded-pill">${p.stock}</span>`;
                    }

                    let estado = p.activo == 1
                        ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Activo</span>'
                        : '<span class="text-danger">Inactivo</span>';

                    let cat = p.categoria_nombre ? p.categoria_nombre : 'Sin Cat.';

                    let fila = `
                <tr class="${p.activo == 0 ? 'table-secondary text-muted' : ''}">
                    <td class="fw-bold">${p.nombre}</td>
                    <td><span class="badge bg-secondary">${cat}</span></td>
                    <td>$${parseFloat(p.precio_venta).toFixed(2)}</td>
                    <td>${badgeStock}</td>
                    <td>${estado}</td>
                    <td>
                        <a href="productos.php?editar=${p.id_producto}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                        ${p.activo == 1
                        ? `<a href="productos.php?eliminar=${p.id_producto}" class="btn btn-sm btn-danger" onclick="return confirm('¿Baja?')"><i class="bi bi-trash-fill"></i></a>`
                        : `<a href="productos.php?activar=${p.id_producto}" class="btn btn-sm btn-success"><i class="bi bi-arrow-clockwise"></i></a>`
                    }
                    </td>
                </tr>
            `;
                    cuerpo.innerHTML += fila;
                });
            })
            .catch(error => console.error(error));
    });
</script>

</body>
</html>