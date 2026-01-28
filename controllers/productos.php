<?php
// controllers/productos.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

if (!isset($_SESSION['usuario_id'])) { header('Location: auth.php'); exit; }

// SEGURIDAD EXTRA: (Comentada como pediste)
/*
if ($_SESSION['rol'] != 1) {
    header('Location: ../index.php'); // ¡Afuera!
    exit;
}*/

$productoModel = new Producto($pdo);
$mensaje = "";
$producto_editar = null;

// --- AQUÍ HABÍAS PEGADO LA FUNCIÓN POR ERROR. YA LA QUITAMOS. ---
// El controlador simplemente llama al modelo, no define las funciones.

// 1. ELIMINAR (BAJA LÓGICA)
if (isset($_GET['eliminar'])) {
    $productoModel->darDeBaja($_GET['eliminar']);
    $mensaje = "Producto dado de baja correctamente.";
}

// 2. REACTIVAR (Opcional, pero útil)
if (isset($_GET['activar'])) {
    $productoModel->reactivar($_GET['activar']);
    $mensaje = "Producto reactivado.";
}

// 3. PROCESAR FORMULARIO (CREAR O EDITAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $nombre = $_POST['nombre'];
    $costo  = $_POST['costo'];
    $venta  = $_POST['venta'];
    $stock  = $_POST['stock'];
    $cat    = $_POST['categoria'];

    if ($accion === 'crear') {
        $productoModel->crear($nombre, $costo, $venta, $stock, $cat);
        $mensaje = "Producto creado.";
    }
    elseif ($accion === 'editar') {
        $id = $_POST['id_producto'];
        $productoModel->actualizar($id, $nombre, $costo, $venta, $stock, $cat);
        $mensaje = "Producto actualizado.";
        $producto_editar = null;
    }
}

// 4. MODO EDICIÓN
if (isset($_GET['editar'])) {
    $producto_editar = $productoModel->obtenerPorId($_GET['editar']);
}

// 5. Obtener lista actualizada
// Fíjate que acá LLAMAMOS a la función del modelo, no la escribimos de nuevo
$lista_productos = $productoModel->obtenerTodos();

// 6. Cargar categorías para el select
$categoriaModel = new Categoria($pdo);
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'default';
$lista_productos = $productoModel->obtenerTodos($orden);

require_once __DIR__ . '/../views/productos_lista.php';
?>