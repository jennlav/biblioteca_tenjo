<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$id =
    isset($_GET['id'])
        ? (int)$_GET['id']
        : 0;

if ($id <= 0) {
    die('Mensaje no válido.');
}

/*
========================================================
ACTUALIZAR ESTADO
========================================================
*/

$mensajeExito = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nuevoEstado =
        strtolower(
            trim(
                $_POST['estado']
                ?? ''
            )
        );

    $estadosPermitidos = [
        'nuevo',
        'revisado',
        'atendido'
    ];

    if (
        !in_array(
            $nuevoEstado,
            $estadosPermitidos,
            true
        )
    ) {

        $error =
            'El estado seleccionado no es válido.';

    } else {

        $sql =
            "UPDATE contactos
             SET estado = :estado
             WHERE id = :id";

        $stmt =
            $pdo->prepare($sql);

        $stmt->execute([
            ':estado' =>
                $nuevoEstado,

            ':id' =>
                $id
        ]);

        $mensajeExito =
            'El estado del mensaje fue actualizado correctamente.';
    }
}

/*
========================================================
CONSULTAR MENSAJE
========================================================
*/

$sql =
    "SELECT
        id,
        nombre,
        correo,
        telefono,
        asunto,
        mensaje,
        estado,
        ip_origen,
        user_agent,
        correo_enviado,
        created_at,
        updated_at
     FROM contactos
     WHERE id = :id
     LIMIT 1";

$stmt =
    $pdo->prepare($sql);

$stmt->execute([
    ':id' =>
        $id
]);

$contacto =
    $stmt->fetch();

if (!$contacto) {
    die('Mensaje no encontrado.');
}

$estadoContacto =
    strtolower(
        trim(
            $contacto['estado']
            ?? 'nuevo'
        )
    );

if ($estadoContacto === '') {
    $estadoContacto = 'nuevo';
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
        Detalle del mensaje | Biblioteca Tenjo
    </title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/admin.css?v=20260901-2"
    >

    <link
        rel="stylesheet"
        href="../assets/css/admin-modulos.css?v=20260902-2"
    >

</head>

<body>

<div class="admin-shell">

<header class="admin-header">

    <div class="admin-header-inner">

        <a
            href="../dashboard.php"
            class="admin-brand"
            style="text-decoration:none;"
        >

            <img
                src="/bibliotecatenjo/public/assets/img/logobiblio.png"
                alt="Biblioteca Municipal Isabel Murillo de Luque"
                class="admin-brand-logo"
            >

            <div class="admin-brand-copy">

                <strong>
                    Biblioteca Municipal Isabel Murillo de Luque
                </strong>

                <span>
                    Tenjo - Cundinamarca
                </span>

            </div>

        </a>


        <div class="admin-user">

            <div
                class="admin-user-icon"
                aria-hidden="true"
            >
                <i class="bi bi-person-circle"></i>
            </div>

            <div class="admin-user-copy">

                <strong>
                    <?= htmlspecialchars(
                        $_SESSION['usuario_nombre'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </strong>

                <span>
                    <?= htmlspecialchars(
                        $_SESSION['usuario_rol'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

            </div>

            <a
                href="../logout.php"
                class="admin-logout"
                title="Cerrar sesión"
            >
                <i
                    class="bi bi-box-arrow-right"
                    aria-hidden="true"
                ></i>

                <span>
                    Cerrar sesión
                </span>
            </a>

        </div>

    </div>

</header>


<main class="admin-main">

    <div class="admin-container admin-container-detail">

        <section class="admin-page-heading">

            <div>

                <a
                    href="index.php"
                    class="admin-back-link"
                >
                    <i class="bi bi-arrow-left"></i>
                    Volver a mensajes
                </a>

                <span class="admin-kicker">
                    Solicitudes y atención
                </span>

                <h1>
                    Detalle del mensaje
                </h1>

                <p>
                    Consulta la información completa enviada desde
                    el formulario de contacto.
                </p>

            </div>

        </section>


        <?php if ($mensajeExito !== ''): ?>

            <div class="admin-alert admin-alert-success">

                <i class="bi bi-check-circle-fill"></i>

                <?= htmlspecialchars(
                    $mensajeExito,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>


        <?php if ($error !== ''): ?>

            <div class="admin-alert admin-alert-error">

                <i class="bi bi-exclamation-circle-fill"></i>

                <?= htmlspecialchars(
                    $error,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>


        <section class="admin-detail-layout">

            <div class="admin-card admin-detail-main">

                <div class="admin-detail-heading">

                    <span class="admin-detail-icon">
                        <i class="bi bi-envelope-open"></i>
                    </span>

                    <div>

                        <span>
                            Asunto
                        </span>

                        <h2>
                            <?= htmlspecialchars(
                                $contacto['asunto'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h2>

                    </div>

                </div>


                <div class="admin-message-box">

                    <?= nl2br(
                        htmlspecialchars(
                            $contacto['mensaje'],
                            ENT_QUOTES,
                            'UTF-8'
                        )
                    ) ?>

                </div>


                <div class="admin-detail-meta">

                    <div>

                        <span>
                            Recibido
                        </span>

                        <strong>
                            <?= date(
                                'd/m/Y',
                                strtotime(
                                    $contacto['created_at']
                                )
                            ) ?>
                        </strong>

                        <small>
                            <?= date(
                                'H:i',
                                strtotime(
                                    $contacto['created_at']
                                )
                            ) ?>
                        </small>

                    </div>


                    <div>

                        <span>
                            Notificación por correo
                        </span>

                        <?php if ((int)$contacto['correo_enviado'] === 1): ?>

                            <strong class="admin-detail-mail-ok">
                                <i class="bi bi-check-circle-fill"></i>
                                Enviada
                            </strong>

                        <?php else: ?>

                            <strong class="admin-detail-mail-pending">
                                <i class="bi bi-exclamation-circle"></i>
                                Pendiente
                            </strong>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <aside class="admin-detail-sidebar">

                <section class="admin-card admin-contact-summary">

                    <div class="admin-detail-section-title">

                        <i class="bi bi-person-lines-fill"></i>

                        <h2>
                            Remitente
                        </h2>

                    </div>


                    <div class="admin-contact-summary-row">

                        <span>
                            Nombre
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $contacto['nombre'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                    </div>


                    <div class="admin-contact-summary-row">

                        <span>
                            Correo
                        </span>

                        <a
                            href="mailto:<?= htmlspecialchars(
                                $contacto['correo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <?= htmlspecialchars(
                                $contacto['correo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </a>

                    </div>


                    <div class="admin-contact-summary-row">

                        <span>
                            Teléfono
                        </span>

                        <strong>
                            <?= !empty($contacto['telefono'])
                                ? htmlspecialchars(
                                    $contacto['telefono'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                  )
                                : 'No registrado'
                            ?>
                        </strong>

                    </div>

                </section>


                <section class="admin-card admin-status-card">

                    <div class="admin-detail-section-title">

                        <i class="bi bi-check2-square"></i>

                        <h2>
                            Estado del mensaje
                        </h2>

                    </div>


                    <form method="POST">

                        <div class="admin-form-group">

                            <label for="estado">
                                Estado
                            </label>

                            <select
                                id="estado"
                                name="estado"
                                class="admin-select"
                            >

                                <option
                                    value="nuevo"
                                    <?= $estadoContacto === 'nuevo'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Nuevo
                                </option>

                                <option
                                    value="revisado"
                                    <?= $estadoContacto === 'revisado'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Revisado
                                </option>

                                <option
                                    value="atendido"
                                    <?= $estadoContacto === 'atendido'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Atendido
                                </option>

                            </select>

                        </div>


                        <button
                            type="submit"
                            class="admin-btn admin-btn-primary admin-btn-full"
                        >
                            <i class="bi bi-check-lg"></i>
                            Actualizar estado
                        </button>

                    </form>

                </section>

            </aside>

        </section>


        <footer class="admin-footer">

            <span>
                Biblioteca Municipal Isabel Murillo de Luque
            </span>

            <span>
                Detalle de contacto
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>