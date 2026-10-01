<?php

require_once __DIR__ . '/includes/auth.php';

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

        Panel Administrativo | Biblioteca Tenjo

    </title>

    <link

        rel="stylesheet"

        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"

    >

    <link

        rel="stylesheet"

        href="assets/css/admin.css?v=20260902-1"

    >

</head>

<body>

<div class="admin-shell">

    <!-- =====================================================

         ENCABEZADO ADMINISTRATIVO

         ===================================================== -->

    <header class="admin-header">

        <div class="admin-header-inner">

            <div class="admin-brand">

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

            </div>

            <div class="admin-user">

                <div class="admin-user-icon" aria-hidden="true">

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

                    href="logout.php"

                    class="admin-logout"

                    title="Cerrar sesión"

                >

                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>

                    <span>Cerrar sesión</span>

                </a>

            </div>

        </div>

    </header>

    <!-- =====================================================

         CONTENIDO

         ===================================================== -->

    <main class="admin-main">

        <div class="admin-container">

            <section class="admin-page-heading">

                <div>

                    <span class="admin-kicker">

                        Gestión del sitio web

                    </span>

                    <h1>

                        Panel Administrativo

                    </h1>

                    <p>

                        Administra los contenidos, formularios y servicios

                        publicados en el portal de la Biblioteca.

                    </p>

                </div>

            </section>

            <!-- =================================================

                 CONTENIDO PUBLICABLE

                 ================================================= -->

            <section class="admin-section">

                <div class="admin-section-heading">

                    <div>

                        <h2>

                            Contenido del sitio

                        </h2>

                        <p>

                            Gestiona la información visible para los usuarios.

                        </p>

                    </div>

                </div>

                <div class="admin-dashboard-grid">

                    <a

                        href="eventos/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-green">

                            <i class="bi bi-calendar-event"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Eventos

                            </h3>

                            <p>

                                Crea, edita y administra los eventos y

                                actividades de la Biblioteca.

                            </p>

                            <span class="admin-module-link">

                                Gestionar eventos

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="banners/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-yellow">

                            <i class="bi bi-images"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Banners

                            </h3>

                            <p>

                                Administra las imágenes y mensajes principales

                                del carrusel del Home.

                            </p>

                            <span class="admin-module-link">

                                Gestionar banners

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="documentos/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-blue">

                            <i class="bi bi-file-earmark-text"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Documentación Pública

                            </h3>

                            <p>

                                Publica y administra documentos institucionales

                                disponibles para consulta.

                            </p>

                            <span class="admin-module-link">

                                Gestionar documentos

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="popup/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-orange">

                            <i class="bi bi-window-stack"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Avisos del Home

                            </h3>

                            <p>

                                Programa avisos emergentes con imagen,

                                vigencia y enlace opcional.

                            </p>

                            <span class="admin-module-link">

                                Gestionar avisos

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                                    <a
                        href="encabezados/index.php"
                        class="admin-module-card"
                    >
                        <span class="admin-module-icon admin-icon-green">
                            <i class="bi bi-layout-text-window-reverse"></i>
                        </span>

                        <div class="admin-module-content">
                            <h3>Encabezados de páginas</h3>

                            <p>
                                Administra la cápsula, el título y el texto introductorio
                                de las vistas públicas del sitio.
                            </p>

                            <span class="admin-module-link">
                                Gestionar encabezados
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </a>

</div>

            </section>

            <!-- =================================================

                 SOLICITUDES Y MENSAJES

                 ================================================= -->

            <section class="admin-section">

                <div class="admin-section-heading">

                    <div>

                        <h2>

                            Solicitudes y atención

                        </h2>

                        <p>

                            Consulta la información recibida desde los

                            formularios públicos.

                        </p>

                    </div>

                </div>

                <div class="admin-dashboard-grid">

                    <a

                        href="contactos/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-purple">

                            <i class="bi bi-envelope"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Contacto

                            </h3>

                            <p>

                                Consulta los mensajes enviados desde el

                                formulario de contacto.

                            </p>

                            <span class="admin-module-link">

                                Gestionar mensajes

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="pqrs/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-red">

                            <i class="bi bi-chat-left-text"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                PQRSDF

                            </h3>

                            <p>

                                Consulta las peticiones, quejas, reclamos,

                                sugerencias, denuncias y felicitaciones.

                            </p>

                            <span class="admin-module-link">

                                Gestionar PQRSDF

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="solicitudes_espacios/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-teal">

                            <i class="bi bi-person-workspace"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Solicitudes de Espacios

                            </h3>

                            <p>

                                Consulta las solicitudes de reserva realizadas

                                por los usuarios.

                            </p>

                            <span class="admin-module-link">

                                Gestionar solicitudes

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                    <a

                        href="usuarios/index.php"

                        class="admin-module-card"

                    >

                        <span class="admin-module-icon admin-icon-gray">

                            <i class="bi bi-people"></i>

                        </span>

                        <div class="admin-module-content">

                            <h3>

                                Usuarios

                            </h3>

                            <p>

                                Administra las cuentas con acceso al panel

                                administrativo.

                            </p>

                            <span class="admin-module-link">

                                Gestionar usuarios

                                <i class="bi bi-arrow-right"></i>

                            </span>

                        </div>

                    </a>

                </div>

            </section>

            <footer class="admin-footer">

                <span>

                    Biblioteca Municipal Isabel Murillo de Luque

                </span>

                <span>

                    Panel de administración

                </span>

            </footer>

        </div>

    </main>

</div>

</body>

</html>
