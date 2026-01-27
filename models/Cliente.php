<?php
// models/Cliente.php
require_once __DIR__ . '/../config/db.php';

class Cliente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar TODOS (Para el ABM, incluye inactivos)
    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM clientes ORDER BY activo DESC, apellido ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar SOLO ACTIVOS (Para el select de Nueva Venta)
    public function obtenerActivos() {
        $stmt = $this->pdo->query("SELECT * FROM clientes WHERE activo = 1 ORDER BY apellido ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID (Para editar)
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id_cliente = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear
    public function crear($nombre, $apellido, $dni, $tel, $email) {
        $sql = "INSERT INTO clientes (nombre, apellido, dni_cuil, telefono, email, activo) 
                VALUES (:nom, :ape, :dni, :tel, :email, 1)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $nombre, 'ape' => $apellido, 'dni' => $dni, 'tel' => $tel, 'email' => $email
        ]);
    }

    // Actualizar
    public function actualizar($id, $nombre, $apellido, $dni, $tel, $email) {
        $sql = "UPDATE clientes SET 
                nombre = :nom, apellido = :ape, dni_cuil = :dni, telefono = :tel, email = :email 
                WHERE id_cliente = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $nombre, 'ape' => $apellido, 'dni' => $dni, 'tel' => $tel, 'email' => $email, 'id' => $id
        ]);
    }

    // Baja Lógica
    public function darDeBaja($id) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET activo = 0 WHERE id_cliente = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Reactivar
    public function reactivar($id) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET activo = 1 WHERE id_cliente = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>