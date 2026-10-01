<?php

require_once __DIR__ . '/config/database.php';

$nombre = 'Administrador Biblioteca';
$correo = 'admin@bibliotecatenjo.local';
$password = password_hash('Admin123*', PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, correo, password)
        VALUES (:nombre, :correo, :password)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nombre' => $nombre,
    ':correo' => $correo,
    ':password' => $password
]);

echo 'Usuario administrador creado correctamente';