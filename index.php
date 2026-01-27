<?php
// index.php
session_start();

// 1. Si NO hay sesión iniciada, lo mandamos al Login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: controllers/auth.php');
    exit;
}

// 2. Si hay sesión, mostramos el Dashboard
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistema Ventas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!--<li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>-->
                <a href="controllers/ventas.php" class="nav-link">Ir a Ventas</a>
                <?php if($_SESSION['rol'] == 1): ?>
                <a href="controllers/productos.php" class="nav-link">Productos</a>
                <?php endif; ?>
                <!--<li class="nav-item"><a class="nav-link" href="#">Productos</a></li>-->
            </ul>
            <span class="navbar-text text-white me-3">
                    Hola, <?php echo $_SESSION['nombre']; ?>
                    (<?php echo ($_SESSION['rol'] == 1) ? 'Admin' : 'Empleado'; ?>)
                </span>
            <a href="/gestion_ventas/logout.php" class="btn btn-outline-danger btn-sm">Salir</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Bienvenido al Panel de Control</h1>
    <p class="lead">Seleccioná una opción del menú para comenzar.</p>

    <div class="col-md-4">
        <div class="card text-center mb-3">
            <div class="card-body">
                <h5 class="card-title">Nueva Venta</h5>
                <p class="card-text">Registrar venta y descontar stock.</p>
                <a href="controllers/ventas.php" class="btn btn-primary">Ir a Ventas</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center mb-3">
            <div class="card-body">
                <h5 class="card-title">Reportes</h5>
                <p class="card-text">Ver historial y totales facturados.</p>
                <a href="controllers/reportes.php" class="btn btn-success">Ver Reporte</a>
            </div>
        </div>
    </div>

    <?php if($_SESSION['rol'] == 1): ?>
        <div class="col-md-4">
            <div class="card text-center mb-3 border-dark">
                <div class="card-body text-dark">
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text">Crear nuevos accesos al sistema.</p>
                    <a href="controllers/usuarios.php" class="btn btn-dark">Gestionar</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
</body>
</html>