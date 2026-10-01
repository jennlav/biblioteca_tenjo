<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT *
        FROM documentos
        ORDER BY orden ASC, fecha_documento DESC, id DESC";

$stmt = $pdo->query($sql);
$documentos = $stmt->fetchAll();

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
        Documentos | Administrador Biblioteca Tenjo
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
          Documentación Pública
        </h1>

        <p>
          Administra los documentos institucionales disponibles
          para consulta pública en el portal de la Biblioteca.
        </p>

      </div>

      <a
        href="crear.php"
        class="admin-btn admin-btn-primary"
      >
        <i class="bi bi-cloud-arrow-up"></i>
        Cargar nuevo documento
      </a>

    </section>


    <section class="admin-card">

      <?php if (empty($documentos)): ?>

        <div class="admin-empty-state">

          <span class="admin-empty-icon">
            <i class="bi bi-file-earmark-text"></i>
          </span>

          <strong>
            No hay documentos registrados
          </strong>

          <p>
            Carga el primer documento para comenzar a publicarlo
            en la sección de Documentación Pública.
          </p>

          <a
            href="crear.php"
            class="admin-btn admin-btn-primary"
          >
            <i class="bi bi-cloud-arrow-up"></i>
            Cargar documento
          </a>

        </div>

      <?php else: ?>

        <div class="admin-table-wrapper">

          <table class="admin-table admin-table-documents">

            <thead>

              <tr>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Tamaño</th>
                <th>Orden</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th>Acciones</th>
              </tr>

            </thead>

            <tbody>

            <?php foreach ($documentos as $documento): ?>

              <?php

              $bytes =
                  (int)(
                      $documento['tamano_archivo']
                      ?? 0
                  );

              if ($bytes >= 1024 * 1024) {

                  $tamanoTexto =
                      number_format(
                          $bytes / (1024 * 1024),
                          1,
                          ',',
                          '.'
                      ) .
                      ' MB';

              } elseif ($bytes > 0) {

                  $tamanoTexto =
                      number_format(
                          $bytes / 1024,
                          1,
                          ',',
                          '.'
                      ) .
                      ' KB';

              } else {

                  $tamanoTexto =
                      '-';
              }

              $tipo =
                  strtoupper(
                      $documento['tipo_archivo']
                      ?? ''
                  );

              ?>

              <tr>

                <td>

                  <div class="admin-document-cell">

                    <span class="admin-document-icon admin-document-<?= strtolower(
                      htmlspecialchars(
                        $tipo,
                        ENT_QUOTES,
                        'UTF-8'
                      )
                    ) ?>">
                      <i class="bi bi-file-earmark-text"></i>
                    </span>

                    <div>

                      <div class="admin-table-title">
                        <?= htmlspecialchars(
                          $documento['titulo'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>
                      </div>

                      <?php if (!empty($documento['descripcion'])): ?>

                        <div class="admin-table-description">
                          <?= htmlspecialchars(
                            mb_strimwidth(
                              preg_replace(
                                '/\s+/',
                                ' ',
                                $documento['descripcion']
                              ),
                              0,
                              125,
                              '...'
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                          ) ?>
                        </div>

                      <?php endif; ?>

                      <?php if (!empty($documento['nombre_archivo_original'])): ?>

                        <small class="admin-document-filename">
                          <?= htmlspecialchars(
                            $documento['nombre_archivo_original'],
                            ENT_QUOTES,
                            'UTF-8'
                          ) ?>
                        </small>

                      <?php endif; ?>

                    </div>

                  </div>

                </td>

                <td>

                  <?php if (!empty($documento['fecha_documento'])): ?>

                    <span class="admin-date-simple">
                      <?= date(
                        'd/m/Y',
                        strtotime(
                          $documento['fecha_documento']
                        )
                      ) ?>
                    </span>

                  <?php else: ?>

                    <span class="admin-muted">
                      Sin fecha
                    </span>

                  <?php endif; ?>

                </td>

                <td>

                  <?php if ($tipo !== ''): ?>

                    <span class="admin-file-type">
                      <?= htmlspecialchars(
                        $tipo,
                        ENT_QUOTES,
                        'UTF-8'
                      ) ?>
                    </span>

                  <?php else: ?>

                    <span class="admin-muted">
                      -
                    </span>

                  <?php endif; ?>

                </td>

                <td>
                  <span class="admin-muted admin-size-text">
                    <?= htmlspecialchars(
                      $tamanoTexto,
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>
                  </span>
                </td>

                <td>
                  <span class="admin-order-badge">
                    <?= (int)$documento['orden'] ?>
                  </span>
                </td>

                <td>

                  <?php if ((int)$documento['estado'] === 1): ?>

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

                  <a
                    href="/bibliotecatenjo/<?= htmlspecialchars(
                      $documento['archivo'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-btn admin-btn-file"
                  >
                    <i class="bi bi-box-arrow-up-right"></i>
                    Ver archivo
                  </a>

                </td>

                <td>

                  <div class="admin-actions">

                    <a
                      href="editar.php?id=<?= (int)$documento['id'] ?>"
                      class="admin-btn admin-btn-light"
                    >
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </a>

                    <a
                      href="eliminar.php?id=<?= (int)$documento['id'] ?>"
                      class="admin-btn admin-btn-danger"
                    >
                      <i class="bi bi-trash3"></i>
                      Eliminar
                    </a>

                  </div>

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
        Gestión de documentos
      </span>

    </footer>

  </div>

</main>

</div>

</body>

</html>