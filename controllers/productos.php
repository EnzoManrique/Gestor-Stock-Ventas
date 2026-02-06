<?php
// controllers/productos.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: auth.php');
    exit;
}

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
    if ($_SESSION['rol'] == 1) { // <--- CANDADO DE SEGURIDAD
        $productoModel->darDeBaja($_GET['eliminar']);
        $mensaje = "Producto dado de baja correctamente.";
    } else {
        $mensaje = "No tienes permiso para eliminar productos.";
    }
}

// 2. REACTIVAR (Opcional, pero útil)
if (isset($_GET['activar'])) {
    if ($_SESSION['rol'] == 1) {
        $productoModel->reactivar($_GET['activar']);
        $mensaje = "Producto reactivado.";
    } else {
        $mensaje = "No tienes permiso para activar productos";
    }
}

// 3. PROCESAR FORMULARIO (CREAR O EDITAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $nombre = $_POST['nombre'];
    $costo = $_POST['costo'];
    $venta = $_POST['venta'];
    $stock = $_POST['stock'];
    $cat = $_POST['categoria'];

    if ($accion === 'crear') {
        $productoModel->crear($nombre, $costo, $venta, $stock, $cat);
        $mensaje = "Producto creado.";
    } elseif ($accion === 'editar') {
        if($_SESSION['rol']==1){
            $id = $_POST['id_producto'];
            $productoModel->actualizar($id, $nombre, $costo, $venta, $stock, $cat);
            $mensaje = "Producto actualizado.";
            $producto_editar = null;
        }else{
            $mensaje = "No tienes permiso para editar un producto";
        }
    }
}

// 4. MODO EDICIÓN
if (isset($_GET['editar'])) {
    if($_SESSION['rol']==1){
        $producto_editar = $productoModel->obtenerPorId($_GET['editar']);
    }
}

// 5. Obtener lista actualizada

// ... (El resto del código de arriba queda igual) ...

// 5. Cargar categorías para el select (ESTO ES LO QUE FALTABA)
$categoriaModel = new Categoria($pdo);
// Usamos el metodo que agregaste al modelo. Si da error, asegurate de haber actualizado models/Categoria.php
$lista_categorias_bd = $categoriaModel->obtenerActivas();

// 6. Obtener lista de productos actualizada (Con ordenamiento)
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'default';
$lista_productos = $productoModel->obtenerTodos($orden);

require_once __DIR__ . '/../views/productos_lista.php';
?>
