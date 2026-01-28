<?php
// controllers/ajax_buscar_productos_admin.php
require_once __DIR__ . '/../config/db.php';

// Desactivar errores visuales para no ensuciar el JSON
ini_set('display_errors', 0);
header('Content-Type: application/json');

$termino = isset($_POST['termino']) ? $_POST['termino'] : '';

try {
    // Si no hay término, devolvemos los primeros 20 (opcional, para que no quede vacío)
    if (empty($termino)) {
        // Podrías devolver nada o devolver todo.
        // Para que coincida con la carga inicial, devolvemos vacío o limitamos.
        // En este caso, si está vacío el JS maneja la limpieza,
        // pero por seguridad devolvemos un array vacío.
        echo json_encode([]);
        exit;
    }

    // CORRECCIÓN: Usamos :nom y :cat en lugar de repetir :t
    $sql = "SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
            WHERE p.nombre LIKE :nom OR c.nombre LIKE :cat 
            ORDER BY p.stock ASC";

    $busqueda = "%$termino%";

    $stmt = $pdo->prepare($sql);
    // Pasamos el mismo valor a los dos huecos
    $stmt->execute([
        'nom' => $busqueda,
        'cat' => $busqueda
    ]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>