<?php
// controllers/estadisticas.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Estadistica.php';

// Solo admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 1) {
    header('Location: ../index.php');
    exit;
}

$statsModel = new Estadistica($pdo);

$ventas_mes = $statsModel->ventasPorMes();
$top_productos = $statsModel->productosMasVendidos();
$valor_inventario = $statsModel->obtenerValorInventario();

// Preparamos arrays para ChartJS
$labels_mes = [];
$data_mes = [];
foreach($ventas_mes as $v) {
    $labels_mes[] = ucfirst($v['mes']); // Enero 2024
    $data_mes[] = $v['total'];
}

$labels_prod = [];
$data_prod = [];
foreach($top_productos as $p) {
    $labels_prod[] = $p['nombre'];
    $data_prod[] = $p['cantidad'];
}

require_once __DIR__ . '/../views/estadisticas_dashboard.php';
?>