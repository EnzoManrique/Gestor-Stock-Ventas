<?php
// models/Usuario.php
require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para buscar usuario por email
    public function obtenerPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para registrar usuario (útil para crear el primer admin con pass encriptada)
    public function registrar($nombre, $apellido, $email, $password, $id_rol) {
        // Encriptamos la contraseña con BCRYPT (Estándar de seguridad)
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, id_rol) 
                VALUES (:nombre, :apellido, :email, :pass, :rol)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'pass' => $hash,
            'rol' => $id_rol
        ]);
    }
}
?>