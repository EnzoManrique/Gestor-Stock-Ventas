<?php
// models/Estadistica.php
require_once __DIR__ . '/../config/db.php';

class Estadistica {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1. Ventas por mes (Últimos 6 meses)
    public function ventasPorMes() {
        // Configuramos idioma español para los meses
        $this->pdo->query("SET lc_time_names = 'es_ES'");

        // CORRECCIÓN:
        // 1. Usamos MIN(fecha) en el SELECT para satisfacer only_full_group_by
        // 2. Usamos MIN(fecha) en el ORDER BY por la misma razón
        $sql = "SELECT 
                    DATE_FORMAT(MIN(fecha), '%M %Y') as mes, 
                    SUM(total) as total 
                FROM ventas 
                WHERE fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(fecha, '%Y-%m') 
                ORDER BY MIN(fecha) ASC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Top 5 Productos más vendidos
    public function productosMasVendidos() {
        $sql = "SELECT 
                    p.nombre, 
                    SUM(d.cantidad) as cantidad 
                FROM detalle_venta d
                JOIN productos p ON d.id_producto = p.id_producto
                GROUP BY d.id_producto
                ORDER BY cantidad DESC
                LIMIT 5";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>