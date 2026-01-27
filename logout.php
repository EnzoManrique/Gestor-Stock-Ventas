<?php
session_start();
session_destroy(); // Destruye la sesión
// Redirige usando la ruta absoluta también para evitar errores
header('Location: /gestion_ventas/controllers/auth.php');
exit;
?>