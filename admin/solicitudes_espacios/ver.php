<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$id =
    isset($_GET['id'])
        ? (int)$_GET['id']
        : 0;

if ($id <= 0) {
    die('Solicitud no válida.');
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
        'pendiente',
        'aprobada',
        'rechazada',
        'atendida'
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
            "UPDATE solicitudes_espacios
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
            'El estado de la solicitud fue actualizado correctamente.';
    }
}

/*
========================================================
CONSULTAR SOLICITUD
========================================================
*/

$sql =
    "SELECT
        id,
        nombre_completo,
        correo,
        telefono,
        espacio,
        fecha_reserva,
        horario,
        tipo_uso,
        cantidad_personas,
        detalle,
        estado,
        ip_origen,
        user_agent,
        correo_enviado,
        created_at,
        updated_at
     FROM solicitudes_espacios
     WHERE id = :id
     LIMIT 1";

$stmt =
    $pdo->prepare($sql);

$stmt->execute([
    ':id' =>
        $id
]);

$solicitud =
    $stmt->fetch();

if (!$solicitud) {
    die('Solicitud no encontrada.');
}

$estadoSolicitud =
    strtolower(
        trim(
            $solicitud['estado']
            ?? 'pendiente'
        )
    );

if ($estadoSolicitud === '') {
    $estadoSolicitud = 'pendiente';
}

$numeroSolicitud =
    str_pad(
        (string)$solicitud['id'],
        3,
        '0',
        STR_PAD_LEFT
    );

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
        Detalle solicitud de espacio | Biblioteca Tenjo
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
        href="../assets/css/admin-modulos.css?v=20260902-6"
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
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
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
                    Volver a solicitudes
                </a>

                <span class="admin-kicker">
                    Solicitudes y atención
                </span>

                <h1>
                    Detalle de solicitud
                </h1>

                <p>
                    Consulta la información completa de la reserva
                    #<?= htmlspecialchars(
                        $numeroSolicitud,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>.
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

                    <span class="admin-detail-icon admin-detail-icon-space">
                        <i class="bi bi-building"></i>
                    </span>

                    <div>

                        <span>
                            Espacio solicitado
                        </span>

                        <h2>
                            <?= htmlspecialchars(
                                $solicitud['espacio'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h2>

                    </div>

                    <span class="admin-radicado admin-radicado-detail">
                        #<?= htmlspecialchars(
                            $numeroSolicitud,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                </div>


                <div class="admin-reserva-summary-grid">

                    <div class="admin-reserva-summary-item">

                        <span class="admin-reserva-summary-icon">
                            <i class="bi bi-calendar-event"></i>
                        </span>

                        <div>
                            <span>Fecha</span>
                            <strong>
                                <?= date(
                                    'd/m/Y',
                                    strtotime(
                                        $solicitud['fecha_reserva']
                                    )
                                ) ?>
                            </strong>
                        </div>

                    </div>


                    <div class="admin-reserva-summary-item">

                        <span class="admin-reserva-summary-icon">
                            <i class="bi bi-clock"></i>
                        </span>

                        <div>
                            <span>Horario</span>
                            <strong>
                                <?= htmlspecialchars(
                                    $solicitud['horario'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>
                        </div>

                    </div>


                    <div class="admin-reserva-summary-item">

                        <span class="admin-reserva-summary-icon">
                            <i class="bi bi-briefcase"></i>
                        </span>

                        <div>
                            <span>Tipo de uso</span>
                            <strong>
                                <?= htmlspecialchars(
                                    $solicitud['tipo_uso'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>
                        </div>

                    </div>


                    <div class="admin-reserva-summary-item">

                        <span class="admin-reserva-summary-icon">
                            <i class="bi bi-people"></i>
                        </span>

                        <div>
                            <span>Personas</span>
                            <strong>
                                <?= htmlspecialchars(
                                    $solicitud['cantidad_personas'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>
                        </div>

                    </div>

                </div>


                <div class="admin-detail-subheading">
                    <i class="bi bi-card-text"></i>
                    <h3>Detalle de la solicitud</h3>
                </div>


                <div class="admin-message-box">

                    <?= nl2br(
                        htmlspecialchars(
                            $solicitud['detalle'],
                            ENT_QUOTES,
                            'UTF-8'
                        )
                    ) ?>

                </div>


                <div class="admin-detail-meta">

                    <div>

                        <span>
                            Solicitud recibida
                        </span>

                        <strong>
                            <?= date(
                                'd/m/Y',
                                strtotime(
                                    $solicitud['created_at']
                                )
                            ) ?>
                        </strong>

                        <small>
                            <?= date(
                                'H:i',
                                strtotime(
                                    $solicitud['created_at']
                                )
                            ) ?>
                        </small>

                    </div>


                    <div>

                        <span>
                            Notificación por correo
                        </span>

                        <?php if ((int)$solicitud['correo_enviado'] === 1): ?>

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
                            Solicitante
                        </h2>

                    </div>


                    <div class="admin-contact-summary-row">

                        <span>
                            Nombre completo
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $solicitud['nombre_completo'],
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
                                $solicitud['correo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <?= htmlspecialchars(
                                $solicitud['correo'],
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
                            <?= htmlspecialchars(
                                $solicitud['telefono'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                    </div>

                </section>


                <section class="admin-card admin-status-card">

                    <div class="admin-detail-section-title">

                        <i class="bi bi-check2-square"></i>

                        <h2>
                            Estado de la solicitud
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
                                    value="pendiente"
                                    <?= $estadoSolicitud === 'pendiente'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Pendiente
                                </option>

                                <option
                                    value="aprobada"
                                    <?= $estadoSolicitud === 'aprobada'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Aprobada
                                </option>

                                <option
                                    value="rechazada"
                                    <?= $estadoSolicitud === 'rechazada'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Rechazada
                                </option>

                                <option
                                    value="atendida"
                                    <?= $estadoSolicitud === 'atendida'
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    Atendida
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
                Solicitud de espacio #<?= htmlspecialchars(
                    $numeroSolicitud,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>