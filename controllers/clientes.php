<?php
// controllers/clientes.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cliente.php';

// Seguridad: Si no está logueado, afuera.
if (!isset($_SESSION['usuario_id'])) { header('Location: auth.php'); exit; }

$clienteModel = new Cliente($pdo);
$mensaje = "";
$cliente_editar = null;

// 1. ELIMINAR / REACTIVAR
if (isset($_GET['eliminar'])) {
    $clienteModel->darDeBaja($_GET['eliminar']);
    $mensaje = "Cliente dado de baja.";
}
if (isset($_GET['activar'])) {
    $clienteModel->reactivar($_GET['activar']);
    $mensaje = "Cliente reactivado.";
}

// 2. GUARDAR (Crear o Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $tel = $_POST['telefono'];
    $email = $_POST['email'];

    try {
        if ($accion === 'crear') {
            $clienteModel->crear($nombre, $apellido, $dni, $tel, $email);
            $mensaje = "Cliente registrado correctamente.";
        } elseif ($accion === 'editar') {
            $id = $_POST['id_cliente'];
            $clienteModel->actualizar($id, $nombre, $apellido, $dni, $tel, $email);
            $mensaje = "Cliente actualizado.";
            $cliente_editar = null;
        }
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// 3. CARGAR DATOS PARA EDITAR
if (isset($_GET['editar'])) {
    $cliente_editar = $clienteModel->obtenerPorId($_GET['editar']);
}

// 4. OBTENER LISTA
$lista_clientes = $clienteModel->obtenerTodos();

require_once __DIR__ . '/../views/clientes_lista.php';
?>