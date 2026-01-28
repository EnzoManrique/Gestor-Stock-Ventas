<?php
// models/Categoria.php
require_once __DIR__ . '/../config/db.php';

class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        return $stmt->execute(['nombre' => $nombre]);
    }

    // Opcional: Eliminar categoría (si quisieras borrar una mal cargada)
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id_categoria = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>