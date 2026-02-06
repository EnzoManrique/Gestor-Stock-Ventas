<?php
// models/Categoria.php
require_once __DIR__ . '/../config/db.php';

class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nombre ASC");
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

    // Metodo para el desplegable de productos (Solo activas)
    public function obtenerActivas() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 1. Obtener una sola categoría por su ID (Para llenar el input al editar)
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id_categoria = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Guardar los cambios (UPDATE)
    public function actualizar($id, $nombre) {
        $stmt = $this->pdo->prepare("UPDATE categorias SET nombre = :nombre WHERE id_categoria = :id");
        return $stmt->execute(['nombre' => $nombre, 'id' => $id]);
    }

} // <--- FIN DE LA CLASE
?>