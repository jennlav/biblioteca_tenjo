<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT *
        FROM eventos
        ORDER BY fecha_inicio ASC";

$stmt = $pdo->query($sql);
$eventos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eventos | Administrador Biblioteca Tenjo</title>
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
  <div class="admin-container">

    <section class="admin-page-heading">
      <div>
        <a href="../dashboard.php" class="admin-back-link">
          <i class="bi bi-arrow-left"></i>
          Volver al panel
        </a>

        <span class="admin-kicker">Contenido del sitio</span>

        <h1>Administración de eventos</h1>

        <p>
          Consulta, crea y actualiza los eventos y actividades
          publicados en la agenda de la Biblioteca.
        </p>
      </div>

      <a href="crear.php" class="admin-btn admin-btn-primary">
        <i class="bi bi-plus-lg"></i>
        Crear nuevo evento
      </a>
    </section>

    <section class="admin-card">

      <?php if (empty($eventos)): ?>

        <div class="admin-empty-state">
          <span class="admin-empty-icon">
            <i class="bi bi-calendar-event"></i>
          </span>

          <strong>No hay eventos registrados</strong>

          <p>
            Crea el primer evento para comenzar a publicar
            actividades en la agenda de la Biblioteca.
          </p>

          <a href="crear.php" class="admin-btn admin-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Crear evento
          </a>
        </div>

      <?php else: ?>

        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Imagen</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>
            <?php foreach ($eventos as $evento): ?>
              <tr>

                <td>
                  <?php if (!empty($evento['imagen'])): ?>
                    <img
                      class="admin-thumb"
                      src="/bibliotecatenjo/<?= htmlspecialchars($evento['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                      alt="<?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                  <?php else: ?>
                    <div class="admin-thumb-placeholder">
                      <i class="bi bi-image"></i>
                      <span>Sin imagen</span>
                    </div>
                  <?php endif; ?>
                </td>

                <td>
                  <div class="admin-table-title">
                    <?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>
                  </div>

                  <div class="admin-table-description">
                    <?= htmlspecialchars(
                      mb_strimwidth(
                        preg_replace('/\s+/', ' ', $evento['descripcion']),
                        0,
                        115,
                        '...'
                      ),
                      ENT_QUOTES,
                      'UTF-8'
                    ) ?>
                  </div>
                </td>

                <td>
                  <div class="admin-date-cell">
                    <strong>Inicio</strong>
                    <span>
                      <?= date('d/m/Y', strtotime($evento['fecha_inicio'])) ?>
                    </span>

                    <?php if (!empty($evento['fecha_fin'])): ?>
                      <small>
                        Hasta <?= date('d/m/Y', strtotime($evento['fecha_fin'])) ?>
                      </small>
                    <?php endif; ?>
                  </div>
                </td>

                <td>
                  <?php if (!empty($evento['categoria'])): ?>
                    <span class="admin-badge admin-badge-neutral">
                      <?= htmlspecialchars($evento['categoria'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  <?php else: ?>
                    <span class="admin-muted">Sin categoría</span>
                  <?php endif; ?>
                </td>

                <td>
                  <?php if ((int)$evento['estado'] === 1): ?>
                    <span class="admin-status admin-status-active">Activo</span>
                  <?php else: ?>
                    <span class="admin-status admin-status-inactive">Inactivo</span>
                  <?php endif; ?>
                </td>

                <td>
                  <div class="admin-actions">
                    <a
                      href="editar.php?id=<?= (int)$evento['id'] ?>"
                      class="admin-btn admin-btn-light"
                    >
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </a>

                    <a
                      href="eliminar.php?id=<?= (int)$evento['id'] ?>"
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
      <span>Gestión de eventos</span>
    </footer>

  </div>
</main>
</div>
</body>
</html>