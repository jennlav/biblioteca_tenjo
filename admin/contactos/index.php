<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT
            id,
            nombre,
            correo,
            telefono,
            asunto,
            mensaje,
            estado,
            correo_enviado,
            created_at
        FROM contactos
        ORDER BY created_at DESC, id DESC";

$stmt = $pdo->query($sql);
$contactos = $stmt->fetchAll();

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
        Contacto | Administrador Biblioteca Tenjo
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
        href="../assets/css/admin-modulos.css?v=20260902-1"
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
                    Mensajes de contacto
                </h1>

                <p>
                    Consulta los mensajes enviados desde el formulario
                    de contacto del sitio web.
                </p>

            </div>

        </section>


        <section class="admin-card">

            <?php if (empty($contactos)): ?>

                <div class="admin-empty-state">

                    <span class="admin-empty-icon">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <strong>
                        No hay mensajes recibidos
                    </strong>

                    <p>
                        Los mensajes enviados desde el formulario de contacto
                        aparecerán aquí automáticamente.
                    </p>

                </div>

            <?php else: ?>

                <div class="admin-table-wrapper">

                    <table class="admin-table admin-table-contactos">

                        <thead>

                            <tr>
                                <th>Remitente</th>
                                <th>Asunto</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($contactos as $contacto): ?>

                            <?php

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

                            <tr>

                                <td>

                                    <div class="admin-contact-person">

                                        <span class="admin-contact-avatar">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <div>

                                            <div class="admin-table-title">
                                                <?= htmlspecialchars(
                                                    $contacto['nombre'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <a
                                                class="admin-contact-email"
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

                                            <?php if (!empty($contacto['telefono'])): ?>

                                                <small class="admin-contact-phone">
                                                    <i class="bi bi-telephone"></i>

                                                    <?= htmlspecialchars(
                                                        $contacto['telefono'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </small>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <div class="admin-contact-subject">
                                        <?= htmlspecialchars(
                                            $contacto['asunto'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </div>

                                </td>


                                <td>

                                    <div class="admin-table-description admin-contact-message">
                                        <?= htmlspecialchars(
                                            mb_strimwidth(
                                                preg_replace(
                                                    '/\s+/',
                                                    ' ',
                                                    $contacto['mensaje']
                                                ),
                                                0,
                                                110,
                                                '...'
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </div>

                                </td>


                                <td>

                                    <div class="admin-contact-date">

                                        <strong>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $contacto['created_at']
                                                )
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= date(
                                                'H:i',
                                                strtotime(
                                                    $contacto['created_at']
                                                )
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <?php if ($estadoContacto === 'nuevo'): ?>

                                        <span class="admin-status admin-status-new">
                                            Nuevo
                                        </span>

                                    <?php elseif ($estadoContacto === 'revisado'): ?>

                                        <span class="admin-status admin-status-reviewed">
                                            Revisado
                                        </span>

                                    <?php elseif ($estadoContacto === 'atendido'): ?>

                                        <span class="admin-status admin-status-active">
                                            Atendido
                                        </span>

                                    <?php else: ?>

                                        <span class="admin-status admin-status-inactive">
                                            <?= htmlspecialchars(
                                                ucfirst($estadoContacto),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?php if ((int)$contacto['correo_enviado'] === 1): ?>

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
                                        href="ver.php?id=<?= (int)$contacto['id'] ?>"
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
                Gestión de mensajes de contacto
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>