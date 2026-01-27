<?php
// models/Usuario.php
require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Login (Ya lo tenías)
    public function obtenerPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // REGISTRAR (Ya lo tenías, agregamos 'activo')
    public function registrar($nombre, $apellido, $email, $password, $id_rol) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, id_rol, activo) 
                VALUES (:nombre, :apellido, :email, :pass, :rol, 1)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $nombre, 'apellido' => $apellido, 'email' => $email, 'pass' => $hash, 'rol' => $id_rol
        ]);
    }

    // --- NUEVOS MÉTODOS PARA CRUD ---

    // Listar todos
    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY activo DESC, apellido ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID (Para editar)
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar Usuario
    public function actualizar($id, $nombre, $apellido, $email, $rol, $password = null) {
        // Si mandaron contraseña nueva, la actualizamos. Si no, dejamos la vieja.
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre=:nom, apellido=:ape, email=:email, id_rol=:rol, password=:pass WHERE id_usuario=:id";
            $params = ['nom'=>$nombre, 'ape'=>$apellido, 'email'=>$email, 'rol'=>$rol, 'pass'=>$hash, 'id'=>$id];
        } else {
            $sql = "UPDATE usuarios SET nombre=:nom, apellido=:ape, email=:email, id_rol=:rol WHERE id_usuario=:id";
            $params = ['nom'=>$nombre, 'ape'=>$apellido, 'email'=>$email, 'rol'=>$rol, 'id'=>$id];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Baja Lógica
    public function darDeBaja($id) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Reactivar
    public function reactivar($id) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET activo = 1 WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>