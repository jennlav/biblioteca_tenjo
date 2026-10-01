<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

try {
    $stmt = $pdo->prepare("
        SELECT
            id,
            titulo,
            descripcion,
            imagen,
            boton_texto,
            boton_url,
            fecha_inicio,
            fecha_fin,
            estado,
            created_at,
            updated_at
        FROM popup_home
        WHERE estado <> 2
        ORDER BY id DESC
    ");

    $stmt->execute();
    $avisos = $stmt->fetchAll();

} catch (PDOException $e) {
    $avisos = [];
}

function obtenerEstadoAviso(array $aviso): array
{
    if ((int)$aviso['estado'] === 0) {
        return [
            'texto' => 'Inactivo',
            'clase' => 'admin-status-inactive'
        ];
    }

    if ((int)$aviso['estado'] === 2) {
        return [
            'texto' => 'Eliminado',
            'clase' => 'admin-status-deleted'
        ];
    }

    $ahora = new DateTime();

    if (!empty($aviso['fecha_inicio'])) {
        $inicio = new DateTime($aviso['fecha_inicio']);

        if ($ahora < $inicio) {
            return [
                'texto' => 'Programado',
                'clase' => 'admin-status-scheduled'
            ];
        }
    }

    if (!empty($aviso['fecha_fin'])) {
        $fin = new DateTime($aviso['fecha_fin']);

        if ($ahora > $fin) {
            return [
                'texto' => 'Finalizado',
                'clase' => 'admin-status-finished'
            ];
        }
    }

    return [
        'texto' => 'Activo',
        'clase' => 'admin-status-active'
    ];
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Avisos del Home | Administración Biblioteca</title>
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
  <div class="admin-container">

    <section class="admin-page-heading">

      <div>
        <a href="../dashboard.php" class="admin-back-link">
          <i class="bi bi-arrow-left"></i>
          Volver al panel
        </a>

        <span class="admin-kicker">Contenido del sitio</span>

        <h1>Avisos del Home</h1>

        <p>
          Administra los avisos emergentes que pueden aparecer
          en la página principal de la Biblioteca.
        </p>
      </div>

      <a href="crear.php" class="admin-btn admin-btn-primary">
        <i class="bi bi-plus-lg"></i>
        Crear aviso
      </a>

    </section>

    <section class="admin-card">

      <?php if (empty($avisos)): ?>

        <div class="admin-empty-state">
          <span class="admin-empty-icon">
            <i class="bi bi-window-stack"></i>
          </span>

          <strong>No hay avisos registrados</strong>

          <p>
            Crea un aviso para mostrar información destacada
            temporalmente en el Home.
          </p>

          <a href="crear.php" class="admin-btn admin-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Crear aviso
          </a>
        </div>

      <?php else: ?>

        <div class="admin-table-wrapper">
          <table class="admin-table admin-table-popup">

            <thead>
              <tr>
                <th>Imagen</th>
                <th>Aviso</th>
                <th>Vigencia</th>
                <th>Botón</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>

            <?php foreach ($avisos as $aviso): ?>

              <?php $estadoAviso = obtenerEstadoAviso($aviso); ?>

              <tr>

                <td>
                  <?php if (!empty($aviso['imagen'])): ?>

                    <img
                      class="admin-thumb admin-thumb-popup"
                      src="/bibliotecatenjo/<?= htmlspecialchars($aviso['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                      alt="<?= htmlspecialchars($aviso['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                  <?php else: ?>

                    <div class="admin-thumb-placeholder admin-thumb-popup">
                      <i class="bi bi-image"></i>
                      <span>Sin imagen</span>
                    </div>

                  <?php endif; ?>
                </td>

                <td>
                  <div class="admin-table-title">
                    <?= htmlspecialchars($aviso['titulo'], ENT_QUOTES, 'UTF-8') ?>
                  </div>

                  <?php if (!empty($aviso['descripcion'])): ?>
                    <div class="admin-table-description">
                      <?= htmlspecialchars(
                        mb_strimwidth(
                          preg_replace('/\s+/', ' ', $aviso['descripcion']),
                          0,
                          125,
                          '...'
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                      ) ?>
                    </div>
                  <?php endif; ?>
                </td>

                <td>
                  <div class="admin-popup-dates">
                    <span>
                      <strong>Desde:</strong>
                      <?= !empty($aviso['fecha_inicio'])
                          ? date('d/m/Y H:i', strtotime($aviso['fecha_inicio']))
                          : 'Sin fecha'
                      ?>
                    </span>

                    <span>
                      <strong>Hasta:</strong>
                      <?= !empty($aviso['fecha_fin'])
                          ? date('d/m/Y H:i', strtotime($aviso['fecha_fin']))
                          : 'Sin fecha'
                      ?>
                    </span>
                  </div>
                </td>

                <td>
                  <?php if (!empty($aviso['boton_texto'])): ?>

                    <div class="admin-button-info">
                      <strong>
                        <?= htmlspecialchars($aviso['boton_texto'], ENT_QUOTES, 'UTF-8') ?>
                      </strong>

                      <?php if (!empty($aviso['boton_url'])): ?>
                        <small>
                          <?= htmlspecialchars($aviso['boton_url'], ENT_QUOTES, 'UTF-8') ?>
                        </small>
                      <?php endif; ?>
                    </div>

                  <?php else: ?>

                    <span class="admin-muted">Sin botón</span>

                  <?php endif; ?>
                </td>

                <td>
                  <span class="admin-status <?= htmlspecialchars($estadoAviso['clase'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($estadoAviso['texto'], ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>

                <td>
                  <div class="admin-actions">

                    <a
                      href="editar.php?id=<?= (int)$aviso['id'] ?>"
                      class="admin-btn admin-btn-light"
                    >
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </a>

                    <a
                      href="eliminar.php?id=<?= (int)$aviso['id'] ?>"
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
      <span>Biblioteca Municipal Isabel Murillo de Luque</span>
      <span>Gestión de avisos del Home</span>
    </footer>

  </div>
</main>

</div>

</body>
</html>