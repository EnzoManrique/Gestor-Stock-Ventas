<?php
// controllers/usuarios.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';

// 1. SEGURIDAD: Verificar Login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: auth.php');
    exit;
}

// 2. SEGURIDAD DE ROLES: Solo Admin (Rol 1) puede estar acá
if ($_SESSION['rol'] != 1) {
    // Si es empleado y quiere entrar, lo mandamos al index con un reto
    header('Location: ../index.php');
    exit;
}

$mensaje = "";
$usuarioModel = new Usuario($pdo);

// 3. PROCESAR EL ALTA DE USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email    = $_POST['email'];
    $password = $_POST['password']; // La contraseña se hashea en el Modelo
    $rol      = $_POST['rol'];      // 1=Admin, 2=Empleado

    try {
        if ($usuarioModel->registrar($nombre, $apellido, $email, $password, $rol)) {
            $mensaje = "Usuario registrado con éxito.";
        } else {
            $mensaje = "Error al registrar.";
        }
    } catch (PDOException $e) {
        // Error típico: El email ya existe (Violación de restricción UNIQUE)
        if ($e->getCode() == 23000) {
            $mensaje = "Error: Ese email ya está registrado.";
        } else {
            $mensaje = "Error de base de datos: " . $e->getMessage();
        }
    }
}

// Cargar la vista
require_once __DIR__ . '/../views/registro.php';
?>