<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        /* Ajuste para que Select2 se vea bien con Bootstrap */
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px;
        }
    </style>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="bi bi-arrow-left-circle"></i> Volver al Panel</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">1. Agregar Productos</h5>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Buscar Producto</label>
                        <select id="selectProducto" class="form-select">
                            <option value="">Buscar...</option>
                            <?php foreach ($lista_productos as $p): ?>
                                <option value="<?php echo $p['id_producto']; ?>"
                                        data-precio="<?php echo $p['precio_venta']; ?>"
                                        data-nombre="<?php echo $p['nombre']; ?>"
                                        data-stock="<?php echo $p['stock']; ?>">
                                    <?php echo $p['nombre']; ?> ($<?php echo $p['precio_venta']; ?>) - Stock:
                                    <?php echo $p['stock']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" id="inputCantidad" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-warning w-100" onclick="agregarAlCarrito()">
                                <i class="bi bi-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">2. Detalle de Venta</h5>
                </div>
                <div class="card-body">

                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                            <?php echo $mensaje; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="formVenta">

                        <div class="mb-3">
                            <label class="fw-bold">Cliente:</label>
                            <select name="cliente" class="form-select select2-simple" required>
                                <option value="">Seleccione Cliente...</option>
                                <?php foreach ($lista_clientes as $c): ?>
                                    <option value="<?php echo $c['id_cliente']; ?>">
                                        <?php echo $c['nombre'] . ' ' . $c['apellido']; ?>
                                        (<?php echo $c['dni_cuil']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="lista_productos" id="inputListaProductos">

                        <table class="table table-striped table-hover mt-3">
                            <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="cuerpoTabla">
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                <td colspan="2" class="fw-bold text-success" id="totalVenta">$0.00</td>
                            </tr>
                            </tfoot>
                        </table>

                        <button type="button" class="btn btn-success w-100 btn-lg mt-2" onclick="prepararVenta()">
                            <i class="bi bi-check-circle-fill"></i> Finalizar Venta
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 1. Inicializar Select2 (El buscador lindo)
    $(document).ready(function () {
        $('#selectProducto').select2({width: '100%'}); // Aplica al selector de productos
        $('.select2-simple').select2({width: '100%'}); // Aplica al selector de clientes
    });

    // 2. Lógica del Carrito (Array en Memoria)
    let carrito = [];

    function agregarAlCarrito() {
        // Obtenemos los valores del select y el input
        const select = document.getElementById('selectProducto');
        const cantidad = parseInt(document.getElementById('inputCantidad').value);

        // Validación básica
        if (select.value === "") {
            alert("Seleccioná un producto");
            return;
        }
        if (cantidad < 1) {
            alert("Cantidad inválida");
            return;
        }

        // Obtenemos datos extra del producto (data-attributes)
        const id = select.value;
        const nombre = select.options[select.selectedIndex].getAttribute('data-nombre');
        const precio = parseFloat(select.options[select.selectedIndex].getAttribute('data-precio'));
        const stock = parseInt(select.options[select.selectedIndex].getAttribute('data-stock'));

        // Validar Stock
        if (cantidad > stock) {
            alert("No hay suficiente stock. Disponible: " + stock);
            return;
        }

        // ¿Ya existe en el carrito?
        const existente = carrito.find(item => item.id === id);
        if (existente) {
            if (existente.cantidad + cantidad > stock) {
                alert("Superás el stock disponible al sumar.");
                return;
            }
            existente.cantidad += cantidad; // Sumamos cantidad
        } else {
            // Agregamos nuevo
            carrito.push({id, nombre, precio, cantidad});
        }

        actualizarTabla();
    }

    function eliminarDelCarrito(index) {
        carrito.splice(index, 1); // Borra 1 elemento en la posición index
        actualizarTabla();
    }

    function actualizarTabla() {
        const cuerpo = document.getElementById('cuerpoTabla');
        const totalElem = document.getElementById('totalVenta');
        const inputHidden = document.getElementById('inputListaProductos');

        cuerpo.innerHTML = ""; // Limpiar tabla visual
        let totalGeneral = 0;

        // Dibujamos el HTML de cada fila
        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            totalGeneral += subtotal;

            const fila = `
                <tr>
                    <td>${item.nombre}</td>
                    <td>${item.cantidad}</td>
                    <td>$${item.precio}</td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarDelCarrito(${index})"><i class="bi bi-x-circle-fill"></i></button>
                    </td>
                </tr>
            `;
            cuerpo.innerHTML += fila;
        });

        // Actualizamos el Total visual
        totalElem.innerText = "$" + totalGeneral.toFixed(2);

        // Actualizamos el input oculto que se envía a PHP (Magia JSON)
        inputHidden.value = JSON.stringify(carrito);
    }

    function validarCarrito() {
        if (carrito.length === 0) {
            alert("El carrito está vacío.");
            return false; // Evita que se envíe el formulario
        }
        return true;
    }


    function prepararVenta() {
        // 1. Llamamos a tu función de siempre.
        // Si validarCarrito() devuelve false (ej: carrito vacio), se detiene tod aca
        if (validarCarrito()) {
            // 2. Si pasó la validación, abrimos el modal manualmente
            var myModal = new bootstrap.Modal(document.getElementById('modalConfirmarVenta'));
            myModal.show();
        }
    }


</script>

<div class="modal fade" id="modalConfirmarVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-shield-lock"></i> Confirmar Transacción</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
<!--                <div class="mb-3">-->
<!--                    <span style="font-size: 3rem;">💸</span>-->
<!--                </div>-->
                <h4 class="text-success fw-bold">¿Realizar Venta?</h4>
                <p class="text-muted mb-0">Se descontará el stock y se registrará el ingreso en caja.</p>
                <p class="small text-secondary mt-2">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer justify-content-center bg-light">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>

                <button type="button" class="btn btn-success px-4 fw-bold"
                        onclick="document.getElementById('formVenta').submit();">
                    Sí, Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

</body>

</html>