<?php
// models/Producto.php
require_once __DIR__ . '/../config/db.php';

class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerActivos() {
        // Solo traemos productos que tengan stock positivo para evitar errores obvios
        $stmt = $this->pdo->query("SELECT * FROM productos WHERE stock > 0 ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>