<?php
// controllers/usuarios.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Usuario.php';

// SEGURIDAD: Solo Admin (Rol 1)
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 1) {
    header('Location: ../index.php');
    exit;
}

$usuarioModel = new Usuario($pdo);
$mensaje = "";
$usuario_editar = null;

// 1. ELIMINAR / REACTIVAR
if (isset($_GET['eliminar'])) {
    // Evitar auto-eliminarse
    if ($_GET['eliminar'] == $_SESSION['usuario_id']) {
        $mensaje = "Error: No podés eliminar tu propia cuenta.";
    } else {
        $usuarioModel->darDeBaja($_GET['eliminar']);
        $mensaje = "Usuario desactivado.";
    }
}
if (isset($_GET['activar'])) {
    $usuarioModel->reactivar($_GET['activar']);
    $mensaje = "Usuario reactivado.";
}

// 2. GUARDAR (Crear o Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion   = $_POST['accion'];
    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email    = $_POST['email'];
    $rol      = $_POST['rol'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    try {
        if ($accion === 'crear') {
            if ($password) {
                $usuarioModel->registrar($nombre, $apellido, $email, $password, $rol);
                $mensaje = "Usuario creado con éxito.";
            } else {
                $mensaje = "Error: La contraseña es obligatoria para crear.";
            }
        }
        elseif ($accion === 'editar') {
            $id = $_POST['id_usuario'];
            $usuarioModel->actualizar($id, $nombre, $apellido, $email, $rol, $password);
            $mensaje = "Usuario actualizado.";
            $usuario_editar = null; // Limpiar form
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mensaje = "Error: El email ya está en uso.";
        } else {
            $mensaje = "Error DB: " . $e->getMessage();
        }
    }
}

// 3. MODO EDICIÓN
if (isset($_GET['editar'])) {
    $usuario_editar = $usuarioModel->obtenerPorId($_GET['editar']);
}

// 4. LISTAR
$lista_usuarios = $usuarioModel->obtenerTodos();

// Cargar la vista (cambiamos el nombre del archivo para seguir el estándar)
require_once __DIR__ . '/../views/usuarios_lista.php';
?>