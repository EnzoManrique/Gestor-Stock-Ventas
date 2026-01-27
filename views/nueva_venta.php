<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Volver al Panel</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Nueva Venta</h4>
                </div>
                <div class="card-body">

                    <?php if(!empty($mensaje)): ?>
                        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                            <?php echo $mensaje; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="cliente" class="form-select" required>
                                <option value="">Seleccione un cliente...</option>
                                <?php foreach($lista_clientes as $c): ?>
                                    <option value="<?php echo $c['id_cliente']; ?>">
                                        <?php echo $c['nombre'] . ' ' . $c['apellido'] . ' (DNI: ' . $c['dni_cuil'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <select name="producto" class="form-select" required>
                                <option value="">Seleccione un producto...</option>
                                <?php foreach($lista_productos as $p): ?>
                                    <option value="<?php echo $p['id_producto']; ?>">
                                        <?php echo $p['nombre']; ?> - $<?php echo $p['precio_venta']; ?>
                                        (Stock: <?php echo $p['stock']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" min="1" value="1" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Confirmar Venta</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>