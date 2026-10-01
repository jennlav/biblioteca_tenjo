<?php

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../config/mailer.php';

header('Content-Type: application/json; charset=utf-8');

/*

========================================================

PLANTILLA INSTITUCIONAL DE CORREO

========================================================

*/

function plantillaCorreoInstitucional(

    string $logoCid,

    string $titulo,

    string $subtitulo,

    string $contenidoHtml

): string {

    $logoHtml = '';

    if ($logoCid !== '') {

        $logoHtml = '

            <img

                src="cid:' . htmlspecialchars($logoCid, ENT_QUOTES, 'UTF-8') . '"

                alt="Biblioteca Municipal Isabel Murillo de Luque"

                style="

                    display:block;

                    width:115px;

                    max-width:115px;

                    height:auto;

                    border:0;

                "

            >

        ';

    }

    return '

    <!DOCTYPE html>

    <html lang="es">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>

            ' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '

        </title>

    </head>

    <body style="

        margin:0;

        padding:0;

        background:#f4f6f5;

        font-family:Arial, Helvetica, sans-serif;

        color:#263238;

    ">

        <table

            role="presentation"

            width="100%"

            cellspacing="0"

            cellpadding="0"

            border="0"

            style="

                width:100%;

                background:#f4f6f5;

                padding:28px 14px;

            "

        >

            <tr>

                <td align="center">

                    <table

                        role="presentation"

                        width="680"

                        cellspacing="0"

                        cellpadding="0"

                        border="0"

                        style="

                            width:100%;

                            max-width:680px;

                            background:#ffffff;

                            border:1px solid #e3e8e5;

                            border-radius:14px;

                            overflow:hidden;

                        "

                    >

                        <!-- =========================================

                             ENCABEZADO INSTITUCIONAL

                             ========================================= -->

                        <tr>

                            <td style="

                                padding:22px 26px;

                                background:#ffffff;

                                border-bottom:1px solid #e7ece9;

                            ">

                                <table

                                    role="presentation"

                                    width="100%"

                                    cellspacing="0"

                                    cellpadding="0"

                                    border="0"

                                >

                                    <tr>

                                        <td style="

                                            width:125px;

                                            vertical-align:middle;

                                        ">

                                            ' . $logoHtml . '

                                        </td>

                                        <td style="

                                            vertical-align:middle;

                                            padding-left:14px;

                                        ">

                                            <div style="

                                                margin:0;

                                                font-size:20px;

                                                line-height:1.25;

                                                font-weight:700;

                                                color:#0b5b34;

                                            ">

                                                Biblioteca Municipal

                                                Isabel Murillo de Luque

                                            </div>

                                            <div style="

                                                margin-top:5px;

                                                font-size:14px;

                                                color:#6a7370;

                                            ">

                                                Tenjo - Cundinamarca

                                            </div>

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>

                        <!-- =========================================

                             TÍTULO

                             ========================================= -->

                        <tr>

                            <td style="

                                padding:28px 30px 16px 30px;

                            ">

                                <h1 style="

                                    margin:0 0 8px 0;

                                    font-size:24px;

                                    line-height:1.3;

                                    color:#25312d;

                                ">

                                    ' .

                                    htmlspecialchars(

                                        $titulo,

                                        ENT_QUOTES,

                                        'UTF-8'

                                    ) .

                                    '

                                </h1>

                                <p style="

                                    margin:0;

                                    font-size:15px;

                                    line-height:1.6;

                                    color:#68726e;

                                ">

                                    ' . $subtitulo . '

                                </p>

                            </td>

                        </tr>

                        <!-- =========================================

                             FRANJA INSTITUCIONAL

                             ========================================= -->

                        <tr>

                            <td style="

                                padding:0 30px;

                            ">

                                <div style="

                                    height:4px;

                                    background:#d4b300;

                                    border-radius:4px;

                                ">

                                </div>

                            </td>

                        </tr>

                        <!-- =========================================

                             CONTENIDO

                             ========================================= -->

                        <tr>

                            <td style="

                                padding:26px 30px 32px 30px;

                                font-size:15px;

                                line-height:1.65;

                                color:#35403c;

                            ">

                                ' . $contenidoHtml . '

                            </td>

                        </tr>

                        <!-- =========================================

                             FOOTER

                             ========================================= -->

                        <tr>

                            <td style="

                                padding:17px 24px;

                                background:#f7f8f7;

                                border-top:1px solid #e8ecea;

                                text-align:center;

                                font-size:12px;

                                line-height:1.5;

                                color:#767d7a;

                            ">

                                Mensaje generado automáticamente desde

                                el portal web de la Biblioteca Municipal

                                Isabel Murillo de Luque.

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    </body>

    </html>

    ';

}

/*

========================================================

PERMITIR SOLO POST

========================================================

*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([

        'success' => false,

        'message' => 'Método no permitido.'

    ], JSON_UNESCAPED_UNICODE);

    exit;

}

/*

========================================================

RECIBIR DATOS

========================================================

*/

$nombre = trim($_POST['nombre'] ?? '');

$correo = trim($_POST['correo'] ?? '');

$telefono = trim($_POST['telefono'] ?? '');

$asunto = trim($_POST['asunto'] ?? '');

$mensaje = trim($_POST['mensaje'] ?? '');

/*

========================================================

VALIDACIONES

========================================================

*/

$errores = [];

if ($nombre === '') {

    $errores[] = 'El nombre es obligatorio.';

}

if ($correo === '') {

    $errores[] = 'El correo es obligatorio.';

} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

    $errores[] = 'El correo no tiene un formato válido.';

}

if ($asunto === '') {

    $errores[] = 'El asunto es obligatorio.';

}

if ($mensaje === '') {

    $errores[] = 'El mensaje es obligatorio.';

}

if (mb_strlen($nombre) > 150) {

    $errores[] = 'El nombre supera la longitud permitida.';

}

if (mb_strlen($correo) > 180) {

    $errores[] = 'El correo supera la longitud permitida.';

}

if (mb_strlen($telefono) > 30) {

    $errores[] = 'El teléfono supera la longitud permitida.';

}

if (mb_strlen($asunto) > 180) {

    $errores[] = 'El asunto supera la longitud permitida.';

}

if (mb_strlen($mensaje) > 1000) {

    $errores[] = 'El mensaje supera los 1000 caracteres.';

}

if (!empty($errores)) {

    http_response_code(422);

    echo json_encode([

        'success' => false,

        'message' => 'Hay errores en la información enviada.',

        'errors' => $errores

    ], JSON_UNESCAPED_UNICODE);

    exit;

}

/*

========================================================

DATOS DE TRAZABILIDAD

========================================================

*/

$ipOrigen = $_SERVER['REMOTE_ADDR'] ?? null;

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

/*

========================================================

GUARDAR PRIMERO EN BASE DE DATOS

========================================================

*/

try {

    $sql = "INSERT INTO contactos (

                nombre,

                correo,

                telefono,

                asunto,

                mensaje,

                estado,

                ip_origen,

                user_agent,

                correo_enviado

            ) VALUES (

                :nombre,

                :correo,

                :telefono,

                :asunto,

                :mensaje,

                'nuevo',

                :ip_origen,

                :user_agent,

                0

            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':nombre' => $nombre,

        ':correo' => $correo,

        ':telefono' =>

            $telefono !== ''

                ? $telefono

                : null,

        ':asunto' => $asunto,

        ':mensaje' => $mensaje,

        ':ip_origen' => $ipOrigen,

        ':user_agent' => $userAgent

    ]);

    $contactoId =

        (int)$pdo->lastInsertId();

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>

            'No fue posible registrar el mensaje.'

    ], JSON_UNESCAPED_UNICODE);

    exit;

}

/*

========================================================

CONFIGURACIÓN DE CORREO

========================================================

*/

$mailConfig =

    require __DIR__ . '/../config/mail.php';

/*

========================================================

LOGO INSTITUCIONAL

========================================================

*/

$logoPath =

    __DIR__ .

    '/../public/assets/img/logobiblio.png';

/*

========================================================

ESCAPAR DATOS

========================================================

*/

$nombreHtml =

    htmlspecialchars(

        $nombre,

        ENT_QUOTES,

        'UTF-8'

    );

$correoHtml =

    htmlspecialchars(

        $correo,

        ENT_QUOTES,

        'UTF-8'

    );

$telefonoHtml =

    htmlspecialchars(

        $telefono !== ''

            ? $telefono

            : 'No registrado',

        ENT_QUOTES,

        'UTF-8'

    );

$asuntoHtml =

    htmlspecialchars(

        $asunto,

        ENT_QUOTES,

        'UTF-8'

    );

$mensajeHtml =

    nl2br(

        htmlspecialchars(

            $mensaje,

            ENT_QUOTES,

            'UTF-8'

        )

    );

/*

========================================================

1. CORREO INTERNO A LA BIBLIOTECA

========================================================

*/

try {

    $mailBiblioteca =

        crearMailer();

    /*

    ====================================================

    DESTINATARIO

    ====================================================

    */

    $mailBiblioteca->addAddress(
        'institutodeculturayturismo@tenjo-cundinamarca.gov.co'
    );

    $mailBiblioteca->addAddress(
        'biblioteca@tenjoculturayturismo.gov.co'
    );

    /*

    ====================================================

    RESPONDER DIRECTAMENTE AL CIUDADANO

    ====================================================

    */

    $mailBiblioteca->addReplyTo(

        $correo,

        $nombre

    );

    /*

    ====================================================

    LOGO EMBEBIDO

    ====================================================

    */

    $logoCidBiblioteca = '';

    if (file_exists($logoPath)) {

        $logoCidBiblioteca =

            'logo_biblioteca_interno';

        $mailBiblioteca->addEmbeddedImage(

            $logoPath,

            $logoCidBiblioteca,

            'Biblioteca Tenjo'

        );

    }

    /*

    ====================================================

    CONTENIDO INTERNO

    ====================================================

    */

    $contenidoInterno = '

        <h2 style="

            margin:0 0 18px 0;

            font-size:19px;

            color:#28332f;

        ">

            Información del usuario

        </h2>

        <table

            role="presentation"

            width="100%"

            cellspacing="0"

            cellpadding="0"

            border="0"

            style="

                width:100%;

                border-collapse:collapse;

                margin-bottom:26px;

                font-size:14px;

            "

        >

            <tr>

                <td style="

                    width:135px;

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Nombre

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $nombreHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Correo

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    <a

                        href="mailto:' . $correoHtml . '"

                        style="

                            color:#145dc0;

                            text-decoration:none;

                        "

                    >

                        ' . $correoHtml . '

                    </a>

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Teléfono

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $telefonoHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Asunto

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $asuntoHtml . '

                </td>

            </tr>

        </table>

        <h2 style="

            margin:0 0 13px 0;

            font-size:19px;

            color:#28332f;

        ">

            Mensaje

        </h2>

        <div style="

            background:#f6f7f6;

            border:1px solid #e7ebe9;

            border-radius:10px;

            padding:17px;

            font-size:14px;

            line-height:1.65;

            color:#35403c;

        ">

            ' . $mensajeHtml . '

        </div>

        <div style="

            margin-top:24px;

            padding:13px 16px;

            background:#f7f8f7;

            border-left:4px solid #d4b300;

            font-size:13px;

            color:#68716d;

        ">

            <strong>

                Registro interno No. ' .

                $contactoId .

                '

            </strong>

        </div>

    ';

    /*

    ====================================================

    ASUNTO Y CUERPO

    ====================================================

    */

    $mailBiblioteca->isHTML(true);

    $mailBiblioteca->Subject =

        'Nuevo mensaje de contacto - ' .

        $asunto;

    $mailBiblioteca->Body =

        plantillaCorreoInstitucional(

            $logoCidBiblioteca,

            'Nuevo mensaje de contacto',

            'Se ha recibido una nueva solicitud desde el sitio web de la Biblioteca.',

            $contenidoInterno

        );

    /*

    ====================================================

    VERSIÓN TEXTO PLANO

    ====================================================

    */

    $mailBiblioteca->AltBody =

        "Nuevo mensaje recibido desde el sitio web\n\n" .

        "Registro interno: {$contactoId}\n" .

        "Nombre: {$nombre}\n" .

        "Correo: {$correo}\n" .

        "Teléfono: " .

        ($telefono ?: 'No registrado') .

        "\n" .

        "Asunto: {$asunto}\n\n" .

        "Mensaje:\n{$mensaje}";

    /*

    ====================================================

    ENVIAR

    ====================================================

    */

    $mailBiblioteca->send();

    /*

    ====================================================

    MARCAR CORREO INTERNO COMO ENVIADO

    ====================================================

    */

    $sql = "UPDATE contactos

            SET correo_enviado = 1

            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':id' => $contactoId

    ]);

} catch (Throwable $e) {

    /*

    ====================================================

    EL REGISTRO YA EXISTE EN BD.

    EL MENSAJE NO SE PIERDE.

    ====================================================

    */

    error_log(

        'Error SMTP correo interno contacto ID ' .

        $contactoId .

        ': ' .

        $e->getMessage()

    );

}

/*

========================================================

2. CORREO DE CONFIRMACIÓN AL CIUDADANO

========================================================

*/

try {

    $mailUsuario =

        crearMailer();

    /*

    ====================================================

    DESTINATARIO

    ====================================================

    */

    $mailUsuario->addAddress(

        $correo,

        $nombre

    );

    /*

    ====================================================

    RESPUESTAS A LA BIBLIOTECA

    ====================================================

    */

    $mailUsuario->addReplyTo(
        'biblioteca@tenjoculturayturismo.gov.co',
        'Biblioteca Municipal Isabel Murillo de Luque'
    );

    /*

    ====================================================

    LOGO EMBEBIDO

    ====================================================

    */

    $logoCidUsuario = '';

    if (file_exists($logoPath)) {

        $logoCidUsuario =

            'logo_biblioteca_confirmacion';

        $mailUsuario->addEmbeddedImage(

            $logoPath,

            $logoCidUsuario,

            'Biblioteca Tenjo'

        );

    }

    /*

    ====================================================

    CONTENIDO DEL CORREO AL CIUDADANO

    ====================================================

    */

    $contenidoUsuario = '

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Hola

            <strong>

                ' . $nombreHtml . '

            </strong>,

        </p>

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Gracias por comunicarte con la

            <strong>

                Biblioteca Municipal Isabel Murillo de Luque

            </strong>.

            Hemos recibido correctamente tu mensaje

            a través de nuestro sitio web.

        </p>

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Nuestro equipo revisará la información enviada

            y una persona de la Biblioteca se pondrá en

            contacto contigo lo antes posible a través de

            los datos que registraste.

        </p>

        <div style="

            margin:24px 0;

            padding:18px;

            background:#f6f7f6;

            border:1px solid #e7ebe9;

            border-radius:10px;

        ">

            <p style="

                margin:0 0 9px 0;

                font-size:14px;

            ">

                <strong>

                    Asunto:

                </strong>

                ' . $asuntoHtml . '

            </p>

            <p style="

                margin:0;

                font-size:14px;

            ">

                <strong>

                    Número de registro:

                </strong>

                ' . $contactoId . '

            </p>

        </div>

        <p style="

            margin:0 0 18px 0;

            font-size:15px;

            line-height:1.7;

            color:#59635f;

        ">

            Te recomendamos conservar este correo

            como constancia de recepción.

        </p>

        <p style="

            margin:24px 0 0 0;

            font-size:15px;

            line-height:1.7;

        ">

            Cordialmente,

            <br>

            <strong>

                Biblioteca Municipal Isabel Murillo de Luque

            </strong>

            <br>

            Tenjo - Cundinamarca

        </p>

    ';

    /*

    ====================================================

    ASUNTO Y CUERPO

    ====================================================

    */

    $mailUsuario->isHTML(true);

    $mailUsuario->Subject =

        'Hemos recibido tu mensaje - Biblioteca Tenjo';

    $mailUsuario->Body =

        plantillaCorreoInstitucional(

            $logoCidUsuario,

            'Hemos recibido tu mensaje',

            'Confirmación de recepción de tu solicitud.',

            $contenidoUsuario

        );

    /*

    ====================================================

    VERSIÓN TEXTO PLANO

    ====================================================

    */

    $mailUsuario->AltBody =

        "Hola {$nombre},\n\n" .

        "Gracias por comunicarte con la Biblioteca Municipal Isabel Murillo de Luque.\n\n" .

        "Hemos recibido correctamente tu mensaje. " .

        "Nuestro equipo revisará la información enviada " .

        "y una persona de la Biblioteca se pondrá en contacto contigo lo antes posible.\n\n" .

        "Asunto: {$asunto}\n" .

        "Número de registro: {$contactoId}\n\n" .

        "Te recomendamos conservar este correo como constancia de recepción.\n\n" .

        "Biblioteca Municipal Isabel Murillo de Luque\n" .

        "Tenjo - Cundinamarca";

    /*

    ====================================================

    ENVIAR CONFIRMACIÓN

    ====================================================

    */

    $mailUsuario->send();

} catch (Throwable $e) {

    /*

    ====================================================

    SI FALLA EL CORREO DE CONFIRMACIÓN:

    - el registro permanece en BD

    - el correo interno no se afecta

    - el formulario sigue respondiendo correctamente

    ====================================================

    */

    error_log(

        'Error SMTP confirmación usuario contacto ID ' .

        $contactoId .

        ': ' .

        $e->getMessage()

    );

}

/*

========================================================

RESPUESTA AL FRONTEND

========================================================

*/

echo json_encode([

    'success' => true,

    'message' =>

        'Tu mensaje fue recibido correctamente.'

], JSON_UNESCAPED_UNICODE);
