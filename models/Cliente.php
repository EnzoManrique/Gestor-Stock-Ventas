<?php
// models/Cliente.php
require_once __DIR__ . '/../config/db.php';

class Cliente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM clientes ORDER BY apellido");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>