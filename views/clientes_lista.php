<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php">⬅ Volver al Panel</a>
        <span class="navbar-text">Gestión de Clientes</span>
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
                <div class="card-header <?php echo $cliente_editar ? 'bg-warning' : 'bg-primary'; ?> text-white">
                    <h5 class="mb-0">
                        <?php echo $cliente_editar ? '✏️ Editar Cliente' : '👤 Nuevo Cliente'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="clientes.php">
                        <input type="hidden" name="accion" value="<?php echo $cliente_editar ? 'editar' : 'crear'; ?>">
                        <?php if($cliente_editar): ?>
                            <input type="hidden" name="id_cliente" value="<?php echo $cliente_editar['id_cliente']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required
                                   value="<?php echo $cliente_editar ? $cliente_editar['nombre'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Apellido</label>
                            <input type="text" name="apellido" class="form-control" required
                                   value="<?php echo $cliente_editar ? $cliente_editar['apellido'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>DNI / CUIL</label>
                            <input type="text" name="dni" class="form-control" required
                                   value="<?php echo $cliente_editar ? $cliente_editar['dni_cuil'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control"
                                   value="<?php echo $cliente_editar ? $cliente_editar['telefono'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo $cliente_editar ? $cliente_editar['email'] : ''; ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn <?php echo $cliente_editar ? 'btn-warning' : 'btn-primary'; ?>">
                                <?php echo $cliente_editar ? 'Guardar Cambios' : 'Registrar Cliente'; ?>
                            </button>
                            <?php if($cliente_editar): ?>
                                <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3 border-primary">
                <div class="card-body p-2">
                    <input type="text" id="txtBuscarCliente" class="form-control" placeholder="🔍 Buscar por nombre, DNI o email...">
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>DNI</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody id="cuerpoTablaClientes">
                            <?php foreach($lista_clientes as $c): ?>
                                <tr class="<?php echo $c['activo'] == 0 ? 'table-secondary text-muted' : ''; ?>">
                                    <td><?php echo $c['nombre'] . ' ' . $c['apellido']; ?></td>
                                    <td><?php echo $c['dni_cuil']; ?></td>
                                    <td>
                                        <small>📞 <?php echo $c['telefono']; ?><br>
                                            ✉️ <?php echo $c['email']; ?></small>
                                    </td>
                                    <td>
                                        <?php if($c['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="clientes.php?editar=<?php echo $c['id_cliente']; ?>" class="btn btn-sm btn-warning">✏️</a>

                                        <?php if($c['activo']): ?>
                                            <a href="clientes.php?eliminar=<?php echo $c['id_cliente']; ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Dar de baja a este cliente?')">🗑️</a>
                                        <?php else: ?>
                                            <a href="clientes.php?activar=<?php echo $c['id_cliente']; ?>"
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
</div>

<script>
    document.getElementById('txtBuscarCliente').addEventListener('input', function() {
        let termino = this.value;
        let formData = new FormData();
        formData.append('termino', termino);

        fetch('ajax_buscar_clientes.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                let cuerpo = document.getElementById('cuerpoTablaClientes');
                cuerpo.innerHTML = ""; // Limpiar tabla

                if(data.length === 0) {
                    cuerpo.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No se encontraron clientes.</td></tr>';
                }

                data.forEach(c => {
                    // Badge de estado
                    let estado = c.activo == 1
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-secondary">Inactivo</span>';

                    // Botones de acción
                    let fila = `
                <tr class="${c.activo == 0 ? 'table-secondary text-muted' : ''}">
                    <td>${c.nombre} ${c.apellido}</td>
                    <td>${c.dni_cuil}</td>
                    <td><small>📞 ${c.telefono}<br>✉️ ${c.email}</small></td>
                    <td>${estado}</td>
                    <td>
                        <a href="clientes.php?editar=${c.id_cliente}" class="btn btn-sm btn-warning">✏️</a>

                        ${c.activo == 1
                        ? `<a href="clientes.php?eliminar=${c.id_cliente}" class="btn btn-sm btn-danger" onclick="return confirm('¿Baja?')">🗑️</a>`
                        : `<a href="clientes.php?activar=${c.id_cliente}" class="btn btn-sm btn-success" title="Reactivar">♻️</a>`
                    }
                    </td>
                </tr>
            `;
                    cuerpo.innerHTML += fila;
                });
            })
            .catch(error => console.error('Error:', error));
    });
</script>

</body>
</html>