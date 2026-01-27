<?php
// models/Dashboard.php
require_once __DIR__ . '/../config/db.php';

class Dashboard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1. Total vendido HOY (Usa función de fecha de MySQL)
    public function totalVentasHoy() {
        // COALESCE es para que si devuelve NULL (nadie vendió nada), devuelva 0
        $sql = "SELECT COALESCE(SUM(total), 0) as total 
                FROM ventas 
                WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 2. Cantidad de ventas HOY
    public function cantidadVentasHoy() {
        $sql = "SELECT COUNT(*) as cantidad 
                FROM ventas 
                WHERE DATE(fecha) = CURDATE()";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cantidad'];
    }

    // 3. Productos con stock CRÍTICO (Menos de 5 unidades)
    public function productosBajoStock() {
        $sql = "SELECT COUNT(*) as cantidad 
                FROM productos 
                WHERE stock < 5 AND activo = 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['cantidad'];
    }
}
?>