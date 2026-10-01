<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$mensajeExito = '';
$errores = [];

/*
========================================================
ACTUALIZAR ENCABEZADO
========================================================
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id =
        isset($_POST['id'])
            ? (int)$_POST['id']
            : 0;

    $etiqueta =
        trim(
            $_POST['etiqueta']
            ?? ''
        );

    $titulo =
        trim(
            $_POST['titulo']
            ?? ''
        );

    $descripcion =
        trim(
            $_POST['descripcion']
            ?? ''
        );

    $estado =
        isset($_POST['estado'])
            ? 1
            : 0;

    if ($id <= 0) {
        $errores[] =
            'El encabezado seleccionado no es válido.';
    }

    if ($titulo === '') {
        $errores[] =
            'El título es obligatorio.';
    } elseif (mb_strlen($titulo) > 200) {
        $errores[] =
            'El título no puede superar los 200 caracteres.';
    }

    if (
        $etiqueta !== '' &&
        mb_strlen($etiqueta) > 150
    ) {
        $errores[] =
            'La etiqueta no puede superar los 150 caracteres.';
    }

    if (empty($errores)) {

        try {

            $sql = "
                UPDATE encabezados_vistas
                SET
                    etiqueta = :etiqueta,
                    titulo = :titulo,
                    descripcion = :descripcion,
                    estado = :estado
                WHERE id = :id
            ";

            $stmt =
                $pdo->prepare($sql);

            $stmt->execute([
                ':etiqueta' =>
                    $etiqueta !== ''
                        ? $etiqueta
                        : null,

                ':titulo' =>
                    $titulo,

                ':descripcion' =>
                    $descripcion !== ''
                        ? $descripcion
                        : null,

                ':estado' =>
                    $estado,

                ':id' =>
                    $id
            ]);

            $mensajeExito =
                'El encabezado fue actualizado correctamente.';

        } catch (Throwable $e) {

            $errores[] =
                'No fue posible actualizar el encabezado.';
        }
    }
}

/*
========================================================
CONSULTAR ENCABEZADOS
========================================================
*/

$stmt =
    $pdo->query("
        SELECT
            id,
            vista,
            etiqueta,
            titulo,
            descripcion,
            estado,
            updated_at
        FROM encabezados_vistas
        ORDER BY id ASC
    ");

$encabezados =
    $stmt->fetchAll();

/*
========================================================
NOMBRES AMIGABLES
========================================================
*/

$nombresVistas = [
    'sedes' => 'Sedes',
    'servicios' => 'Servicios',
    'actividades-transversales' => 'Actividades Transversales',
    'programas' => 'Programas Bibliotecarios',
    'eventos' => 'Eventos',
    'coworking' => 'Solicitud de Espacios',
    'pqrs' => 'PQRSDF',
    'documentacion' => 'Documentación Pública',
    'equipo' => 'Equipo',
    'contacto' => 'Contacto',
    'material-digital' => 'Material Digital'
];

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    Encabezados de páginas | Administración Biblioteca
</title>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<link
    rel="stylesheet"
    href="../assets/css/admin.css?v=20260922-1"
>

<link
    rel="stylesheet"
    href="../assets/css/admin-modulos.css?v=20260922-1"
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
                    Contenido del sitio
                </span>

                <h1>
                    Encabezados de páginas
                </h1>

                <p>
                    Administra desde un solo lugar la etiqueta,
                    título y texto introductorio de las vistas públicas.
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


        <?php if (!empty($errores)): ?>

            <div class="admin-alert admin-alert-error">

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>

                    <?php foreach ($errores as $error): ?>

                        <div>
                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endif; ?>


        <section class="admin-headings-grid">

            <?php foreach ($encabezados as $encabezado): ?>

                <?php

                $nombreVista =
                    $nombresVistas[$encabezado['vista']]
                    ?? ucfirst(
                        str_replace(
                            '-',
                            ' ',
                            $encabezado['vista']
                        )
                    );

                ?>

                <form
                    method="POST"
                    class="admin-card admin-heading-card"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$encabezado['id'] ?>"
                    >


                    <div class="admin-heading-card-top">

                        <div>

                            <span class="admin-heading-view-label">
                                Vista
                            </span>

                            <h2>
                                <?= htmlspecialchars(
                                    $nombreVista,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h2>

                            <small>
                                <?= htmlspecialchars(
                                    $encabezado['vista'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </small>

                        </div>


                        <?php if ((int)$encabezado['estado'] === 1): ?>

                            <span class="admin-status admin-status-active">
                                Activo
                            </span>

                        <?php else: ?>

                            <span class="admin-status admin-status-inactive">
                                Inactivo
                            </span>

                        <?php endif; ?>

                    </div>


                    <div class="admin-form-group">

                        <label>
                            Etiqueta superior
                        </label>

                        <input
                            type="text"
                            name="etiqueta"
                            maxlength="150"
                            value="<?= htmlspecialchars(
                                $encabezado['etiqueta']
                                ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            placeholder="Ejemplo: Biblioteca en el territorio"
                        >

                    </div>


                    <div class="admin-form-group">

                        <label>
                            Título
                            <span class="admin-required">*</span>
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            maxlength="200"
                            value="<?= htmlspecialchars(
                                $encabezado['titulo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="admin-form-group">

                        <label>
                            Texto introductorio
                        </label>

                        <textarea
                            name="descripcion"
                            rows="4"
                            placeholder="Texto que aparece debajo del título."
                        ><?= htmlspecialchars(
                            $encabezado['descripcion']
                            ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?></textarea>

                    </div>


                    <div class="admin-heading-card-footer">

                        <div class="admin-check-row admin-check-row-inline">

                            <input
                                type="checkbox"
                                id="estado_<?= (int)$encabezado['id'] ?>"
                                name="estado"
                                value="1"
                                <?= (int)$encabezado['estado'] === 1
                                    ? 'checked'
                                    : ''
                                ?>
                            >

                            <label
                                for="estado_<?= (int)$encabezado['id'] ?>"
                            >
                                Encabezado activo
                            </label>

                        </div>


                        <button
                            type="submit"
                            class="admin-btn admin-btn-primary"
                        >
                            <i class="bi bi-floppy"></i>
                            Guardar
                        </button>

                    </div>


                    <div class="admin-heading-updated">

                        <i class="bi bi-clock-history"></i>

                        Última actualización:
                        <?= date(
                            'd/m/Y H:i',
                            strtotime(
                                $encabezado['updated_at']
                            )
                        ) ?>

                    </div>

                </form>

            <?php endforeach; ?>

        </section>


        <footer class="admin-footer">

            <span>
                Biblioteca Municipal Isabel Murillo de Luque
            </span>

            <span>
                Gestión de encabezados
            </span>

        </footer>

    </div>

</main>

</div>

</body>

</html>