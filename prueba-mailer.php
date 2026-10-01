<?php

require_once __DIR__ . '/config/mailer.php';

try {

    $mail = crearMailer();

    echo '<h2>PHPMailer cargado correctamente ✅</h2>';

    echo '<p>Host configurado: ' .
        htmlspecialchars($mail->Host) .
        '</p>';

    echo '<p>Puerto configurado: ' .
        htmlspecialchars((string)$mail->Port) .
        '</p>';

} catch (Throwable $e) {

    echo '<h2>Error al cargar PHPMailer ❌</h2>';

    echo '<p>' .
        htmlspecialchars($e->getMessage()) .
        '</p>';
}