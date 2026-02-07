<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-arrow-left-circle"></i> Volver al Panel</a>
            <span class="navbar-text">Dashboard Gerencial</span>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4"><i class="bi bi-pie-chart-fill"></i> Análisis de Negocio</h2>

        <div class="card mb-4 shadow-sm">
            <div class="card-body py-2"> <form method="GET" action="estadisticas.php" class="row align-items-center">
                    <div class="col-auto">
                        <label class="fw-bold"><i class="bi bi-calendar-range"></i> Filtrar Reporte:</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="desde" class="form-control form-control-sm" value="<?php echo $fecha_desde; ?>" required>
                    </div>
                    <div class="col-auto">
                        <span class="fw-bold">hasta</span>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="hasta" class="form-control form-control-sm" value="<?php echo $fecha_hasta; ?>" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i> Aplicar
                        </button>
                        <a href="estadisticas.php" class="btn btn-outline-secondary btn-sm" title="Restablecer">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2 border-primary border-3 border-top-0 border-end-0 border-bottom-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Facturación Total
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    $<?php echo number_format($reporte_rango['total_facturado'], 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-currency-dollar fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-left-info shadow h-100 py-2 border-info border-3 border-top-0 border-end-0 border-bottom-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Tickets Emitidos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $reporte_rango['total_tickets']; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-receipt fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-left-success shadow h-100 py-2 border-success border-3 border-top-0 border-end-0 border-bottom-0">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Ganancia Neta (Real)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    $<?php echo number_format($reporte_rango['ganancia_neta'], 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-graph-up-arrow fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-white fw-bold">Evolución de Ventas ($)</div>
                    <div class="card-body">
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-white fw-bold">Top 5 Productos</div>
                    <div class="card-body">
                        <canvas id="chartProductos"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-primary text-white shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    Valor Total del Stock
                                </div>
                                <div class="h1 mb-0 font-weight-bold">
                                    $<?php echo number_format($valor_inventario, 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span style="font-size: 3rem;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. Configuración Gráfico de VENTAS (Barras)
        const ctxVentas = document.getElementById('chartVentas');
        new Chart(ctxVentas, {
            type: 'bar',
            data: {
                // PHP imprime los arrays de JS directamente aquí
                labels: <?php echo json_encode($labels_mes); ?>,
                datasets: [{
                    label: 'Facturación Mensual',
                    data: <?php echo json_encode($data_mes); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Configuración Gráfico de PRODUCTOS (Doughnut/Torta)
        const ctxProd = document.getElementById('chartProductos');
        new Chart(ctxProd, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labels_prod); ?>,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: <?php echo json_encode($data_prod); ?>,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                    ]
                }]
            }
        });
    </script>

</body>

</html>