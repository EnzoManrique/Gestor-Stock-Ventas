<?php
require 'config/db.php';
// Contraseña que querés usar
$pass_real = 'admin123';
// La encriptamos
$hash = password_hash($pass_real, PASSWORD_DEFAULT);

// Actualizamos al usuario ID 1 (Juan Pérez)
$sql = "UPDATE usuarios SET password = :hash WHERE id_usuario = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['hash' => $hash]);

echo "Contraseña actualizada! Ahora el usuario 1 tiene pass encriptada.";
?>
