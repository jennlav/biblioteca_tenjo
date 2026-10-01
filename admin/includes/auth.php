<?php

/*
========================================================
CONFIGURACIÓN SEGURA DE SESIÓN
========================================================
*/

$usaHttps =
    !empty($_SERVER['HTTPS']) &&
    $_SERVER['HTTPS'] !== 'off';

session_set_cookie_params([
    'httponly' => true,
    'secure' => $usaHttps,
    'samesite' => 'Lax'
]);

session_start();

/*
========================================================
VALIDAR SESIÓN
========================================================
*/

if (
    !isset($_SESSION['usuario_id']) ||
    !is_numeric($_SESSION['usuario_id'])
) {

    session_unset();
    session_destroy();

    header(
        'Location: /bibliotecatenjo/admin/login.php'
    );

    exit;
}

/*
========================================================
VALIDAR USUARIO ACTIVO EN BASE DE DATOS
========================================================

Esto evita que un usuario que ya tenía una sesión abierta
pueda seguir usando el panel después de ser desactivado.
========================================================
*/

require_once __DIR__ . '/../../config/database.php';

$stmt =
    $pdo->prepare("
        SELECT
            id,
            nombre,
            rol,
            estado
        FROM usuarios
        WHERE id = :id
        LIMIT 1
    ");

$stmt->execute([
    ':id' =>
        (int)$_SESSION['usuario_id']
]);

$usuarioSesion =
    $stmt->fetch();

if (
    !$usuarioSesion ||
    (int)$usuarioSesion['estado'] !== 1
) {

    $_SESSION = [];

    if (
        ini_get('session.use_cookies')
    ) {

        $params =
            session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header(
        'Location: /bibliotecatenjo/admin/login.php'
    );

    exit;
}

/*
========================================================
SINCRONIZAR DATOS DE SESIÓN
========================================================

Si el nombre o rol cambia desde el módulo Usuarios,
la cabecera del panel reflejará el dato actualizado.
========================================================
*/

$_SESSION['usuario_nombre'] =
    $usuarioSesion['nombre'];

$_SESSION['usuario_rol'] =
    $usuarioSesion['rol'];