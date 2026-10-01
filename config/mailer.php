<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/*
========================================================
CARGAR PHPMailer
========================================================
*/

require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

/*
========================================================
CARGAR CONFIGURACIÓN SMTP
========================================================
*/

$mailConfig = require __DIR__ . '/mail.php';

/*
========================================================
CREAR Y CONFIGURAR INSTANCIA
========================================================
*/

function crearMailer(): PHPMailer
{
    global $mailConfig;

    $mail = new PHPMailer(true);

    /*
    ====================================================
    CONFIGURACIÓN SMTP
    ====================================================
    */

    $mail->isSMTP();

    $mail->Host = $mailConfig['host'];

    $mail->SMTPAuth = true;

    $mail->Username = $mailConfig['username'];

    $mail->Password = $mailConfig['password'];

    $mail->Port = $mailConfig['port'];

    /*
    ====================================================
    SEGURIDAD
    ====================================================
    */

    if ($mailConfig['secure'] === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($mailConfig['secure'] === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    /*
    ====================================================
    CODIFICACIÓN
    ====================================================
    */

    $mail->CharSet = 'UTF-8';

    $mail->Encoding = 'base64';

    /*
    ====================================================
    REMITENTE
    ====================================================
    */

    if (!empty($mailConfig['from_email'])) {

        $mail->setFrom(
            $mailConfig['from_email'],
            $mailConfig['from_name']
        );
    }

    return $mail;
}