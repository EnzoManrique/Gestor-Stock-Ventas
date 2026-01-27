<?php
// controllers/ventas.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Producto.php';

// Verificamos si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: auth.php');
    exit;
}

$mensaje = "";
$tipo_mensaje = "";

// LOGICA POST: Si el usuario envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $id_cliente = $_POST['cliente'];
    $id_producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];

    try {
        // Llamamos al PROCEDIMIENTO ALMACENADO que creamos en MySQL
        // Este se encarga de la transacción, validación de stock y rollback
        $stmt = $pdo->prepare("CALL registrar_venta_simple(:usr, :cli, :prod, :cant)");
        $stmt->execute([
            'usr'  => $id_usuario,
            'cli'  => $id_cliente,
            'prod' => $id_producto,
            'cant' => $cantidad
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        // Si el SP devuelve mensaje de éxito o error
        if (strpos($resultado['mensaje'], 'Error') !== false) {
            $mensaje = $resultado['mensaje'];
            $tipo_mensaje = "danger";
        } else {
            $mensaje = "¡Venta registrada con éxito!";
            $tipo_mensaje = "success";
        }

    } catch (PDOException $e) {
        $mensaje = "Error del sistema: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

// LOGICA GET: Preparar los datos para la vista
$clienteModel = new Cliente($pdo);
$lista_clientes = $clienteModel->obtenerTodos();

$productoModel = new Producto($pdo);
$lista_productos = $productoModel->obtenerActivos();

// Cargamos la vista
require_once __DIR__ . '/../views/nueva_venta.php';
?>