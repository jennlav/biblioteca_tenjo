<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$mensaje = '';
$error = '';

$titulo = '';
$descripcion = '';

$boton1Texto = '';
$boton1Url = '';

$boton2Texto = '';
$boton2Url = '';

$orden = 1;
$estado = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    $boton1Texto = trim($_POST['boton1_texto'] ?? '');
    $boton1Url = trim($_POST['boton1_url'] ?? '');

    $boton2Texto = trim($_POST['boton2_texto'] ?? '');
    $boton2Url = trim($_POST['boton2_url'] ?? '');

    $orden = (int)($_POST['orden'] ?? 1);
    $estado = isset($_POST['estado']) ? 1 : 0;

    $rutaImagen = null;

    if (!$titulo || !$descripcion) {
        $error =
            'Debes completar el título y la descripción.';
    }

    if (
        !$error &&
        (
            ($boton1Texto && !$boton1Url) ||
            (!$boton1Texto && $boton1Url)
        )
    ) {
        $error =
            'El botón 1 debe tener texto y enlace.';
    }

    if (
        !$error &&
        (
            ($boton2Texto && !$boton2Url) ||
            (!$boton2Texto && $boton2Url)
        )
    ) {
        $error =
            'El botón 2 debe tener texto y enlace.';
    }

    if (
        !$error &&
        (
            !isset($_FILES['imagen']) ||
            $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE
        )
    ) {
        $error =
            'Debes seleccionar una imagen para el banner.';
    }

    if (
        !$error &&
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {

            $error =
                'Ocurrió un error al cargar la imagen.';

        } else {

            $archivoTemporal =
                $_FILES['imagen']['tmp_name'];

            $nombreOriginal =
                $_FILES['imagen']['name'];

            $tamanoArchivo =
                $_FILES['imagen']['size'];

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

            if (
                !in_array(
                    $extension,
                    $extensionesPermitidas,
                    true
                )
            ) {
                $error =
                    'La imagen debe ser JPG, JPEG, PNG o WEBP.';
            }

            if (
                $tamanoArchivo >
                5 * 1024 * 1024
            ) {
                $error =
                    'La imagen no puede superar los 5 MB.';
            }

            if (!$error) {

                $dimensiones =
                    getimagesize(
                        $archivoTemporal
                    );

                if ($dimensiones === false) {

                    $error =
                        'El archivo seleccionado no es una imagen válida.';

                } else {

                    $ancho =
                        (int)$dimensiones[0];

                    $alto =
                        (int)$dimensiones[1];

                    if (
                        $ancho < 1200 ||
                        $alto < 500
                    ) {

                        $error =
                            'La imagen es demasiado pequeña. ' .
                            'Se recomienda utilizar banners de 2000 × 854 px.';
                    }
                }
            }

            if (!$error) {

                $directorioDestino =
                    __DIR__ .
                    '/../../uploads/banners/';

                if (!is_dir($directorioDestino)) {
                    mkdir(
                        $directorioDestino,
                        0755,
                        true
                    );
                }

                $nombreArchivo =
                    'banner_' .
                    time() .
                    '_' .
                    bin2hex(
                        random_bytes(4)
                    ) .
                    '.' .
                    $extension;

                $rutaFisica =
                    $directorioDestino .
                    $nombreArchivo;

                if (
                    move_uploaded_file(
                        $archivoTemporal,
                        $rutaFisica
                    )
                ) {

                    $rutaImagen =
                        'uploads/banners/' .
                        $nombreArchivo;

                } else {

                    $error =
                        'No fue posible guardar la imagen del banner.';
                }
            }
        }
    }

    if (!$error) {

        $sql = "INSERT INTO banners
                (
                    titulo,
                    descripcion,
                    imagen,
                    boton1_texto,
                    boton1_url,
                    boton2_texto,
                    boton2_url,
                    orden,
                    estado
                )
                VALUES
                (
                    :titulo,
                    :descripcion,
                    :imagen,
                    :boton1_texto,
                    :boton1_url,
                    :boton2_texto,
                    :boton2_url,
                    :orden,
                    :estado
                )";

        $stmt =
            $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' =>
                $titulo,

            ':descripcion' =>
                $descripcion,

            ':imagen' =>
                $rutaImagen,

            ':boton1_texto' =>
                $boton1Texto ?: null,

            ':boton1_url' =>
                $boton1Url ?: null,

            ':boton2_texto' =>
                $boton2Texto ?: null,

            ':boton2_url' =>
                $boton2Url ?: null,

            ':orden' =>
                $orden > 0
                    ? $orden
                    : 1,

            ':estado' =>
                $estado
        ]);

        $mensaje =
            'Banner creado correctamente.';

        $titulo = '';
        $descripcion = '';
        $boton1Texto = '';
        $boton1Url = '';
        $boton2Texto = '';
        $boton2Url = '';
        $orden = 1;
        $estado = 1;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    Crear banner | Biblioteca Tenjo
</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=20260901-2">
    <link rel="stylesheet" href="../assets/css/admin-modulos.css?v=20260901-2">

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
        <strong>
          <?= htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8') ?>
        </strong>
        <span>
          <?= htmlspecialchars($_SESSION['usuario_rol'], ENT_QUOTES, 'UTF-8') ?>
        </span>
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

        <a
          href="index.php"
          class="admin-back-link"
        >
          <i class="bi bi-arrow-left"></i>
          Volver a banners
        </a>

        <span class="admin-kicker">
          Gestión de banners
        </span>

        <h1>
          Crear banner
        </h1>

        <p>
          Configura una nueva diapositiva para el carrusel
          principal de la página de inicio.
        </p>

      </div>

    </section>


    <?php if ($mensaje): ?>

      <div class="admin-alert admin-alert-success">

        <i class="bi bi-check-circle-fill"></i>

        <?= htmlspecialchars(
          $mensaje,
          ENT_QUOTES,
          'UTF-8'
        ) ?>

      </div>

    <?php endif; ?>


    <?php if ($error): ?>

      <div class="admin-alert admin-alert-error">

        <i class="bi bi-exclamation-circle-fill"></i>

        <?= htmlspecialchars(
          $error,
          ENT_QUOTES,
          'UTF-8'
        ) ?>

      </div>

    <?php endif; ?>


    <form
      method="POST"
      enctype="multipart/form-data"
      class="admin-card admin-form-card"
    >

      <div class="admin-form-section-heading">

        <span class="admin-form-section-icon">
          <i class="bi bi-images"></i>
        </span>

        <div>

          <h2>
            Información principal
          </h2>

          <p>
            Define el contenido que aparecerá sobre la imagen.
          </p>

        </div>

      </div>


      <div class="admin-form-group">

        <label for="titulo">
          Título
          <span class="admin-required">*</span>
        </label>

        <input
          type="text"
          id="titulo"
          name="titulo"
          value="<?= htmlspecialchars(
            $titulo,
            ENT_QUOTES,
            'UTF-8'
          ) ?>"
          required
        >

      </div>


      <div class="admin-form-group">

        <label for="descripcion">
          Descripción
          <span class="admin-required">*</span>
        </label>

        <textarea
          id="descripcion"
          name="descripcion"
          rows="5"
          required
        ><?= htmlspecialchars(
          $descripcion,
          ENT_QUOTES,
          'UTF-8'
        ) ?></textarea>

      </div>


      <div class="admin-form-group">

        <label for="imagen">
          Imagen del banner
          <span class="admin-required">*</span>
        </label>

        <div class="admin-file-field">

          <input
            type="file"
            id="imagen"
            name="imagen"
            accept=".jpg,.jpeg,.png,.webp"
            required
          >

          <small class="admin-help">
            Dimensión recomendada: 2000 × 854 px.
            Imagen horizontal con proporción aproximada 2.34:1.
            Formatos JPG, JPEG, PNG y WEBP. Máximo 5 MB.
          </small>

        </div>

      </div>


      <div class="admin-form-section-heading admin-form-section-secondary">

        <span class="admin-form-section-icon admin-form-section-icon-yellow">
          <i class="bi bi-link-45deg"></i>
        </span>

        <div>

          <h2>
            Botones del banner
          </h2>

          <p>
            Son opcionales. Si utilizas uno, debes completar
            tanto el texto como el enlace.
          </p>

        </div>

      </div>


      <div class="admin-option-panel">

        <div class="admin-option-panel-title">
          Botón 1
        </div>

        <div class="admin-form-grid admin-form-grid-2">

          <div class="admin-form-group">

            <label for="boton1_texto">
              Texto del botón
            </label>

            <input
              type="text"
              id="boton1_texto"
              name="boton1_texto"
              value="<?= htmlspecialchars(
                $boton1Texto,
                ENT_QUOTES,
                'UTF-8'
              ) ?>"
              placeholder="Ej: Ver Noticias"
            >

          </div>

          <div class="admin-form-group">

            <label for="boton1_url">
              Enlace del botón
            </label>

            <input
              type="text"
              id="boton1_url"
              name="boton1_url"
              value="<?= htmlspecialchars(
                $boton1Url,
                ENT_QUOTES,
                'UTF-8'
              ) ?>"
              placeholder="Ej: noticias.html"
            >

          </div>

        </div>

      </div>


      <div class="admin-option-panel">

        <div class="admin-option-panel-title">
          Botón 2
        </div>

        <div class="admin-form-grid admin-form-grid-2">

          <div class="admin-form-group">

            <label for="boton2_texto">
              Texto del botón
            </label>

            <input
              type="text"
              id="boton2_texto"
              name="boton2_texto"
              value="<?= htmlspecialchars(
                $boton2Texto,
                ENT_QUOTES,
                'UTF-8'
              ) ?>"
              placeholder="Ej: Ver Servicios"
            >

          </div>

          <div class="admin-form-group">

            <label for="boton2_url">
              Enlace del botón
            </label>

            <input
              type="text"
              id="boton2_url"
              name="boton2_url"
              value="<?= htmlspecialchars(
                $boton2Url,
                ENT_QUOTES,
                'UTF-8'
              ) ?>"
              placeholder="Ej: servicios.html"
            >

          </div>

        </div>

      </div>


      <div class="admin-form-section-heading admin-form-section-secondary">

        <span class="admin-form-section-icon">
          <i class="bi bi-sliders"></i>
        </span>

        <div>

          <h2>
            Publicación
          </h2>

          <p>
            Define la posición del banner dentro del carrusel.
          </p>

        </div>

      </div>


      <div class="admin-form-group admin-order-field">

        <label for="orden">
          Orden de aparición
          <span class="admin-required">*</span>
        </label>

        <input
          type="number"
          id="orden"
          name="orden"
          min="1"
          value="<?= (int)$orden ?>"
          required
        >

        <small class="admin-help">
          Ejemplo: 1 aparece primero, 2 aparece después, etc.
        </small>

      </div>


      <div class="admin-check-row">

        <input
          type="checkbox"
          id="estado"
          name="estado"
          value="1"
          <?= $estado ? 'checked' : '' ?>
        >

        <label for="estado">
          Publicar banner como activo
        </label>

      </div>


      <div class="admin-form-actions">

        <button
          type="submit"
          class="admin-btn admin-btn-primary"
        >
          <i class="bi bi-check-lg"></i>
          Guardar banner
        </button>

        <a
          href="index.php"
          class="admin-btn admin-btn-light"
        >
          Cancelar
        </a>

      </div>

    </form>


    <footer class="admin-footer">

      <span>
        Biblioteca Municipal Isabel Murillo de Luque
      </span>

      <span>
        Crear banner
      </span>

    </footer>

  </div>

</main>

</div>

</body>

</html>