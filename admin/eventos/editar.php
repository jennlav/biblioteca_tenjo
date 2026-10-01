<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Evento no válido.');
}

$sql = "SELECT *
        FROM eventos
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$evento = $stmt->fetch();

if (!$evento) {
    die('Evento no encontrado.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $categoria = trim($_POST['categoria'] ?? '');
    $estado = isset($_POST['estado']) ? 1 : 0;

    $rutaImagen = $evento['imagen'];

    if (!$titulo || !$descripcion || !$fecha_inicio) {
        $error = 'Debes completar los campos obligatorios.';
    }

    if (
        !$error &&
        isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {

            $error =
                'Ocurrió un error al cargar la nueva imagen.';

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

            if ($tamanoArchivo > 5 * 1024 * 1024) {
                $error =
                    'La imagen no puede superar los 5 MB.';
            }

            if (!$error) {

                $directorioDestino =
                    __DIR__ .
                    '/../../uploads/eventos/';

                if (!is_dir($directorioDestino)) {
                    mkdir(
                        $directorioDestino,
                        0755,
                        true
                    );
                }

                $nombreArchivo =
                    'evento_' .
                    time() .
                    '_' .
                    bin2hex(random_bytes(4)) .
                    '.' .
                    $extension;

                $rutaFisicaNueva =
                    $directorioDestino .
                    $nombreArchivo;

                if (
                    move_uploaded_file(
                        $archivoTemporal,
                        $rutaFisicaNueva
                    )
                ) {

                    $rutaImagen =
                        'uploads/eventos/' .
                        $nombreArchivo;

                    if (!empty($evento['imagen'])) {

                        $rutaFisicaAnterior =
                            __DIR__ .
                            '/../../' .
                            $evento['imagen'];

                        if (
                            file_exists(
                                $rutaFisicaAnterior
                            )
                        ) {
                            unlink(
                                $rutaFisicaAnterior
                            );
                        }
                    }

                } else {

                    $error =
                        'No fue posible guardar la nueva imagen.';
                }
            }
        }
    }

    if (!$error) {

        $sql = "UPDATE eventos
                SET
                    titulo = :titulo,
                    descripcion = :descripcion,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    categoria = :categoria,
                    imagen = :imagen,
                    estado = :estado
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin ?: null,
            ':categoria' => $categoria ?: null,
            ':imagen' => $rutaImagen,
            ':estado' => $estado,
            ':id' => $id
        ]);

        header('Location: index.php');
        exit;
    }

    $evento['titulo'] = $titulo;
    $evento['descripcion'] = $descripcion;
    $evento['fecha_inicio'] = $fecha_inicio;
    $evento['fecha_fin'] = $fecha_fin;
    $evento['categoria'] = $categoria;
    $evento['estado'] = $estado;
    $evento['imagen'] = $rutaImagen;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar evento | Biblioteca Tenjo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=20260901-2">
    <link rel="stylesheet" href="../assets/css/admin-modulos.css?v=20260901-1">

</head>
<body>
<div class="admin-shell">

<header class="admin-header">
  <div class="admin-header-inner">
    <a href="../dashboard.php" class="admin-brand" style="text-decoration:none;">
      <img src="/bibliotecatenjo/public/assets/img/logobiblio.png"
           alt="Biblioteca Municipal Isabel Murillo de Luque"
           class="admin-brand-logo">

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
        <a href="index.php" class="admin-back-link">
          <i class="bi bi-arrow-left"></i>
          Volver a eventos
        </a>

        <span class="admin-kicker">Gestión de eventos</span>

        <h1>Editar evento</h1>

        <p>
          Actualiza la información, imagen o estado de
          publicación del evento seleccionado.
        </p>
      </div>
    </section>

    <?php if ($error): ?>
      <div class="admin-alert admin-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="POST"
          enctype="multipart/form-data"
          class="admin-card admin-form-card">

      <div class="admin-form-section-heading">
        <span class="admin-form-section-icon">
          <i class="bi bi-pencil-square"></i>
        </span>

        <div>
          <h2>Información del evento</h2>
          <p>Modifica únicamente los datos que necesites actualizar.</p>
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
          value="<?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>"
          required
        >
      </div>

      <div class="admin-form-group">
        <label for="descripcion">
          Descripción <span class="admin-required">*</span>
        </label>

        <textarea
          id="descripcion"
          name="descripcion"
          rows="6"
          required
        ><?= htmlspecialchars($evento['descripcion'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">
          <label for="fecha_inicio">
            Fecha de inicio <span class="admin-required">*</span>
          </label>

          <input
            type="date"
            id="fecha_inicio"
            name="fecha_inicio"
            value="<?= htmlspecialchars($evento['fecha_inicio'], ENT_QUOTES, 'UTF-8') ?>"
            required
          >
        </div>

        <div class="admin-form-group">
          <label for="fecha_fin">
            Fecha de fin
          </label>

          <input
            type="date"
            id="fecha_fin"
            name="fecha_fin"
            value="<?= htmlspecialchars($evento['fecha_fin'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

      </div>

      <div class="admin-form-group">
        <label for="categoria">
          Categoría
        </label>

        <input
          type="text"
          id="categoria"
          name="categoria"
          value="<?= htmlspecialchars($evento['categoria'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >
      </div>

      <div class="admin-current-media">
        <div class="admin-current-media-copy">
          <strong>Imagen actual</strong>
          <span>
            La imagen se conservará si no seleccionas una nueva.
          </span>
        </div>

        <?php if (!empty($evento['imagen'])): ?>
          <img
            src="/bibliotecatenjo/<?= htmlspecialchars($evento['imagen'], ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>"
          >
        <?php else: ?>
          <div class="admin-current-media-empty">
            <i class="bi bi-image"></i>
            Este evento no tiene imagen.
          </div>
        <?php endif; ?>
      </div>

      <div class="admin-form-group">
        <label for="imagen">
          Reemplazar imagen
        </label>

        <div class="admin-file-field">
          <input
            type="file"
            id="imagen"
            name="imagen"
            accept=".jpg,.jpeg,.png,.webp"
          >

          <small class="admin-help">
            Déjalo vacío para conservar la imagen actual.
            Formatos: JPG, JPEG, PNG y WEBP. Máximo 5 MB.
          </small>
        </div>
      </div>

      <div class="admin-check-row">
        <input
          type="checkbox"
          id="estado"
          name="estado"
          value="1"
          <?= (int)$evento['estado'] === 1 ? 'checked' : '' ?>
        >

        <label for="estado">
          Evento activo
        </label>
      </div>

      <div class="admin-form-actions">
        <button type="submit" class="admin-btn admin-btn-primary">
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
      <span>Editar evento</span>
    </footer>

  </div>
</main>
</div>
</body>
</html>