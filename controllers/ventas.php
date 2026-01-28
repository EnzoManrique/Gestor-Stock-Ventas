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
// ... (Validaciones de sesión iniciales) ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recibimos el cliente y la LISTA de productos (JSON)
    $id_usuario = $_SESSION['usuario_id'];
    $id_cliente = $_POST['cliente'];
    $lista_items_json = $_POST['lista_productos']; // Esto viene del input hidden

    // Convertimos el texto JSON a un Array de PHP
    $lista_items = json_decode($lista_items_json, true);

    if (empty($lista_items)) {
        $mensaje = "Error: El carrito está vacío.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // INICIO TRANSACCIÓN (Todo o nada)
            $pdo->beginTransaction();

            // 1. Insertamos la CABECERA de la venta (Una sola vez)
            $stmt = $pdo->prepare("INSERT INTO ventas (id_usuario, id_cliente, fecha) VALUES (:usr, :cli, NOW())");
            $stmt->execute(['usr' => $id_usuario, 'cli' => $id_cliente]);
            $id_venta = $pdo->lastInsertId();

            // 2. Recorremos el carrito e insertamos cada DETALLE
            $stmtDetalle = $pdo->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (:idv, :idp, :cant, :prec, 0)");

            // Buscamos el precio actual de cada producto para asegurar integridad
            $stmtPrecio = $pdo->prepare("SELECT precio_venta FROM productos WHERE id_producto = :id");

            foreach ($lista_items as $item) {
                // Buscamos precio real de la base de datos (por seguridad, no confiar en el JS)
                $stmtPrecio->execute(['id' => $item['id']]);
                $precio_real = $stmtPrecio->fetchColumn();

                $stmtDetalle->execute([
                    'idv' => $id_venta,
                    'idp' => $item['id'],
                    'cant' => $item['cantidad'],
                    'prec' => $precio_real
                ]);
            }

            // Si llegamos acá, todo salió bien
            $pdo->commit();
            $mensaje = "¡Venta registrada con éxito! (Ticket #$id_venta)";
            $tipo_mensaje = "success";

        } catch (Exception $e) {
            $pdo->rollBack();
            $mensaje = "Error al registrar venta: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}

// ... (El resto del código GET para cargar listas sigue igual) ...

// LOGICA GET: Preparar los datos para la vista
// ... (Lógica del POST de venta sigue igual) ...
$clienteModel = new Cliente($pdo);
$lista_clientes = $clienteModel->obtenerActivos(); // Asegurate de usar obtenerActivos acá también

$productoModel = new Producto($pdo);

// --- LÓGICA DEL BUSCADOR ---
$busqueda = ""; // Variable para recordar qué buscó
if (isset($_GET['q']) && !empty($_GET['q'])) {
    // Si hay búsqueda, filtramos
    $busqueda = $_GET['q'];
    $lista_productos = $productoModel->buscarActivos($busqueda);
} else {
    // Si no, mostramos todos los disponibles
    $lista_productos = $productoModel->obtenerActivos();
}

// Cargamos la vista
require_once __DIR__ . '/../views/nueva_venta.php';
?>