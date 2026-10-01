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

$error = '';

/*
========================================================
TOKEN CSRF PARA EL FORMULARIO DE LOGIN
========================================================
*/

if (
    empty($_SESSION['login_csrf_token'])
) {
    $_SESSION['login_csrf_token'] =
        bin2hex(
            random_bytes(32)
        );
}

/*
========================================================
PROCESAR LOGIN
========================================================
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../config/database.php';

    $correo =
        strtolower(
            trim(
                $_POST['correo']
                ?? ''
            )
        );

    $password =
        $_POST['password']
        ?? '';

    $csrfToken =
        $_POST['csrf_token']
        ?? '';

    /*
    ====================================================
    VALIDAR TOKEN CSRF
    ====================================================
    */

    if (
        !hash_equals(
            $_SESSION['login_csrf_token'],
            $csrfToken
        )
    ) {

        $error =
            'No fue posible procesar el inicio de sesión. Inténtalo nuevamente.';

    } else {

        /*
        ====================================================
        VALIDACIONES BÁSICAS
        ====================================================
        */

        if (
            $correo === '' ||
            $password === '' ||
            !filter_var(
                $correo,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $error =
                'Correo o contraseña incorrectos.';

        } else {

            /*
            ====================================================
            CONSULTAR USUARIO
            ====================================================
            */

            $sql = "
                SELECT
                    id,
                    nombre,
                    correo,
                    password,
                    rol,
                    estado
                FROM usuarios
                WHERE correo = :correo
                LIMIT 1
            ";

            $stmt =
                $pdo->prepare($sql);

            $stmt->execute([
                ':correo' =>
                    $correo
            ]);

            $usuario =
                $stmt->fetch();

            /*
            ====================================================
            VALIDAR CREDENCIALES Y ESTADO
            ====================================================
            */

            if (
                $usuario &&
                (int)$usuario['estado'] === 1 &&
                password_verify(
                    $password,
                    $usuario['password']
                )
            ) {

                /*
                ================================================
                PREVENIR FIJACIÓN DE SESIÓN
                ================================================
                */

                session_regenerate_id(true);

                /*
                ================================================
                DATOS DE SESIÓN
                ================================================
                */

                $_SESSION['usuario_id'] =
                    (int)$usuario['id'];

                $_SESSION['usuario_nombre'] =
                    $usuario['nombre'];

                $_SESSION['usuario_rol'] =
                    $usuario['rol'];

                /*
                ================================================
                ROTAR TOKEN CSRF DE LOGIN
                ================================================
                */

                unset(
                    $_SESSION['login_csrf_token']
                );

                header(
                    'Location: dashboard.php'
                );

                exit;
            }

            /*
            ====================================================
            MENSAJE GENÉRICO
            No revelar si falló correo, contraseña o estado.
            ====================================================
            */

            $error =
                'Correo o contraseña incorrectos.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Administrador | Biblioteca Tenjo
    </title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="assets/css/admin.css?v=20260902-2"
    >

    <style>

        .admin-login-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 30px 18px;
            background:
                linear-gradient(
                    135deg,
                    rgba(7, 91, 54, 0.08),
                    rgba(212, 179, 0, 0.08)
                ),
                #f6f8f7;
        }

        .admin-login-box {
            width: min(100%, 440px);
        }

        .admin-login-brand {
            margin-bottom: 22px;
            text-align: center;
        }

        .admin-login-brand img {
            width: 120px;
            max-height: 82px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .admin-login-brand h1 {
            margin: 0;
            color: #075b36;
            font-size: 1.35rem;
            line-height: 1.3;
        }

        .admin-login-brand p {
            margin: 6px 0 0;
            color: #68726e;
            font-size: 0.82rem;
        }

        .admin-login-card {
            padding: 28px;
            border: 1px solid #dfe7e2;
            border-radius: 18px;
            background: #fff;
            box-shadow:
                0 16px 45px rgba(25, 45, 35, 0.08);
        }

        .admin-login-heading {
            margin-bottom: 22px;
        }

        .admin-login-heading h2 {
            margin: 0 0 6px;
            color: #29342f;
            font-size: 1.1rem;
        }

        .admin-login-heading p {
            margin: 0;
            color: #68726e;
            font-size: 0.8rem;
        }

        .admin-login-form-group {
            margin-bottom: 17px;
        }

        .admin-login-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #29342f;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .admin-login-input-wrap {
            position: relative;
        }

        .admin-login-input-wrap i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #718078;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .admin-login-input-wrap input {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px 11px 39px;
            border: 1px solid #cad4ce;
            border-radius: 10px;
            background: #fff;
            color: #29342f;
            font: inherit;
        }

        .admin-login-input-wrap input:focus {
            outline: none;
            border-color: #d4b300;
            box-shadow:
                0 0 0 3px rgba(212, 179, 0, 0.14);
        }

        .admin-login-error {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #fdecec;
            color: #9d2424;
            font-size: 0.78rem;
            line-height: 1.45;
        }

        .admin-login-submit {
            width: 100%;
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 10px;
            background: #075b36;
            color: #fff;
            font: inherit;
            font-size: 0.82rem;
            font-weight: 800;
            cursor: pointer;
            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .admin-login-submit:hover {
            background: #044828;
            transform: translateY(-1px);
        }

        .admin-login-footer {
            margin-top: 18px;
            color: #7b8580;
            font-size: 0.7rem;
            text-align: center;
        }

    </style>

</head>

<body>

<div class="admin-login-page">

    <div class="admin-login-box">

        <div class="admin-login-brand">

            <img
                src="/bibliotecatenjo/public/assets/img/logobiblio.png"
                alt="Biblioteca Municipal Isabel Murillo de Luque"
            >

            <h1>
                Biblioteca Municipal
                Isabel Murillo de Luque
            </h1>

            <p>
                Tenjo - Cundinamarca
            </p>

        </div>


        <section class="admin-login-card">

            <div class="admin-login-heading">

                <h2>
                    Panel Administrativo
                </h2>

                <p>
                    Ingresa con tu cuenta autorizada.
                </p>

            </div>


            <?php if ($error !== ''): ?>

                <div class="admin-login-error">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    <span>
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                autocomplete="on"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $_SESSION['login_csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >


                <div class="admin-login-form-group">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <div class="admin-login-input-wrap">

                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            maxlength="150"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>


                <div class="admin-login-form-group">

                    <label for="password">
                        Contraseña
                    </label>

                    <div class="admin-login-input-wrap">

                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    class="admin-login-submit"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ingresar
                </button>

            </form>

        </section>


        <div class="admin-login-footer">
            Acceso exclusivo para personal autorizado.
        </div>

    </div>

</div>

</body>

</html>