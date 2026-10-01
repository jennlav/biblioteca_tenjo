<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$mensaje = '';
$error = '';

$titulo = '';
$descripcion = '';
$fechaDocumento = '';
$orden = 1;
$estado = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    $fechaDocumento =
        $_POST['fecha_documento']
        ?? null;

    $orden =
        (int)(
            $_POST['orden']
            ?? 1
        );

    $estado =
        isset($_POST['estado'])
            ? 1
            : 0;

    $rutaArchivo = null;
    $nombreOriginal = null;
    $tipoArchivo = null;
    $tamanoArchivo = null;

    if (!$titulo) {

        $error =
            'Debes ingresar el título del documento.';
    }

    if (
        !$error &&
        (
            !isset($_FILES['archivo']) ||
            $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE
        )
    ) {

        $error =
            'Debes seleccionar un archivo.';
    }

    if (
        !$error &&
        isset($_FILES['archivo']) &&
        $_FILES['archivo']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if (
            $_FILES['archivo']['error'] !== UPLOAD_ERR_OK
        ) {

            $error =
                'Ocurrió un error al cargar el archivo.';

        } else {

            $archivoTemporal =
                $_FILES['archivo']['tmp_name'];

            $nombreOriginal =
                $_FILES['archivo']['name'];

            $tamanoArchivo =
                $_FILES['archivo']['size'];

            $extension =
                strtolower(
                    pathinfo(
                        $nombreOriginal,
                        PATHINFO_EXTENSION
                    )
                );

            $extensionesPermitidas = [
                'pdf',
                'doc',
                'docx',
                'xls',
                'xlsx'
            ];

            if (
                !in_array(
                    $extension,
                    $extensionesPermitidas,
                    true
                )
            ) {

                $error =
                    'El archivo debe ser PDF, DOC, DOCX, XLS o XLSX.';
            }

            if (
                $tamanoArchivo >
                25 * 1024 * 1024
            ) {

                $error =
                    'El archivo no puede superar los 25 MB.';
            }

            if (!$error) {

                $directorioDestino =
                    __DIR__ .
                    '/../../uploads/documentos/';

                if (!is_dir($directorioDestino)) {

                    mkdir(
                        $directorioDestino,
                        0755,
                        true
                    );
                }

                $nombreArchivo =
                    'documento_' .
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

                    $rutaArchivo =
                        'uploads/documentos/' .
                        $nombreArchivo;

                    $tipoArchivo =
                        strtoupper(
                            $extension
                        );

                } else {

                    $error =
                        'No fue posible guardar el archivo.';
                }
            }
        }
    }

    if (!$error) {

        $sql =
            "INSERT INTO documentos
            (
                titulo,
                descripcion,
                archivo,
                nombre_archivo_original,
                tipo_archivo,
                tamano_archivo,
                fecha_documento,
                orden,
                estado
            )
            VALUES
            (
                :titulo,
                :descripcion,
                :archivo,
                :nombre_archivo_original,
                :tipo_archivo,
                :tamano_archivo,
                :fecha_documento,
                :orden,
                :estado
            )";

        $stmt =
            $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' =>
                $titulo,

            ':descripcion' =>
                $descripcion ?: null,

            ':archivo' =>
                $rutaArchivo,

            ':nombre_archivo_original' =>
                $nombreOriginal,

            ':tipo_archivo' =>
                $tipoArchivo,

            ':tamano_archivo' =>
                $tamanoArchivo,

            ':fecha_documento' =>
                $fechaDocumento ?: null,

            ':orden' =>
                $orden > 0
                    ? $orden
                    : 1,

            ':estado' =>
                $estado
        ]);

        $mensaje =
            'Documento creado correctamente.';

        $titulo = '';
        $descripcion = '';
        $fechaDocumento = '';
        $orden = 1;
        $estado = 1;
    }
}

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
        Crear documento | Biblioteca Tenjo
    </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=20260901-2">
    <link rel="stylesheet" href="../assets/css/admin-modulos.css?v=20260901-3">

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

      <a
        href="../logout.php"
        class="admin-logout"
        title="Cerrar sesión"
      >
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
          Volver a documentos
        </a>

        <span class="admin-kicker">
          Documentación Pública
        </span>

        <h1>
          Cargar documento
        </h1>

        <p>
          Publica un nuevo documento institucional para consulta
          desde el sitio web de la Biblioteca.
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
          <i class="bi bi-file-earmark-text"></i>
        </span>

        <div>

          <h2>
            Información del documento
          </h2>

          <p>
            Completa los datos que identificarán el archivo
            en la sección pública.
          </p>

        </div>

      </div>


      <div class="admin-form-group">

        <label for="titulo">
          Título del documento
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
        </label>

        <textarea
          id="descripcion"
          name="descripcion"
          rows="5"
          placeholder="Descripción breve del contenido del documento"
        ><?= htmlspecialchars(
          $descripcion,
          ENT_QUOTES,
          'UTF-8'
        ) ?></textarea>

      </div>


      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">

          <label for="fecha_documento">
            Fecha del documento
          </label>

          <input
            type="date"
            id="fecha_documento"
            name="fecha_documento"
            value="<?= htmlspecialchars(
              $fechaDocumento ?? '',
              ENT_QUOTES,
              'UTF-8'
            ) ?>"
          >

        </div>


        <div class="admin-form-group">

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

        </div>

      </div>


      <div class="admin-form-section-heading admin-form-section-secondary">

        <span class="admin-form-section-icon admin-form-section-icon-blue">
          <i class="bi bi-cloud-arrow-up"></i>
        </span>

        <div>

          <h2>
            Archivo
          </h2>

          <p>
            Selecciona el documento que se almacenará
            en el servidor.
          </p>

        </div>

      </div>


      <div class="admin-form-group">

        <label for="archivo">
          Archivo
          <span class="admin-required">*</span>
        </label>

        <div class="admin-file-field admin-file-field-document">

          <input
            type="file"
            id="archivo"
            name="archivo"
            accept=".pdf,.doc,.docx,.xls,.xlsx"
            required
          >

          <small class="admin-help">
            Formatos permitidos: PDF, DOC, DOCX, XLS y XLSX.
            Tamaño máximo: 25 MB.
          </small>

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

        <label for="estado">
          Publicar documento como activo
        </label>

      </div>


      <div class="admin-form-actions">

        <button
          type="submit"
          class="admin-btn admin-btn-primary"
        >
          <i class="bi bi-cloud-arrow-up"></i>
          Guardar documento
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
        Cargar documento
      </span>

    </footer>

  </div>

</main>

</div>

</body>

</html>