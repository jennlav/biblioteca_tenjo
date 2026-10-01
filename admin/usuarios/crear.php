<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';

$errores = [];
$mensajeExito = '';

$nombre = '';
$correo = '';
$rol = 'administrador';
$estado = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $rol = trim($_POST['rol'] ?? 'administrador');
    $estado = isset($_POST['estado']) ? 1 : 0;

    $password = $_POST['password'] ?? '';
    $confirmarPassword = $_POST['confirmar_password'] ?? '';

    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($nombre) > 120) {
        $errores[] = 'El nombre no puede superar los 120 caracteres.';
    }

    if ($correo === '') {
        $errores[] = 'El correo es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no tiene un formato válido.';
    } elseif (mb_strlen($correo) > 150) {
        $errores[] = 'El correo no puede superar los 150 caracteres.';
    }

    if ($rol === '') {
        $errores[] = 'El rol es obligatorio.';
    } elseif (mb_strlen($rol) > 50) {
        $errores[] = 'El rol no puede superar los 50 caracteres.';
    }

    if ($password === '') {
        $errores[] = 'La contraseña es obligatoria.';
    } elseif (mb_strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener mínimo 8 caracteres.';
    }

    if ($password !== $confirmarPassword) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errores)) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM usuarios
            WHERE correo = :correo
            LIMIT 1
        ");

        $stmt->execute([
            ':correo' => $correo
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'Ya existe un usuario registrado con ese correo.';
        }
    }

    if (empty($errores)) {

        try {

            $passwordHash =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "
                INSERT INTO usuarios (
                    nombre,
                    correo,
                    password,
                    rol,
                    estado
                ) VALUES (
                    :nombre,
                    :correo,
                    :password,
                    :rol,
                    :estado
                )
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':password' => $passwordHash,
                ':rol' => $rol,
                ':estado' => $estado
            ]);

            $mensajeExito =
                'El usuario fue creado correctamente.';

            $nombre = '';
            $correo = '';
            $rol = 'administrador';
            $estado = 1;

        } catch (Throwable $e) {

            $errores[] =
                'No fue posible crear el usuario.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
  Crear usuario | Administración Biblioteca
</title>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<link
  rel="stylesheet"
  href="../assets/css/admin.css?v=20260901-2"
>

<link
  rel="stylesheet"
  href="../assets/css/admin-modulos.css?v=20260902-8"
>

</head>

<body>

<div class="admin-shell">

<header class="admin-header">
  <div class="admin-header-inner">

    <a
      href="../dashboard.php"
      class="admin-brand"
      style="text-decoration:none;"
    >
      <img
        src="/bibliotecatenjo/public/assets/img/logobiblio.png"
        alt="Biblioteca Municipal Isabel Murillo de Luque"
        class="admin-brand-logo"
      >

      <div class="admin-brand-copy">
        <strong>
          Biblioteca Municipal Isabel Murillo de Luque
        </strong>
        <span>
          Tenjo - Cundinamarca
        </span>
      </div>
    </a>

    <div class="admin-user">

      <div class="admin-user-icon" aria-hidden="true">
        <i class="bi bi-person-circle"></i>
      </div>

      <div class="admin-user-copy">
        <strong>
          <?= htmlspecialchars(
            $_SESSION['usuario_nombre'],
            ENT_QUOTES,
            'UTF-8'
          ) ?>
        </strong>

        <span>
          <?= htmlspecialchars(
            $_SESSION['usuario_rol'],
            ENT_QUOTES,
            'UTF-8'
          ) ?>
        </span>
      </div>

      <a
        href="../logout.php"
        class="admin-logout"
        title="Cerrar sesión"
      >
        <i class="bi bi-box-arrow-right"></i>
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
          Volver a usuarios
        </a>

        <span class="admin-kicker">
          Administración
        </span>

        <h1>
          Crear usuario
        </h1>

        <p>
          Registra una nueva cuenta con acceso al panel
          administrativo de la Biblioteca.
        </p>

      </div>

    </section>


    <?php if ($mensajeExito !== ''): ?>

      <div class="admin-alert admin-alert-success">

        <i class="bi bi-check-circle-fill"></i>

        <?= htmlspecialchars(
          $mensajeExito,
          ENT_QUOTES,
          'UTF-8'
        ) ?>

      </div>

    <?php endif; ?>


    <?php if (!empty($errores)): ?>

      <div class="admin-alert admin-alert-error">

        <i class="bi bi-exclamation-circle-fill"></i>

        <div>
          <?php foreach ($errores as $error): ?>

            <div>
              <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
              ) ?>
            </div>

          <?php endforeach; ?>
        </div>

      </div>

    <?php endif; ?>


    <form
      method="POST"
      class="admin-card admin-form-card"
      autocomplete="off"
    >

      <div class="admin-form-section-heading">

        <span class="admin-form-section-icon">
          <i class="bi bi-person-plus"></i>
        </span>

        <div>
          <h2>
            Datos de la cuenta
          </h2>

          <p>
            Define la información básica del usuario administrativo.
          </p>
        </div>

      </div>


      <div class="admin-form-group">

        <label for="nombre">
          Nombre completo
          <span class="admin-required">*</span>
        </label>

        <input
          type="text"
          id="nombre"
          name="nombre"
          maxlength="120"
          value="<?= htmlspecialchars(
            $nombre,
            ENT_QUOTES,
            'UTF-8'
          ) ?>"
          required
        >

      </div>


      <div class="admin-form-group">

        <label for="correo">
          Correo electrónico
          <span class="admin-required">*</span>
        </label>

        <input
          type="email"
          id="correo"
          name="correo"
          maxlength="150"
          value="<?= htmlspecialchars(
            $correo,
            ENT_QUOTES,
            'UTF-8'
          ) ?>"
          required
        >

      </div>


      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">

          <label for="rol">
            Rol
            <span class="admin-required">*</span>
          </label>

          <select
            id="rol"
            name="rol"
            class="admin-select"
            required
          >
            <option
              value="administrador"
              <?= $rol === 'administrador'
                  ? 'selected'
                  : ''
              ?>
            >
              Administrador
            </option>
          </select>

        </div>


        <div class="admin-form-group">

          <label>
            Estado
          </label>

          <div class="admin-check-row admin-check-row-inline">

            <input
              type="checkbox"
              id="estado"
              name="estado"
              value="1"
              <?= $estado ? 'checked' : '' ?>
            >

            <label for="estado">
              Usuario activo
            </label>

          </div>

        </div>

      </div>


      <div class="admin-form-section-heading admin-form-section-secondary">

        <span class="admin-form-section-icon admin-form-section-icon-yellow">
          <i class="bi bi-key"></i>
        </span>

        <div>
          <h2>
            Contraseña inicial
          </h2>

          <p>
            Debe contener mínimo 8 caracteres.
          </p>
        </div>

      </div>


      <div class="admin-form-grid admin-form-grid-2">

        <div class="admin-form-group">

          <label for="password">
            Contraseña
            <span class="admin-required">*</span>
          </label>

          <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            autocomplete="new-password"
            required
          >

        </div>


        <div class="admin-form-group">

          <label for="confirmar_password">
            Confirmar contraseña
            <span class="admin-required">*</span>
          </label>

          <input
            type="password"
            id="confirmar_password"
            name="confirmar_password"
            minlength="8"
            autocomplete="new-password"
            required
          >

        </div>

      </div>


      <div class="admin-user-security-note">

        <i class="bi bi-shield-check"></i>

        <div>
          <strong>
            La contraseña no se almacena en texto plano.
          </strong>

          <span>
            PHP la guarda mediante un hash seguro utilizando
            <code>password_hash()</code>.
          </span>
        </div>

      </div>


      <div class="admin-form-actions">

        <button
          type="submit"
          class="admin-btn admin-btn-primary"
        >
          <i class="bi bi-person-check"></i>
          Crear usuario
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
        Crear usuario
      </span>

    </footer>

  </div>

</main>

</div>

</body>

</html>
