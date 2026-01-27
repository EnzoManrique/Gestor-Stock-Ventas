<?php
// controllers/reportes.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Venta.php';

// Seguridad: Si no está logueado, afuera
if (!isset($_SESSION['usuario_id'])) {
    header('Location: auth.php');
    exit;
}

// 1. Instanciamos el modelo
$ventaModel = new Venta($pdo);

// 2. Pedimos la lista de ventas
$lista_ventas = $ventaModel->obtenerReporte();

// 3. Mostramos la vista
require_once __DIR__ . '/../views/reportes.php';
?>