<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT
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
            correo_enviado,
            created_at
        FROM solicitudes_espacios
        ORDER BY fecha_reserva DESC, created_at DESC, id DESC";

$stmt = $pdo->query($sql);
$solicitudes = $stmt->fetchAll();

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
        Solicitudes de espacios | Administrador Biblioteca Tenjo
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
        href="../assets/css/admin-modulos.css?v=20260902-5"
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

    <div class="admin-container">

        <section class="admin-page-heading">

            <div>

                <a
                    href="../dashboard.php"
                    class="admin-back-link"
                >
                    <i class="bi bi-arrow-left"></i>
                    Volver al panel
                </a>

                <span class="admin-kicker">
                    Solicitudes y atención
                </span>

                <h1>
                    Solicitudes de espacios
                </h1>

                <p>
                    Consulta las reservas y solicitudes realizadas
                    para los espacios disponibles de la Biblioteca.
                </p>

            </div>

        </section>


        <section class="admin-card">

            <?php if (empty($solicitudes)): ?>

                <div class="admin-empty-state">

                    <span class="admin-empty-icon">
                        <i class="bi bi-building"></i>
                    </span>

                    <strong>
                        No hay solicitudes de espacios registradas
                    </strong>

                    <p>
                        Las reservas enviadas desde el formulario de Coworking
                        aparecerán aquí automáticamente.
                    </p>

                </div>

            <?php else: ?>

                <div class="admin-table-wrapper">

                    <table class="admin-table admin-table-reservas">

                        <thead>

                            <tr>
                                <th>Solicitante</th>
                                <th>Espacio</th>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th>Uso</th>
                                <th>Personas</th>
                                <th>Estado</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($solicitudes as $solicitud): ?>

                            <?php

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

                            ?>

                            <tr>

                                <td>

                                    <div class="admin-reserva-person">

                                        <span class="admin-contact-avatar">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <div>

                                            <div class="admin-table-title">
                                                <?= htmlspecialchars(
                                                    $solicitud['nombre_completo'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <a
                                                class="admin-contact-email"
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

                                            <small class="admin-contact-phone">
                                                <i class="bi bi-telephone"></i>
                                                <?= htmlspecialchars(
                                                    $solicitud['telefono'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span class="admin-space-badge">
                                        <i class="bi bi-geo-alt"></i>

                                        <?= htmlspecialchars(
                                            $solicitud['espacio'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <div class="admin-reserva-date">

                                        <strong>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $solicitud['fecha_reserva']
                                                )
                                            ) ?>
                                        </strong>

                                    </div>

                                </td>


                                <td>

                                    <span class="admin-reserva-time">
                                        <i class="bi bi-clock"></i>

                                        <?= htmlspecialchars(
                                            $solicitud['horario'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="admin-reserva-use">
                                        <?= htmlspecialchars(
                                            $solicitud['tipo_uso'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="admin-people-badge">
                                        <i class="bi bi-people"></i>

                                        <?= htmlspecialchars(
                                            $solicitud['cantidad_personas'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if ($estadoSolicitud === 'pendiente'): ?>

                                        <span class="admin-status admin-status-new">
                                            Pendiente
                                        </span>

                                    <?php elseif ($estadoSolicitud === 'aprobada'): ?>

                                        <span class="admin-status admin-status-active">
                                            Aprobada
                                        </span>

                                    <?php elseif ($estadoSolicitud === 'rechazada'): ?>

                                        <span class="admin-status admin-status-finished">
                                            Rechazada
                                        </span>

                                    <?php elseif ($estadoSolicitud === 'atendida'): ?>

                                        <span class="admin-status admin-status-reviewed">
                                            Atendida
                                        </span>

                                    <?php else: ?>

                                        <span class="admin-status admin-status-inactive">
                                            <?= htmlspecialchars(
                                                ucfirst($estadoSolicitud),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ((int)$solicitud['correo_enviado'] === 1): ?>

                                        <span class="admin-mail-status admin-mail-status-ok">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Enviado
                                        </span>

                                    <?php else: ?>

                                        <span class="admin-mail-status admin-mail-status-pending">
                                            <i class="bi bi-exclamation-circle"></i>
                                            Pendiente
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <a
                                        href="ver.php?id=<?= (int)$solicitud['id'] ?>"
                                        class="admin-btn admin-btn-light"
                                    >
                                        <i class="bi bi-eye"></i>
                                        Ver detalle
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>


        <footer class="admin-footer">

            <span>
                Biblioteca Municipal Isabel Murillo de Luque
            </span>

            <span>
                Gestión de solicitudes de espacios
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>