<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Evento no válido.');
}

$sql = "SELECT id, titulo, imagen
        FROM eventos
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

$evento = $stmt->fetch();

if (!$evento) {
    die('Evento no encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "DELETE FROM eventos
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if (!empty($evento['imagen'])) {

        $rutaImagen =
            __DIR__ .
            '/../../' .
            $evento['imagen'];

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eliminar evento | Biblioteca Tenjo</title>
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
  <div class="admin-container admin-container-confirm">

    <a href="index.php" class="admin-back-link">
      <i class="bi bi-arrow-left"></i>
      Volver a eventos
    </a>

    <section class="admin-card admin-confirm-card">

      <span class="admin-confirm-icon admin-confirm-icon-danger">
        <i class="bi bi-trash3"></i>
      </span>

      <span class="admin-kicker">
        Gestión de eventos
      </span>

      <h1>
        Eliminar evento
      </h1>

      <p>
        Estás a punto de eliminar definitivamente el siguiente evento:
      </p>

      <div class="admin-confirm-name">
        <?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>
      </div>

      <div class="admin-alert admin-alert-error admin-confirm-warning">
        <i class="bi bi-exclamation-triangle-fill"></i>

        <div>
          <strong>Esta acción no se puede deshacer.</strong>

          <span>
            También se eliminará la imagen asociada al evento,
            si existe.
          </span>
        </div>
      </div>

      <form method="POST">
        <div class="admin-form-actions">

          <button
            type="submit"
            class="admin-btn admin-btn-danger admin-btn-danger-solid"
          >
            <i class="bi bi-trash3"></i>
            Confirmar eliminación
          </button>

          <a href="index.php" class="admin-btn admin-btn-light">
            Cancelar
          </a>

        </div>
      </form>

    </section>

    <footer class="admin-footer">
      <span>Biblioteca Municipal Isabel Murillo de Luque</span>
      <span>Eliminar evento</span>
    </footer>

  </div>
</main>
</div>
</body>
</html>