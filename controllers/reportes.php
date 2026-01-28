<?php
// controllers/reportes.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Usuario.php'; // Necesitamos listar usuarios

if (!isset($_SESSION['usuario_id'])) { header('Location: auth.php'); exit; }

$ventaModel = new Venta($pdo);
$usuarioModel = new Usuario($pdo);

// 1. Recibimos los filtros (si existen)
$f_desde = isset($_GET['desde']) ? $_GET['desde'] : null;
$f_hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;
$f_usr   = isset($_GET['usuario']) ? $_GET['usuario'] : null;

// 2. Pedimos el reporte filtrado
$lista_ventas = $ventaModel->obtenerReporte($f_desde, $f_hasta, $f_usr);

// 3. Pedimos la lista de vendedores (para llenar el select del filtro)
// (Podés usar el metodo obtenerTodos de Usuario.php que hicimos antes)
$lista_usuarios = $usuarioModel->obtenerTodos();

require_once __DIR__ . '/../views/reportes.php';
?>