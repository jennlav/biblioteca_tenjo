<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$id =
    isset($_GET['id'])
        ? (int)$_GET['id']
        : 0;

if ($id <= 0) {
    die('Aviso no válido.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM popup_home
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $id
]);

$aviso =
    $stmt->fetch();

if (!$aviso) {
    die('El aviso no existe.');
}

$errores = [];
$mensajeExito = '';

$titulo =
    $aviso['titulo'];

$descripcion =
    $aviso['descripcion'] ?? '';

$botonTexto =
    $aviso['boton_texto'] ?? '';

$botonUrl =
    $aviso['boton_url'] ?? '';

$fechaInicio =
    $aviso['fecha_inicio']
        ? date(
            'Y-m-d\TH:i',
            strtotime($aviso['fecha_inicio'])
        )
        : '';

$fechaFin =
    $aviso['fecha_fin']
        ? date(
            'Y-m-d\TH:i',
            strtotime($aviso['fecha_fin'])
        )
        : '';

$estado =
    (int)$aviso['estado'];

$imagenActual =
    $aviso['imagen'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $botonTexto = trim($_POST['boton_texto'] ?? '');
    $botonUrl = trim($_POST['boton_url'] ?? '');
    $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
    $fechaFin = trim($_POST['fecha_fin'] ?? '');
    $estado = isset($_POST['estado']) ? 1 : 0;

    if ($titulo === '') {
        $errores[] =
            'El título es obligatorio.';
    } elseif (mb_strlen($titulo) > 180) {
        $errores[] =
            'El título no puede superar los 180 caracteres.';
    }

    if ($botonTexto !== '' && mb_strlen($botonTexto) > 100) {
        $errores[] =
            'El texto del botón no puede superar los 100 caracteres.';
    }

    if ($botonUrl !== '' && mb_strlen($botonUrl) > 255) {
        $errores[] =
            'La URL del botón no puede superar los 255 caracteres.';
    }

    if ($fechaInicio !== '' && $fechaFin !== '') {

        $inicio = strtotime($fechaInicio);
        $fin = strtotime($fechaFin);

        if (
            $inicio !== false &&
            $fin !== false &&
            $fin < $inicio
        ) {
            $errores[] =
                'La fecha de finalización no puede ser anterior a la fecha de inicio.';
        }
    }

    $nuevaImagenSubida =
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE;

    $rutaNuevaImagen = null;
    $rutaFisicaNueva = null;

    if ($nuevaImagenSubida) {

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {

            $errores[] =
                'Ocurrió un error al cargar la nueva imagen.';

        } else {

            $archivoTemporal =
                $_FILES['imagen']['tmp_name'];

            $nombreOriginal =
                $_FILES['imagen']['name'];

            $tamano =
                (int)$_FILES['imagen']['size'];

            $extension =
                strtolower(
                    pathinfo(
                        $nombreOriginal,
                        PATHINFO_EXTENSION
                    )
                );

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            if (!in_array($extension, $extensionesPermitidas, true)) {
                $errores[] =
                    'La imagen debe ser JPG, JPEG, PNG o WEBP.';
            }

            if ($tamano > 5 * 1024 * 1024) {
                $errores[] =
                    'La imagen no puede superar los 5 MB.';
            }
        }
    }

    if (empty($errores)) {

        try {

            if ($nuevaImagenSubida) {

                $directorioDestino =
                    __DIR__ .
                    '/../../uploads/popup/';

                if (!is_dir($directorioDestino)) {
                    mkdir(
                        $directorioDestino,
                        0755,
                        true
                    );
                }

                $nombreNuevo =
                    'popup_' .
                    time() .
                    '_' .
                    bin2hex(random_bytes(4)) .
                    '.' .
                    $extension;

                $rutaFisicaNueva =
                    $directorioDestino .
                    $nombreNuevo;

                if (
                    !move_uploaded_file(
                        $archivoTemporal,
                        $rutaFisicaNueva
                    )
                ) {
                    throw new Exception(
                        'No fue posible guardar la nueva imagen.'
                    );
                }

                $rutaNuevaImagen =
                    'uploads/popup/' .
                    $nombreNuevo;
            }

            $imagenFinal =
                $rutaNuevaImagen
                    ?? $imagenActual;

            $sql = "
                UPDATE popup_home
                SET
                    titulo = :titulo,
                    descripcion = :descripcion,
                    imagen = :imagen,
                    boton_texto = :boton_texto,
                    boton_url = :boton_url,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    estado = :estado
                WHERE id = :id
            ";

            $stmt =
                $pdo->prepare($sql);

            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion !== '' ? $descripcion : null,
                ':imagen' => $imagenFinal,
                ':boton_texto' => $botonTexto !== '' ? $botonTexto : null,
                ':boton_url' => $botonUrl !== '' ? $botonUrl : null,
                ':fecha_inicio' => $fechaInicio !== '' ? $fechaInicio : null,
                ':fecha_fin' => $fechaFin !== '' ? $fechaFin : null,
                ':estado' => $estado,
                ':id' => $id
            ]);

            if (
                $rutaNuevaImagen !== null &&
                !empty($imagenActual)
            ) {

                $rutaAnterior =
                    __DIR__ .
                    '/../../' .
                    $imagenActual;

                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }

            $imagenActual =
                $imagenFinal;

            $mensajeExito =
                'El aviso fue actualizado correctamente.';

        } catch (Throwable $e) {

            if (
                $rutaFisicaNueva !== null &&
                file_exists($rutaFisicaNueva)
            ) {
                unlink($rutaFisicaNueva);
            }

            $errores[] =
                'No fue posible actualizar el aviso.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar aviso | Administración Biblioteca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=20260901-2">
    <link rel="stylesheet" href="../assets/css/admin-modulos.css?v=20260901-4">

</head>

<body>

<div class="admin-shell">

<header class="admin-header">
  <div class="admin-header-inner">

    <a href="../dashboard.php" class="admin-brand" style="text-decoration:none;">
      <img
        src="/bibliotecatenjo/public/assets/img/logobiblio.png"
        alt="Biblioteca Municipal Isabel Murillo de Luque"
        class="admin-brand-logo"
      >

      <div class="admin-brand-copy">
        <strong>Biblioteca Municipal Isabel Murillo de Luque</strong>
        <span>Tenjo - Cundinamarca</span>
      </div>
    </a>

    <div class="admin-user">
      <div class="admin-user-icon" aria-hidden="true">
        <i class="bi bi-person-circle"></i>
      </div>

      <div class="admin-user-copy">
        <strong><?= htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span><?= htmlspecialchars($_SESSION['usuario_rol'], ENT_QUOTES, 'UTF-8') ?></span>
      </div>

      <a href="../logout.php" class="admin-logout" title="Cerrar sesión">
        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
        <span>Cerrar sesión</span>
      </a>
    </div>

  </div>
</header>


<main class="admin-main">
  <div class="admin-container admin-container-form">

    <section class="admin-page-heading">
      <div>
        <a href="index.php" class="admin-back-link">
          <i class="bi bi-arrow-left"></i>
          Volver a avisos
        </a>

        <span class="admin-kicker">Avisos del Home</span>

        <h1>Editar aviso</h1>

        <p>
          Actualiza el contenido, imagen, botón, vigencia
          o estado del aviso seleccionado.
        </p>
      </div>
    </section>

    <?php if ($mensajeExito !== ''): ?>
      <div class="admin-alert admin-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
      <div class="admin-alert admin-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          <?php foreach ($errores as $error): ?>
            <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <form
      method="POST"
      enctype="multipart/form-data"
      class="admin-card admin-form-card"
    >

      <div class="admin-form-section-heading">
        <span class="admin-form-section-icon">
          <i class="bi bi-pencil-square"></i>
        </span>

        <div>
          <h2>Contenido del aviso</h2>
          <p>Modifica la información principal del aviso.</p>
        </div>
      </div>

      <div class="admin-form-group">
        <label for="titulo">
          Título <span class="admin-required">*</span>
        </label>

        <input
          type="text"
          id="titulo"
          name="titulo"
          maxlength="180"
          value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="admin-form-group">
        <label for="descripcion">Descripción</label>

        <textarea
          id="descripcion"
          name="descripcion"
          rows="5"
        ><?= htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="admin-current-media admin-current-media-popup">

        <div class="admin-current-media-copy">
          <strong>Imagen actual</strong>
          <span>
            Si no seleccionas otra imagen, se conservará la actual.
          </span>
        </div>

        <?php if (!empty($imagenActual)): ?>

          <img
            src="/bibliotecatenjo/<?= htmlspecialchars($imagenActual, ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>"
          >

        <?php else: ?>

          <div class="admin-current-media-empty">
            <i class="bi bi-image"></i>
            Este aviso no tiene imagen.
          </div>

        <?php endif; ?>

      </div>

      <div class="admin-form-group">
        <label for="imagen">Cambiar imagen</label>

        <div class="admin-file-field">
          <input
            type="file"
            id="imagen"
            name="imagen"
            accept=".jpg,.jpeg,.png,.webp"
          >

          <small class="admin-help">
            Déjalo vacío para conservar la imagen actual.
            Formatos JPG, JPEG, PNG y WEBP. Máximo 5 MB.
          </small>
        </div>
      </div>

      <div class="admin-form-section-heading admin-form-section-secondary">
        <span class="admin-form-section-icon admin-form-section-icon-yellow">
          <i class="bi bi-link-45deg"></i>
        </span>

        <div>
          <h2>Botón opcional</h2>
          <p>Configura el texto y destino del botón del aviso.</p>
        </div>
      </div>

      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">
          <label for="boton_texto">Texto del botón</label>

          <input
            type="text"
            id="boton_texto"
            name="boton_texto"
            maxlength="100"
            value="<?= htmlspecialchars($botonTexto, ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="admin-form-group">
          <label for="boton_url">Enlace del botón</label>

          <input
            type="text"
            id="boton_url"
            name="boton_url"
            maxlength="255"
            value="<?= htmlspecialchars($botonUrl, ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

      </div>

      <div class="admin-form-section-heading admin-form-section-secondary">
        <span class="admin-form-section-icon admin-form-section-icon-blue">
          <i class="bi bi-clock-history"></i>
        </span>

        <div>
          <h2>Vigencia</h2>
          <p>Ajusta la fecha y hora de publicación del aviso.</p>
        </div>
      </div>

      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">
          <label for="fecha_inicio">Mostrar desde</label>

          <input
            type="datetime-local"
            id="fecha_inicio"
            name="fecha_inicio"
            value="<?= htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

        <div class="admin-form-group">
          <label for="fecha_fin">Mostrar hasta</label>

          <input
            type="datetime-local"
            id="fecha_fin"
            name="fecha_fin"
            value="<?= htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

      </div>

      <div class="admin-check-row">
        <input
          type="checkbox"
          id="estado"
          name="estado"
          value="1"
          <?= $estado ? 'checked' : '' ?>
        >

        <label for="estado">Aviso activo</label>
      </div>

      <div class="admin-form-actions">

        <button
          type="submit"
          class="admin-btn admin-btn-primary"
        >
          <i class="bi bi-check-lg"></i>
          Guardar cambios
        </button>

        <a href="index.php" class="admin-btn admin-btn-light">
          Cancelar
        </a>

      </div>

    </form>

    <footer class="admin-footer">
      <span>Biblioteca Municipal Isabel Murillo de Luque</span>
      <span>Editar aviso</span>
    </footer>

  </div>
</main>

</div>

</body>
</html>