<?php
// 1. Incluimos el archivo de conexión (como un Import)
require 'db.php';

try {
    // 2. Preparamos la consulta SQL (usamos tu VISTA)
    $stmt = $pdo->query("SELECT * FROM reporte_ventas");

    // 3. Obtenemos los datos
    echo "<h1>Probando conexión a la Base de Datos</h1>";

    // Si hay ventas, las mostramos
    while ($row = $stmt->fetch()) {
        echo "<p>";
        echo "<strong>Fecha:</strong> " . $row['fecha'] . "<br>";
        echo "<strong>Cliente:</strong> " . $row['cliente'] . "<br>";
        echo "<strong>Productos:</strong> " . $row['productos_vendidos'] . "<br>";
        echo "<strong>Total:</strong> $" . $row['total'];
        echo "</p><hr>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>