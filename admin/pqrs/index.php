<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$sql = "SELECT id, tipo_solicitante, nombre_completo, tipo_documento,
               numero_documento, correo, telefono, tipo_pqrs,
               descripcion, archivo, nombre_archivo_original,
               tipo_archivo, tamano_archivo, estado, correo_enviado,
               created_at
        FROM pqrs
        ORDER BY created_at DESC, id DESC";

$stmt = $pdo->query($sql);
$solicitudes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PQRSDF | Administrador Biblioteca Tenjo</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/admin.css?v=20260901-2">
<link rel="stylesheet" href="../assets/css/admin-modulos.css?v=20260902-3">
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

        <span class="admin-kicker">Solicitudes y atención</span>

        <h1>PQRSDF</h1>

        <p>
          Consulta las peticiones, quejas, reclamos, sugerencias,
          denuncias y felicitaciones recibidas.
        </p>
      </div>
    </section>

    <section class="admin-card">

      <?php if (empty($solicitudes)): ?>

        <div class="admin-empty-state">
          <span class="admin-empty-icon">
            <i class="bi bi-chat-left-text"></i>
          </span>

          <strong>No hay solicitudes PQRSDF registradas</strong>

          <p>
            Las solicitudes enviadas desde el formulario público
            aparecerán aquí automáticamente.
          </p>
        </div>

      <?php else: ?>

        <div class="admin-table-wrapper">
          <table class="admin-table admin-table-pqrs">

            <thead>
              <tr>
                <th>Radicado</th>
                <th>Solicitante</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Adjunto</th>
                <th>Correo</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>

            <?php foreach ($solicitudes as $solicitud): ?>

              <?php
              $estadoSolicitud = strtolower(trim($solicitud['estado'] ?? 'nuevo'));

              if ($estadoSolicitud === '') {
                  $estadoSolicitud = 'nuevo';
              }

              $radicado = str_pad(
                  (string)$solicitud['id'],
                  3,
                  '0',
                  STR_PAD_LEFT
              );
              ?>

              <tr>

                <td>
                  <span class="admin-radicado">
                    #<?= htmlspecialchars($radicado, ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>

                <td>
                  <div class="admin-pqrs-person">

                    <span class="admin-contact-avatar">
                      <i class="bi bi-person"></i>
                    </span>

                    <div>
                      <div class="admin-table-title">
                        <?= htmlspecialchars($solicitud['nombre_completo'], ENT_QUOTES, 'UTF-8') ?>
                      </div>

                      <small class="admin-pqrs-doc">
                        <?= htmlspecialchars($solicitud['tipo_documento'], ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($solicitud['numero_documento'], ENT_QUOTES, 'UTF-8') ?>
                      </small>

                      <a
                        class="admin-contact-email"
                        href="mailto:<?= htmlspecialchars($solicitud['correo'], ENT_QUOTES, 'UTF-8') ?>"
                      >
                        <?= htmlspecialchars($solicitud['correo'], ENT_QUOTES, 'UTF-8') ?>
                      </a>
                    </div>

                  </div>
                </td>

                <td>
                  <span class="admin-pqrs-type">
                    <?= htmlspecialchars($solicitud['tipo_pqrs'], ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>

                <td>
                  <div class="admin-table-description admin-pqrs-description">
                    <?= htmlspecialchars(
                        mb_strimwidth(
                            preg_replace('/\s+/', ' ', $solicitud['descripcion']),
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
                  <div class="admin-contact-date">
                    <strong>
                      <?= date('d/m/Y', strtotime($solicitud['created_at'])) ?>
                    </strong>

                    <span>
                      <?= date('H:i', strtotime($solicitud['created_at'])) ?>
                    </span>
                  </div>
                </td>

                <td>
                  <?php if ($estadoSolicitud === 'nuevo'): ?>
                    <span class="admin-status admin-status-new">Nuevo</span>
                  <?php elseif ($estadoSolicitud === 'revisado'): ?>
                    <span class="admin-status admin-status-reviewed">Revisado</span>
                  <?php elseif ($estadoSolicitud === 'atendido'): ?>
                    <span class="admin-status admin-status-active">Atendido</span>
                  <?php else: ?>
                    <span class="admin-status admin-status-inactive">
                      <?= htmlspecialchars(ucfirst($estadoSolicitud), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  <?php endif; ?>
                </td>

                <td>
                  <?php if (!empty($solicitud['archivo'])): ?>

                    <a
                      href="/bibliotecatenjo/<?= htmlspecialchars($solicitud['archivo'], ENT_QUOTES, 'UTF-8') ?>"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="admin-btn admin-btn-file"
                    >
                      <i class="bi bi-paperclip"></i>
                      Ver
                    </a>

                  <?php else: ?>

                    <span class="admin-muted">Sin adjunto</span>

                  <?php endif; ?>
                </td>

                <td>
                  <?php if ((int)$solicitud['correo_enviado'] === 1): ?>

                    <span class="admin-mail-status admin-mail-status-ok">
                      <i class="bi bi-check-circle-fill"></i>
                      Enviado
                    </span>

                  <?php else: ?>

                    <span class="admin-mail-status admin-mail-status-pending">
                      <i class="bi bi-exclamation-circle"></i>
                      Pendiente
                    </span>

                  <?php endif; ?>
                </td>

                <td>
                  <a
                    href="ver.php?id=<?= (int)$solicitud['id'] ?>"
                    class="admin-btn admin-btn-light"
                  >
                    <i class="bi bi-eye"></i>
                    Ver detalle
                  </a>
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
      <span>Gestión de PQRSDF</span>
    </footer>

  </div>
</main>

</div>
</body>
</html>