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

    public function obtenerValorInventario() {
        try {
            // Ejecutamos la función como si fuera un SELECT normal
            $stmt = $this->pdo->query("SELECT fn_valor_inventario() as total");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Devolvemos el número (o 0 si viene vacío)
            return $resultado['total'] ?? 0;
        } catch (Exception $e) {
            return 0; // Si falla, decimos que hay $0 para no romper la web
        }
    }

    public function reportePorFechas($desde, $hasta) {
        try {
            // Llamada al SP con los dos parámetros
            $stmt = $this->pdo->prepare("CALL reporte_estadisticas_rango(?, ?)");
            $stmt->execute([$desde, $hasta]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Si falla, devolvemos ceros para que no rompa la vista
            return [
                'total_tickets' => 0,
                'total_facturado' => 0,
                'total_costo' => 0,
                'ganancia_neta' => 0
            ];
        }
    }

}
?>