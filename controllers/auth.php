<?php
// controllers/auth.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';

// Si ya está logueado, lo mandamos al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Procesar el formulario de Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $usuarioModel = new Usuario($pdo);
    $usuario = $usuarioModel->obtenerPorEmail($email);

    // Verificamos si existe el usuario y si la contraseña coincide con el Hash
    if ($usuario && password_verify($password, $usuario['password'])) {
        // ¡Login Exitoso! Guardamos datos en sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
        $_SESSION['rol'] = $usuario['id_rol'];

        header('Location: ../index.php'); // Redirigir al inicio
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}

// Cargar la vista
require_once __DIR__ . '/../views/login.php';
?>