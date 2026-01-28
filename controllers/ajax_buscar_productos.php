<?php
// controllers/ajax_buscar_productos.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Producto.php';

// Le decimos al navegador que le vamos a responder con JSON, no con HTML
header('Content-Type: application/json');

$termino = isset($_POST['termino']) ? $_POST['termino'] : '';

$productoModel = new Producto($pdo);

// Si el usuario no escribió nada, devolvemos todo. Si escribió, filtramos.
if (empty($termino)) {
    $resultados = $productoModel->obtenerActivos();
} else {
    $resultados = $productoModel->buscarActivos($termino);
}

// Convertimos el array de PHP a texto JSON (que entiende JavaScript)
echo json_encode($resultados);
?>