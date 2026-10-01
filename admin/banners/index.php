<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT *
        FROM banners
        ORDER BY orden ASC, id ASC";

$stmt = $pdo->query($sql);
$banners = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    Banners | Administrador Biblioteca Tenjo
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
          Administración de banners
        </h1>

        <p>
          Administra las imágenes, mensajes, botones y orden
          del carrusel principal del Home.
        </p>

      </div>

      <a
        href="crear.php"
        class="admin-btn admin-btn-primary"
      >
        <i class="bi bi-plus-lg"></i>
        Crear nuevo banner
      </a>

    </section>


    <section class="admin-card">

      <?php if (empty($banners)): ?>

        <div class="admin-empty-state">

          <span class="admin-empty-icon">
            <i class="bi bi-images"></i>
          </span>

          <strong>
            No hay banners registrados
          </strong>

          <p>
            Crea el primer banner para comenzar a administrar
            el carrusel principal del sitio web.
          </p>

          <a
            href="crear.php"
            class="admin-btn admin-btn-primary"
          >
            <i class="bi bi-plus-lg"></i>
            Crear banner
          </a>

        </div>

      <?php else: ?>

        <div class="admin-table-wrapper">

          <table class="admin-table admin-table-banners">

            <thead>

              <tr>
                <th>Imagen</th>
                <th>Banner</th>
                <th>Botón 1</th>
                <th>Botón 2</th>
                <th>Orden</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>

            </thead>

            <tbody>

            <?php foreach ($banners as $banner): ?>

              <tr>

                <td>

                  <?php if (!empty($banner['imagen'])): ?>

                    <img
                      class="admin-thumb admin-thumb-banner"
                      src="/bibliotecatenjo/<?= htmlspecialchars(
                        $banner['imagen'],
                        ENT_QUOTES,
                        'UTF-8'
                      ) ?>"
                      alt="<?= htmlspecialchars(
                        $banner['titulo'],
                        ENT_QUOTES,
                        'UTF-8'
                      ) ?>"
                    >

                  <?php else: ?>

                    <div class="admin-thumb-placeholder admin-thumb-banner">
                      <i class="bi bi-image"></i>
                      <span>Sin imagen</span>
                    </div>

                  <?php endif; ?>

                </td>

                <td>

                  <div class="admin-table-title">
                    <?= htmlspecialchars(
                      $banner['titulo'],
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>
                  </div>

                  <div class="admin-table-description">
                    <?= htmlspecialchars(
                      mb_strimwidth(
                        preg_replace(
                          '/\s+/',
                          ' ',
                          $banner['descripcion']
                        ),
                        0,
                        120,
                        '...'
                      ),
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>
                  </div>

                </td>

                <td>

                  <?php if (!empty($banner['boton1_texto'])): ?>

                    <div class="admin-button-info">
                      <strong>
                        <?= htmlspecialchars(
                          $banner['boton1_texto'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>
                      </strong>

                      <small>
                        <?= htmlspecialchars(
                          $banner['boton1_url'] ?? '',
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>
                      </small>
                    </div>

                  <?php else: ?>

                    <span class="admin-muted">
                      Sin botón
                    </span>

                  <?php endif; ?>

                </td>

                <td>

                  <?php if (!empty($banner['boton2_texto'])): ?>

                    <div class="admin-button-info">
                      <strong>
                        <?= htmlspecialchars(
                          $banner['boton2_texto'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>
                      </strong>

                      <small>
                        <?= htmlspecialchars(
                          $banner['boton2_url'] ?? '',
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>
                      </small>
                    </div>

                  <?php else: ?>

                    <span class="admin-muted">
                      Sin botón
                    </span>

                  <?php endif; ?>

                </td>

                <td>

                  <span class="admin-order-badge">
                    <?= (int)$banner['orden'] ?>
                  </span>

                </td>

                <td>

                  <?php if ((int)$banner['estado'] === 1): ?>

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

                  <div class="admin-actions">

                    <a
                      href="editar.php?id=<?= (int)$banner['id'] ?>"
                      class="admin-btn admin-btn-light"
                    >
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </a>

                    <a
                      href="eliminar.php?id=<?= (int)$banner['id'] ?>"
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
        Gestión de banners
      </span>

    </footer>

  </div>

</main>

</div>

</body>
</html>
