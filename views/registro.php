<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">⬅ Volver al Panel</a>
        <span class="navbar-text">Administración de Usuarios</span>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Nuevo Usuario</h4>
                </div>
                <div class="card-body">

                    <?php if($mensaje): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label>Apellido</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Email (Usuario)</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Rol de Acceso</label>
                            <select name="rol" class="form-select">
                                <option value="2">Empleado (Solo Ventas)</option>
                                <option value="1">Administrador (Acceso Total)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Crear Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>