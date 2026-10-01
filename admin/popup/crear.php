<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$errores = [];

$titulo = '';
$descripcion = '';
$botonTexto = '';
$botonUrl = '';
$fechaInicio = '';
$fechaFin = '';
$estado = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $botonTexto = trim($_POST['boton_texto'] ?? '');
    $botonUrl = trim($_POST['boton_url'] ?? '');
    $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
    $fechaFin = trim($_POST['fecha_fin'] ?? '');
    $estado = isset($_POST['estado']) ? 1 : 0;

    if ($titulo === '') {
        $errores[] = 'El título es obligatorio.';
    } elseif (mb_strlen($titulo) > 180) {
        $errores[] = 'El título no puede superar los 180 caracteres.';
    }

    if (mb_strlen($descripcion) > 1000) {
        $errores[] = 'La descripción no puede superar los 1000 caracteres.';
    }

    if (mb_strlen($botonTexto) > 100) {
        $errores[] = 'El texto del botón no puede superar los 100 caracteres.';
    }

    if ($botonUrl !== '' && mb_strlen($botonUrl) > 500) {
        $errores[] = 'La URL del botón es demasiado larga.';
    }

    if (
        $fechaInicio !== '' &&
        $fechaFin !== '' &&
        strtotime($fechaFin) < strtotime($fechaInicio)
    ) {
        $errores[] = 'La fecha de finalización no puede ser anterior a la fecha de inicio.';
    }

    $rutaImagenBd = null;

    if (
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'No fue posible cargar la imagen.';
        } else {
            $tamanoMaximo = 5 * 1024 * 1024;

            if ($_FILES['imagen']['size'] > $tamanoMaximo) {
                $errores[] = 'La imagen no puede superar los 5 MB.';
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['imagen']['tmp_name']);

            $extensionesPermitidas = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($extensionesPermitidas[$mime])) {
                $errores[] = 'La imagen debe estar en formato JPG, PNG o WEBP.';
            }

            if (empty($errores)) {
                $extension = $extensionesPermitidas[$mime];
                $nombreArchivo = 'popup_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

                $directorioFisico = __DIR__ . '/../../public/assets/img/popup/';
                $rutaImagenBd = 'public/assets/img/popup/' . $nombreArchivo;

                if (!is_dir($directorioFisico)) {
                    if (!mkdir($directorioFisico, 0775, true) && !is_dir($directorioFisico)) {
                        $errores[] = 'No fue posible crear la carpeta para las imágenes.';
                    }
                }

                if (
                    empty($errores) &&
                    !move_uploaded_file(
                        $_FILES['imagen']['tmp_name'],
                        $directorioFisico . $nombreArchivo
                    )
                ) {
                    $errores[] = 'No fue posible guardar la imagen cargada.';
                }
            }
        }
    }

    if (empty($errores)) {
        try {
            $sql = "
                INSERT INTO popup_home (
                    titulo,
                    descripcion,
                    imagen,
                    boton_texto,
                    boton_url,
                    fecha_inicio,
                    fecha_fin,
                    estado
                ) VALUES (
                    :titulo,
                    :descripcion,
                    :imagen,
                    :boton_texto,
                    :boton_url,
                    :fecha_inicio,
                    :fecha_fin,
                    :estado
                )
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion !== '' ? $descripcion : null,
                ':imagen' => $rutaImagenBd,
                ':boton_texto' => $botonTexto !== '' ? $botonTexto : null,
                ':boton_url' => $botonUrl !== '' ? $botonUrl : null,
                ':fecha_inicio' => $fechaInicio !== '' ? date('Y-m-d H:i:s', strtotime($fechaInicio)) : null,
                ':fecha_fin' => $fechaFin !== '' ? date('Y-m-d H:i:s', strtotime($fechaFin)) : null,
                ':estado' => $estado
            ]);

            header('Location: index.php?creado=1');
            exit;

        } catch (Throwable $e) {
            if ($rutaImagenBd !== null) {
                $archivoGuardado = __DIR__ . '/../../' . $rutaImagenBd;

                if (is_file($archivoGuardado)) {
                    @unlink($archivoGuardado);
                }
            }

            $errores[] = 'No fue posible crear el aviso. Intenta nuevamente.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear aviso | Administración Biblioteca</title>

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
        href="../assets/css/admin-modulos.css?v=20260901-4"
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
                        href="index.php"
                        class="admin-back-link"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Volver a avisos
                    </a>

                    <span class="admin-kicker">
                        Contenido del sitio
                    </span>

                    <h1>
                        Crear aviso
                    </h1>

                    <p>
                        Crea un aviso emergente para mostrar información
                        destacada temporalmente en el Home.
                    </p>
                </div>
            </section>

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

            <form
                method="POST"
                enctype="multipart/form-data"
                class="admin-card admin-form-card"
            >

                <div class="admin-form-grid">

                    <div class="admin-form-group">
                        <label for="titulo">
                            Título
                            <span class="admin-required">*</span>
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            maxlength="180"
                            value="<?= htmlspecialchars(
                                $titulo,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >
                    </div>

                    <div class="admin-form-group">
                        <label for="imagen">
                            Imagen
                        </label>

                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="admin-help">
                            Formatos permitidos: JPG, PNG o WEBP. Máximo 5 MB.
                        </small>
                    </div>

                    <div class="admin-form-group admin-form-group-full">
                        <label for="descripcion">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="5"
                            maxlength="1000"
                            placeholder="Escribe el contenido principal del aviso."
                        ><?= htmlspecialchars(
                            $descripcion,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?></textarea>
                    </div>

                    <div class="admin-form-group">
                        <label for="boton_texto">
                            Texto del botón
                        </label>

                        <input
                            type="text"
                            id="boton_texto"
                            name="boton_texto"
                            maxlength="100"
                            value="<?= htmlspecialchars(
                                $botonTexto,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            placeholder="Ejemplo: Ver eventos"
                        >
                    </div>

                    <div class="admin-form-group">
                        <label for="boton_url">
                            Enlace del botón
                        </label>

                        <input
                            type="text"
                            id="boton_url"
                            name="boton_url"
                            maxlength="500"
                            value="<?= htmlspecialchars(
                                $botonUrl,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            placeholder="Ejemplo: eventos.html"
                        >
                    </div>

                    <div class="admin-form-group">
                        <label for="fecha_inicio">
                            Fecha de inicio
                        </label>

                        <input
                            type="datetime-local"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="<?= htmlspecialchars(
                                $fechaInicio,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                    </div>

                    <div class="admin-form-group">
                        <label for="fecha_fin">
                            Fecha de finalización
                        </label>

                        <input
                            type="datetime-local"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="<?= htmlspecialchars(
                                $fechaFin,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                    </div>

                </div>

                <div class="admin-heading-card-footer">
                    <div class="admin-check-row admin-check-row-inline">
                        <input
                            type="checkbox"
                            id="estado"
                            name="estado"
                            value="1"
                            <?= $estado === 1 ? 'checked' : '' ?>
                        >

                        <label for="estado">
                            Aviso activo
                        </label>
                    </div>

                    <div class="admin-actions">
                        <a
                            href="index.php"
                            class="admin-btn admin-btn-light"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="admin-btn admin-btn-primary"
                        >
                            <i class="bi bi-floppy"></i>
                            Guardar aviso
                        </button>
                    </div>
                </div>

            </form>

            <footer class="admin-footer">
                <span>
                    Biblioteca Municipal Isabel Murillo de Luque
                </span>

                <span>
                    Creación de avisos del Home
                </span>
            </footer>

        </div>
    </main>

</div>

</body>
</html>
