<?php
// index.php
session_start();
require_once 'config/db.php'; // Asegurate que la ruta esté bien
require_once 'models/Dashboard.php';

// Si no hay sesión, al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: controllers/auth.php');
    exit;
}

// INSTANCIAMOS EL DASHBOARD PARA LAS ESTADÍSTICAS
$dashboard = new Dashboard($pdo);
$ventas_hoy = $dashboard->totalVentasHoy();
$cantidad_ventas = $dashboard->cantidadVentasHoy();
$alerta_stock = $dashboard->productosBajoStock();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🚀 Sistema Ventas</a>
        <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3">
                    Hola, <strong><?php echo $_SESSION['nombre']; ?></strong>
                    (<?php echo ($_SESSION['rol'] == 1) ? 'Admin' : 'Empleado'; ?>)
                </span>
            <a href="/gestion_ventas/logout.php" class="btn btn-outline-danger btn-sm">Salir</a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow">
                <div class="card-header">Vendido Hoy</div>
                <div class="card-body">
                    <h2 class="card-title">$<?php echo number_format($ventas_hoy, 2, ',', '.'); ?></h2>
                    <p class="card-text">En <?php echo $cantidad_ventas; ?> operaciones.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white <?php echo ($alerta_stock > 0) ? 'bg-danger' : 'bg-primary'; ?> mb-3 shadow">
                <div class="card-header">Estado de Stock</div>
                <div class="card-body">
                    <?php if($alerta_stock > 0): ?>
                        <h2 class="card-title">⚠️ <?php echo $alerta_stock; ?> Productos</h2>
                        <p class="card-text">Con stock bajo (menos de 5).</p>
                    <?php else: ?>
                        <h2 class="card-title">✅ Todo OK</h2>
                        <p class="card-text">Stock saludable.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-info mb-3 shadow">
                <div class="card-header">Fecha Sistema</div>
                <div class="card-body">
                    <h2 class="card-title"><?php echo date('d/m/Y'); ?></h2>
                    <p class="card-text">Bienvenido al sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Panel de Acciones</h3>

    <div class="row">

        <div class="col-md-4">
            <div class="card text-center mb-3 h-100 shadow-sm hover-card">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title">🛒 Nueva Venta</h5>
                    <p class="card-text text-muted">Registrar venta y descontar stock.</p>
                    <a href="controllers/ventas.php" class="btn btn-primary mt-auto">Ir a Ventas</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center mb-3 h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title">📊 Reportes</h5>
                    <p class="card-text text-muted">Ver historial y facturación.</p>
                    <a href="controllers/reportes.php" class="btn btn-success mt-auto">Ver Reporte</a>
                </div>
            </div>
        </div>

        <?php if($_SESSION['rol'] == 1): ?>
            <div class="col-md-4">
                <div class="card text-center mb-3 h-100 shadow-sm border-warning">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">📦 Inventario</h5>
                        <p class="card-text text-muted">ABM de Productos y Precios.</p>
                        <a href="controllers/productos.php" class="btn btn-warning mt-auto">Gestionar Productos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center mb-3 h-100 shadow-sm border-dark">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">👥 Usuarios</h5>
                        <p class="card-text text-muted">Crear accesos para empleados.</p>
                        <a href="controllers/usuarios.php" class="btn btn-dark mt-auto">Gestionar Accesos</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>