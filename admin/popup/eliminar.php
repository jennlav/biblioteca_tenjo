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
    SELECT
        id,
        titulo,
        imagen,
        estado
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

if ((int)$aviso['estado'] === 2) {
    header(
        'Location: /bibliotecatenjo/admin/popup/index.php'
    );
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $stmt = $pdo->prepare("
            UPDATE popup_home
            SET estado = 2
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        header(
            'Location: /bibliotecatenjo/admin/popup/index.php'
        );

        exit;

    } catch (Throwable $e) {

        $errores[] =
            'No fue posible eliminar el aviso.';
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Eliminar aviso | Administración Biblioteca</title>
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
  <div class="admin-container admin-container-confirm">

    <a href="index.php" class="admin-back-link">
      <i class="bi bi-arrow-left"></i>
      Volver a avisos
    </a>

    <section class="admin-card admin-confirm-card">

      <span class="admin-confirm-icon admin-confirm-icon-danger">
        <i class="bi bi-trash3"></i>
      </span>

      <span class="admin-kicker">
        Avisos del Home
      </span>

      <h1>Eliminar aviso</h1>

      <p>
        Estás a punto de retirar el siguiente aviso
        del sitio público:
      </p>

      <div class="admin-confirm-name">
        <?= htmlspecialchars($aviso['titulo'], ENT_QUOTES, 'UTF-8') ?>
      </div>

      <?php if (!empty($aviso['imagen'])): ?>

        <div class="admin-confirm-preview admin-confirm-preview-popup">
          <img
            src="/bibliotecatenjo/<?= htmlspecialchars($aviso['imagen'], ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($aviso['titulo'], ENT_QUOTES, 'UTF-8') ?>"
          >
        </div>

      <?php endif; ?>

      <div class="admin-alert admin-alert-error admin-confirm-warning">

        <i class="bi bi-eye-slash-fill"></i>

        <div>
          <strong>
            El aviso dejará de mostrarse públicamente.
          </strong>

          <span>
            También desaparecerá del listado normal
            de administración.
          </span>
        </div>

      </div>

      <div class="admin-history-note">

        <i class="bi bi-clock-history"></i>

        <div>
          <strong>
            Se conservará el historial.
          </strong>

          <span>
            El registro y su imagen permanecerán almacenados.
            La eliminación es lógica mediante estado = 2.
          </span>
        </div>

      </div>

      <?php if (!empty($errores)): ?>

        <div class="admin-alert admin-alert-error">
          <?php foreach ($errores as $error): ?>
            <div>
              <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>

      <form method="POST">

        <div class="admin-form-actions">

          <button
            type="submit"
            class="admin-btn admin-btn-danger admin-btn-danger-solid"
          >
            <i class="bi bi-trash3"></i>
            Sí, eliminar aviso
          </button>

          <a href="index.php" class="admin-btn admin-btn-light">
            Cancelar
          </a>

        </div>

      </form>

    </section>

    <footer class="admin-footer">
      <span>Biblioteca Municipal Isabel Murillo de Luque</span>
      <span>Eliminar aviso</span>
    </footer>

  </div>
</main>

</div>

</body>
</html>