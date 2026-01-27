<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Volver al Panel</a>
        <span class="navbar-text text-white">Reporte General</span>
    </div>
</nav>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Historial de Ventas</h4>
        </div>
        <div class="card-body">

            <?php if (empty($lista_ventas)): ?>
                <div class="alert alert-info">Aún no se han registrado ventas.</div>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th># ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Productos (Detalle)</th>
                            <th class="text-end">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lista_ventas as $v): ?>
                            <tr>
                                <td><?php echo $v['id_venta']; ?></td>
                                <td><?php echo $v['fecha']; ?></td>
                                <td><?php echo $v['cliente']; ?></td>
                                <td><?php echo $v['vendedor']; ?></td>
                                <td>
                                            <span class="badge bg-secondary text-wrap" style="text-align: left;">
                                                <?php echo $v['productos_vendidos']; ?>
                                            </span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    $<?php echo number_format($v['total'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>