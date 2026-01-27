<?php
// controllers/productos.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Producto.php';

if (!isset($_SESSION['usuario_id'])) { header('Location: auth.php'); exit; }

$productoModel = new Producto($pdo);
$mensaje = "";
$producto_editar = null; // Variable para guardar los datos si estamos editando

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
        // Limpiamos la variable para volver al modo "Crear"
        $producto_editar = null;
    }
}

// 4. MODO EDICIÓN: Si hacen clic en "Editar", cargamos los datos en el formulario
if (isset($_GET['editar'])) {
    $producto_editar = $productoModel->obtenerPorId($_GET['editar']);
}

// 5. Obtener lista actualizada
$lista_productos = $productoModel->obtenerTodos();

require_once __DIR__ . '/../views/productos_lista.php';
?>