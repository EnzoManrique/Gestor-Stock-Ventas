<?php
// controllers/ajax_buscar_clientes.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cliente.php';

// Desactivamos errores visuales para que no ensucien el JSON, pero los registramos
ini_set('display_errors', 0);
header('Content-Type: application/json');

$termino = isset($_POST['termino']) ? $_POST['termino'] : '';

try {
    if (empty($termino)) {
        // Traer los primeros 20 si no hay búsqueda
        $stmt = $pdo->query("SELECT * FROM clientes WHERE activo = 1 ORDER BY apellido ASC LIMIT 20");
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // CORRECCIÓN: Usamos parámetros únicos para cada campo
        $sql = "SELECT * FROM clientes 
                WHERE activo = 1 AND (
                    nombre   LIKE :nom OR 
                    apellido LIKE :ape OR 
                    dni_cuil LIKE :dni OR 
                    email    LIKE :email
                )
                ORDER BY apellido ASC";

        $busqueda = "%$termino%"; // Preparamos el término con los %

        $stmt = $pdo->prepare($sql);
        // Le pasamos el mismo término 4 veces, uno para cada hueco
        $stmt->execute([
            'nom'   => $busqueda,
            'ape'   => $busqueda,
            'dni'   => $busqueda,
            'email' => $busqueda
        ]);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($resultados);

} catch (Exception $e) {
    // Si hay error, devolvemos un JSON con el error para verlo en consola
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>