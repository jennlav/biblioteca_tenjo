<?php

require_once __DIR__ . '/config/mailer.php';

try {

    $mailConfig = require __DIR__ . '/config/mail.php';

    $mail = crearMailer();

    /*
    ========================================================
    DESTINATARIO
    ========================================================
    */

    $mail->addAddress(
        $mailConfig['to_email']
    );

    /*
    ========================================================
    CONTENIDO DEL CORREO
    ========================================================
    */

    $mail->isHTML(true);

    $mail->Subject =
        'Prueba SMTP - Biblioteca Tenjo';

    $mail->Body = '
        <div style="
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 24px;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
        ">

            <h2 style="margin-top: 0;">
                Biblioteca Municipal Isabel Murillo de Luque
            </h2>

            <p>
                Este es un correo de prueba enviado desde
                el backend local del sitio web de la Biblioteca.
            </p>

            <p>
                Si estás viendo este mensaje,
                la conexión SMTP con PHPMailer
                está funcionando correctamente.
            </p>

            <hr>

            <p style="
                font-size: 13px;
                color: #666;
            ">
                Prueba técnica de integración SMTP.
            </p>

        </div>
    ';

    $mail->AltBody =
        'Prueba SMTP - Biblioteca Tenjo. ' .
        'La conexión con PHPMailer funciona correctamente.';

    /*
    ========================================================
    ENVIAR
    ========================================================
    */

    $mail->send();

    echo '<h2>Correo enviado correctamente ✅</h2>';

    echo '<p>Revisa la bandeja de entrada de:</p>';

    echo '<strong>' .
        htmlspecialchars($mailConfig['to_email']) .
        '</strong>';

} catch (Throwable $e) {

    echo '<h2>Error al enviar el correo ❌</h2>';

    echo '<p>' .
        htmlspecialchars($e->getMessage()) .
        '</p>';
}