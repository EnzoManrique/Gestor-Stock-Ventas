<?php
// models/Venta.php
require_once __DIR__ . '/../config/db.php';

class Venta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Este método es una papa: solo consulta la VISTA que ya tiene todo cocinado
    public function obtenerReporte($fecha_desde = null, $fecha_hasta = null, $usuario = null) {
        // SQL Base: Trae todo. Usamos '1=1' un truco para poder agregar 'AND' sin miedo.
        $sql = "SELECT * FROM reporte_ventas WHERE 1=1";
        $params = [];

        // Filtro: Fecha Desde
        if (!empty($fecha_desde)) {
            $sql .= " AND DATE(fecha) >= :desde"; // DATE() ignora la hora
            $params['desde'] = $fecha_desde;
        }

        // Filtro: Fecha Hasta
        if (!empty($fecha_hasta)) {
            $sql .= " AND DATE(fecha) <= :hasta";
            $params['hasta'] = $fecha_hasta;
        }

        // Filtro: Usuario/Vendedor
        if (!empty($usuario)) {
            // Como en la vista 'reporte_ventas' no tenemos el id_usuario limpio (solo el nombre concatenado),
            // lo ideal sería actualizar la vista SQL para incluir la columna 'id_usuario'.
            // PERO, para no complicarte, vamos a filtrar por el nombre del vendedor usando LIKE
            // O MEJOR: Vamos a actualizar la vista primero (ver abajo) para hacerlo bien.

            // Asumiendo que actualizamos la vista (Paso 0):
            $sql .= " AND id_usuario = :usr";
            $params['usr'] = $usuario;
        }

        $sql .= " ORDER BY fecha DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>