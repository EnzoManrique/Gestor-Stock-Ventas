<?php
// models/Venta.php
require_once __DIR__ . '/../config/db.php';

class Venta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Este método es una papa: solo consulta la VISTA que ya tiene todo cocinado
    public function obtenerReporte() {
        try {
            // Consultamos la vista reporte_ventas
            $stmt = $this->pdo->query("SELECT * FROM reporte_ventas");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return []; // Si falla, devolvemos array vacío
        }
    }
}
?>