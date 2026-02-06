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