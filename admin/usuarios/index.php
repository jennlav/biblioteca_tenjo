<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT
            id,
            nombre,
            correo,
            rol,
            estado,
            created_at,
            updated_at
        FROM usuarios
        ORDER BY created_at DESC, id DESC";

$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll();

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
        Usuarios | Administrador Biblioteca Tenjo
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
        href="../assets/css/admin-modulos.css?v=20260902-7"
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
                    Administración
                </span>

                <h1>
                    Usuarios
                </h1>

                <p>
                    Administra las cuentas con acceso al panel
                    administrativo de la Biblioteca.
                </p>

            </div>

            <a
                href="crear.php"
                class="admin-btn admin-btn-primary"
            >
                <i class="bi bi-person-plus"></i>
                Crear usuario
            </a>

        </section>


        <section class="admin-card">

            <?php if (empty($usuarios)): ?>

                <div class="admin-empty-state">

                    <span class="admin-empty-icon">
                        <i class="bi bi-people"></i>
                    </span>

                    <strong>
                        No hay usuarios registrados
                    </strong>

                    <p>
                        Crea una cuenta para permitir acceso
                        al panel administrativo.
                    </p>

                    <a
                        href="crear.php"
                        class="admin-btn admin-btn-primary"
                    >
                        <i class="bi bi-person-plus"></i>
                        Crear usuario
                    </a>

                </div>

            <?php else: ?>

                <div class="admin-table-wrapper">

                    <table class="admin-table admin-table-users">

                        <thead>

                            <tr>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Creado</th>
                                <th>Última actualización</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($usuarios as $usuario): ?>

                            <tr>

                                <td>

                                    <div class="admin-user-row">

                                        <span class="admin-user-avatar-table">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <div>

                                            <div class="admin-table-title">
                                                <?= htmlspecialchars(
                                                    $usuario['nombre'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </div>

                                            <a
                                                href="mailto:<?= htmlspecialchars(
                                                    $usuario['correo'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                class="admin-contact-email"
                                            >
                                                <?= htmlspecialchars(
                                                    $usuario['correo'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </a>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span class="admin-role-badge">
                                        <i class="bi bi-shield-lock"></i>

                                        <?= htmlspecialchars(
                                            ucfirst(
                                                $usuario['rol']
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <?php if ((int)$usuario['estado'] === 1): ?>

                                        <span class="admin-status admin-status-active">
                                            Activo
                                        </span>

                                    <?php else: ?>

                                        <span class="admin-status admin-status-inactive">
                                            Inactivo
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="admin-user-date">

                                        <strong>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $usuario['created_at']
                                                )
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= date(
                                                'H:i',
                                                strtotime(
                                                    $usuario['created_at']
                                                )
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="admin-user-date">

                                        <strong>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $usuario['updated_at']
                                                )
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= date(
                                                'H:i',
                                                strtotime(
                                                    $usuario['updated_at']
                                                )
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <a
                                        href="editar.php?id=<?= (int)$usuario['id'] ?>"
                                        class="admin-btn admin-btn-light"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        Editar
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
                Gestión de usuarios
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>