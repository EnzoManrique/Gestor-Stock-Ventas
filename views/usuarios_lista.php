<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="bi bi-arrow-left-circle"></i> Volver al Panel</a>
            <span class="navbar-text">Gestión de Usuarios (RRHH)</span>
        </div>
    </nav>

    <div class="container">

        <?php if ($mensaje): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header <?php echo $usuario_editar ? 'bg-warning' : 'bg-dark'; ?> text-white">
                        <h5 class="mb-0">
                            <?php echo $usuario_editar ? '<i class="bi bi-pencil-square"></i> Editar Usuario' : '<i class="bi bi-person-plus-fill"></i> Nuevo Usuario'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="usuarios.php">
                            <input type="hidden" name="accion"
                                value="<?php echo $usuario_editar ? 'editar' : 'crear'; ?>">
                            <?php if ($usuario_editar): ?>
                                <input type="hidden" name="id_usuario" value="<?php echo $usuario_editar['id_usuario']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required
                                    value="<?php echo $usuario_editar ? $usuario_editar['nombre'] : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Apellido</label>
                                <input type="text" name="apellido" class="form-control" required
                                    value="<?php echo $usuario_editar ? $usuario_editar['apellido'] : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required
                                    value="<?php echo $usuario_editar ? $usuario_editar['email'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label>Rol</label>
                                <select name="rol" class="form-select">
                                    <option value="2" <?php echo ($usuario_editar && $usuario_editar['id_rol'] == 2) ? 'selected' : ''; ?>>Empleado</option>
                                    <option value="1" <?php echo ($usuario_editar && $usuario_editar['id_rol'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="<?php echo $usuario_editar ? '(Dejar vacío para no cambiar)' : 'Obligatoria al crear'; ?>"
                                    <?php echo $usuario_editar ? '' : 'required'; ?>>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit"
                                    class="btn <?php echo $usuario_editar ? 'btn-warning' : 'btn-dark'; ?>">
                                    <?php echo $usuario_editar ? 'Actualizar Datos' : 'Crear Usuario'; ?>
                                </button>
                                <?php if ($usuario_editar): ?>
                                    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
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
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_usuarios as $u): ?>
                                    <tr class="<?php echo $u['activo'] == 0 ? 'table-secondary text-muted' : ''; ?>">
                                        <td><?php echo $u['nombre'] . ' ' . $u['apellido']; ?></td>
                                        <td><?php echo $u['email']; ?></td>
                                        <td>
                                            <?php if ($u['id_rol'] == 1): ?>
                                                <span class="badge bg-dark">Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">Empleado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($u['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="usuarios.php?editar=<?php echo $u['id_usuario']; ?>"
                                                class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>

                                            <?php if ($u['activo']): ?>
                                                <?php if ($u['id_usuario'] != $_SESSION['usuario_id']): ?>
                                                    <a href="usuarios.php?eliminar=<?php echo $u['id_usuario']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Bloquear acceso a este usuario?')"><i
                                                            class="bi bi-slash-circle"></i></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="usuarios.php?activar=<?php echo $u['id_usuario']; ?>"
                                                    class="btn btn-sm btn-success" title="Reactivar"><i
                                                        class="bi bi-check-circle-fill"></i></a>
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
</body>

</html>