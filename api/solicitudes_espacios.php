<?php

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../config/mailer.php';

header('Content-Type: application/json; charset=utf-8');

/*

\========================================================

PLANTILLA INSTITUCIONAL DE CORREO

\========================================================

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

                                    ' . htmlspecialchars(

                                        $titulo,

                                        ENT_QUOTES,

                                        'UTF-8'

                                    ) . '

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

                        <tr>

                            <td style="padding:0 30px;">

                                <div style="

                                    height:4px;

                                    background:#d4b300;

                                    border-radius:4px;

                                "></div>

                            </td>

                        </tr>

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

                                Mensaje generado automáticamente desde el portal web de la

                                Biblioteca Municipal Isabel Murillo de Luque.

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

\========================================================

PERMITIR SOLO POST

\========================================================

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

\========================================================

RECIBIR DATOS

\========================================================

*/

$nombre = trim($_POST['nombreCoworking'] ?? '');

$correo = trim($_POST['correoCoworking'] ?? '');

$telefono = trim($_POST['telefonoCoworking'] ?? '');

$espacio = trim($_POST['espacioReserva'] ?? '');

$fechaReserva = trim($_POST['fechaReserva'] ?? '');

$horario = trim($_POST['horarioReserva'] ?? '');

$tipoUso = trim($_POST['tipoUso'] ?? '');

$cantidadPersonas = trim($_POST['cantidadPersonas'] ?? '');

$detalle = trim($_POST['detalleReserva'] ?? '');

/*

\========================================================

VALORES PERMITIDOS

\========================================================

*/

$espaciosPermitidos = [

    'box-lectura',

    'sala-alas-papel',

    'sala-trabajo',

    'computadores'

];

$horariosPermitidos = [

    '8-10',

    '10-12',

    '12-2',

    '2-4',

    '4-6'

];

$tiposUsoPermitidos = [

    'estudio',

    'trabajo',

    'reunion',

    'proyecto',

    'otro'

];

$cantidadesPermitidas = [

    '1',

    '2',

    '3',

    '4',

    '5'

];

/*

\========================================================

VALIDACIONES

\========================================================

*/

$errores = [];

if ($nombre === '') {

    $errores[] =

        'El nombre completo es obligatorio.';

} elseif (mb_strlen($nombre) < 3) {

    $errores[] =

        'El nombre debe tener al menos 3 caracteres.';

} elseif (mb_strlen($nombre) > 150) {

    $errores[] =

        'El nombre supera la longitud permitida.';

} elseif (

    !preg_match(

        '/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u',

        $nombre

    )

) {

    $errores[] =

        'El nombre solo puede contener letras y espacios.';

}

if ($correo === '') {

    $errores[] =

        'El correo electrónico es obligatorio.';

} elseif (

    !filter_var(

        $correo,

        FILTER_VALIDATE_EMAIL

    )

) {

    $errores[] =

        'El correo electrónico no es válido.';

} elseif (

    mb_strlen($correo) > 180

) {

    $errores[] =

        'El correo supera la longitud permitida.';

}

if ($telefono === '') {

    $errores[] =

        'El teléfono o celular es obligatorio.';

} elseif (

    !preg_match(

        '/^[\d\s+\\-()]+$/',

        $telefono

    )

) {

    $errores[] =

        'El teléfono contiene caracteres no permitidos.';

} else {

    $digitos =

        preg_replace(

            '/\D/',

            '',

            $telefono

        );

    if (

        strlen($digitos) < 7 ||

        strlen($digitos) > 15

    ) {

        $errores[] =

            'El teléfono debe tener entre 7 y 15 dígitos.';

    }

}

if (

    $espacio === '' ||

    !in_array(

        $espacio,

        $espaciosPermitidos,

        true

    )

) {

    $errores[] =

        'El espacio seleccionado no es válido.';

}

if ($fechaReserva === '') {

    $errores[] =

        'La fecha de reserva es obligatoria.';

} else {

    $fechaObjeto =

        DateTime::createFromFormat(

            'Y-m-d',

            $fechaReserva

        );

    $fechaValida =

        $fechaObjeto &&

        $fechaObjeto->format('Y-m-d') === $fechaReserva;

    if (!$fechaValida) {

        $errores[] =

            'La fecha de reserva no es válida.';

    } else {

        $hoy = new DateTime('today');

        if ($fechaObjeto < $hoy) {

            $errores[] =

                'La fecha de reserva no puede ser anterior a hoy.';

        }

        if (

            (int)$fechaObjeto->format('w') === 0

        ) {

            $errores[] =

                'No se reciben reservas para los domingos.';

        }

    }

}

if (

    $horario === '' ||

    !in_array(

        $horario,

        $horariosPermitidos,

        true

    )

) {

    $errores[] =

        'El horario seleccionado no es válido.';

}

if (

    $tipoUso === '' ||

    !in_array(

        $tipoUso,

        $tiposUsoPermitidos,

        true

    )

) {

    $errores[] =

        'El tipo de uso seleccionado no es válido.';

}

if (

    $cantidadPersonas === '' ||

    !in_array(

        $cantidadPersonas,

        $cantidadesPermitidas,

        true

    )

) {

    $errores[] =

        'La cantidad de personas seleccionada no es válida.';

}

if ($detalle === '') {

    $errores[] =

        'La descripción de la solicitud es obligatoria.';

} elseif (

    mb_strlen($detalle) < 15

) {

    $errores[] =

        'La descripción debe tener al menos 15 caracteres.';

} elseif (

    mb_strlen($detalle) > 800

) {

    $errores[] =

        'La descripción no puede superar los 800 caracteres.';

}

/*

\========================================================

RESPONDER SI EXISTEN ERRORES

\========================================================

*/

if (!empty($errores)) {

    http_response_code(422);

    echo json_encode([

        'success' => false,

        'message' =>

            'Hay errores en la información enviada.',

        'errors' =>

            $errores

    ], JSON_UNESCAPED_UNICODE);

    exit;

}

/*

\========================================================

TRAZABILIDAD

\========================================================

*/

$ipOrigen =

    $_SERVER['REMOTE_ADDR']

    ?? null;

$userAgent =

    $_SERVER['HTTP_USER_AGENT']

    ?? null;

/*

\========================================================

GUARDAR EN BASE DE DATOS

\========================================================

*/

try {

    $sql = "

        INSERT INTO solicitudes_espacios (

            nombre_completo,

            correo,

            telefono,

            espacio,

            fecha_reserva,

            horario,

            tipo_uso,

            cantidad_personas,

            detalle,

            estado,

            ip_origen,

            user_agent,

            correo_enviado

        ) VALUES (

            :nombre_completo,

            :correo,

            :telefono,

            :espacio,

            :fecha_reserva,

            :horario,

            :tipo_uso,

            :cantidad_personas,

            :detalle,

            'pendiente',

            :ip_origen,

            :user_agent,

            0

        )

    ";

    $stmt =

        $pdo->prepare($sql);

    $stmt->execute([

        ':nombre_completo' =>

            $nombre,

        ':correo' =>

            $correo,

        ':telefono' =>

            $telefono,

        ':espacio' =>

            $espacio,

        ':fecha_reserva' =>

            $fechaReserva,

        ':horario' =>

            $horario,

        ':tipo_uso' =>

            $tipoUso,

        ':cantidad_personas' =>

            $cantidadPersonas,

        ':detalle' =>

            $detalle,

        ':ip_origen' =>

            $ipOrigen,

        ':user_agent' =>

            $userAgent

    ]);

    $solicitudId =

        (int)$pdo->lastInsertId();

    $numeroSolicitud =

        str_pad(

            (string)$solicitudId,

            3,

            '0',

            STR_PAD_LEFT

        );

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>

            'No fue posible registrar la solicitud.'

    ], JSON_UNESCAPED_UNICODE);

    exit;

}

/*

\========================================================

CONFIGURACIÓN DE CORREO

\========================================================

*/

$mailConfig =

    require __DIR__ . '/../config/mail.php';

$logoPath =

    __DIR__ .

    '/../public/assets/img/logobiblio.png';

/*

\========================================================

ETIQUETAS AMIGABLES

\========================================================

*/

$etiquetasEspacios = [

    'box-lectura' => 'Box de lectura',

    'sala-alas-papel' => 'Sala Alas de Papel',

    'sala-trabajo' => 'Sala de trabajo',

    'computadores' => 'Préstamo de Computadores'

];

$etiquetasHorarios = [

    '8-10' => '8:00 AM - 10:00 AM',

    '10-12' => '10:00 AM - 12:00 PM',

    '12-2' => '12:00 PM - 2:00 PM',

    '2-4' => '2:00 PM - 4:00 PM',

    '4-6' => '4:00 PM - 6:00 PM'

];

$etiquetasUso = [

    'estudio' => 'Estudio individual',

    'trabajo' => 'Trabajo',

    'reunion' => 'Reunión',

    'proyecto' => 'Proyecto académico',

    'otro' => 'Otro'

];

$etiquetasCantidad = [

    '1' => '1 persona',

    '2' => '2 personas',

    '3' => '3 personas',

    '4' => '4 personas',

    '5' => '5 personas o más'

];

$espacioTexto =

    $etiquetasEspacios[$espacio]

    ?? $espacio;

$horarioTexto =

    $etiquetasHorarios[$horario]

    ?? $horario;

$tipoUsoTexto =

    $etiquetasUso[$tipoUso]

    ?? $tipoUso;

$cantidadTexto =

    $etiquetasCantidad[$cantidadPersonas]

    ?? $cantidadPersonas;

/*

\========================================================

FECHA AMIGABLE

\========================================================

*/

$fechaTexto =

    $fechaReserva;

if (!empty($fechaObjeto)) {

    $fechaTexto =

        $fechaObjeto->format('d/m/Y');

}

/*

\========================================================

ESCAPAR PARA HTML

\========================================================

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

        $telefono,

        ENT_QUOTES,

        'UTF-8'

    );

$espacioHtml =

    htmlspecialchars(

        $espacioTexto,

        ENT_QUOTES,

        'UTF-8'

    );

$fechaHtml =

    htmlspecialchars(

        $fechaTexto,

        ENT_QUOTES,

        'UTF-8'

    );

$horarioHtml =

    htmlspecialchars(

        $horarioTexto,

        ENT_QUOTES,

        'UTF-8'

    );

$tipoUsoHtml =

    htmlspecialchars(

        $tipoUsoTexto,

        ENT_QUOTES,

        'UTF-8'

    );

$cantidadHtml =

    htmlspecialchars(

        $cantidadTexto,

        ENT_QUOTES,

        'UTF-8'

    );

$detalleHtml =

    nl2br(

        htmlspecialchars(

            $detalle,

            ENT_QUOTES,

            'UTF-8'

        )

    );

/*

\========================================================

1. CORREO INTERNO A LA BIBLIOTECA

\========================================================

*/

try {

    $mailBiblioteca =

        crearMailer();

    $mailBiblioteca->addAddress(
    'institutodeculturayturismo@tenjo-cundinamarca.gov.co',
    'Instituto de Cultura y Turismo de Tenjo'
);

$mailBiblioteca->addAddress(
    'biblioteca@tenjoculturayturismo.gov.co',
    'Biblioteca Municipal Isabel Murillo de Luque'
);

    $mailBiblioteca->addReplyTo(

        $correo,

        $nombre

    );

    $logoCidBiblioteca = '';

    if (file_exists($logoPath)) {

        $logoCidBiblioteca =

            'logo_biblioteca_solicitud_espacio_interno';

        $mailBiblioteca->addEmbeddedImage(

            $logoPath,

            $logoCidBiblioteca,

            'Biblioteca Tenjo'

        );

    }

    $contenidoInterno = '

        <div style="

            margin-bottom:24px;

            padding:16px 18px;

            background:#f7f8f7;

            border-left:4px solid #d4b300;

            color:#5e6864;

            font-size:14px;

        ">

            <strong>Número de solicitud: ' . $numeroSolicitud . '</strong>

        </div>

        <h2 style="

            margin:0 0 18px 0;

            font-size:19px;

            color:#28332f;

        ">

            Datos del solicitante

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

                margin-bottom:28px;

                font-size:14px;

            "

        >

            <tr>

                <td style="

                    width:170px;

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

        </table>

        <h2 style="

            margin:0 0 18px 0;

            font-size:19px;

            color:#28332f;

        ">

            Datos de la reserva

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

                margin-bottom:22px;

                font-size:14px;

            "

        >

            <tr>

                <td style="

                    width:170px;

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Espacio

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $espacioHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Fecha

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $fechaHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Horario

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $horarioHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Tipo de uso

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $tipoUsoHtml . '

                </td>

            </tr>

            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Cantidad de personas

                </td>

                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $cantidadHtml . '

                </td>

            </tr>

        </table>

        <div style="

            background:#f6f7f6;

            border:1px solid #e7ebe9;

            border-radius:10px;

            padding:17px;

            font-size:14px;

            line-height:1.65;

            color:#35403c;

        ">

            <strong>Descripción o necesidad del espacio</strong>

            <br><br>

            ' . $detalleHtml . '

        </div>

    ';

    $mailBiblioteca->isHTML(true);

    $mailBiblioteca->Subject =

        'Nueva solicitud de espacio - ' .

        $numeroSolicitud;

    $mailBiblioteca->Body =

        plantillaCorreoInstitucional(

            $logoCidBiblioteca,

            'Nueva solicitud de espacio',

            'Se ha registrado una nueva solicitud desde el sitio web de la Biblioteca.',

            $contenidoInterno

        );

    $mailBiblioteca->AltBody =

        "Nueva solicitud de espacio\n\n" .

        "Número de solicitud: {$numeroSolicitud}\n" .

        "Nombre: {$nombre}\n" .

        "Correo: {$correo}\n" .

        "Teléfono: {$telefono}\n" .

        "Espacio: {$espacioTexto}\n" .

        "Fecha: {$fechaTexto}\n" .

        "Horario: {$horarioTexto}\n" .

        "Tipo de uso: {$tipoUsoTexto}\n" .

        "Cantidad de personas: {$cantidadTexto}\n\n" .

        "Descripción:\n{$detalle}";

    $mailBiblioteca->send();

    $sql = "

        UPDATE solicitudes_espacios

        SET correo_enviado = 1

        WHERE id = :id

    ";

    $stmt =

        $pdo->prepare($sql);

    $stmt->execute([

        ':id' => $solicitudId

    ]);

} catch (Throwable $e) {

    error_log(

        'Error SMTP correo interno solicitud de espacio ID ' .

        $solicitudId .

        ': ' .

        $e->getMessage()

    );

}

/*

\========================================================

2. CONFIRMACIÓN AL CIUDADANO

\========================================================

*/

try {

    $mailUsuario =

        crearMailer();

    $mailUsuario->addAddress(

        $correo,

        $nombre

    );

    $mailUsuario->addReplyTo(
    'biblioteca@tenjoculturayturismo.gov.co',
    'Biblioteca Municipal Isabel Murillo de Luque'
);

    $logoCidUsuario = '';

    if (file_exists($logoPath)) {

        $logoCidUsuario =

            'logo_biblioteca_solicitud_espacio_confirmacion';

        $mailUsuario->addEmbeddedImage(

            $logoPath,

            $logoCidUsuario,

            'Biblioteca Tenjo'

        );

    }

    $contenidoUsuario = '

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Hola <strong>' . $nombreHtml . '</strong>,

        </p>

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Hemos recibido correctamente tu solicitud para el espacio

            <strong>' . $espacioHtml . '</strong>.

        </p>

        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            La solicitud queda registrada como

            <strong>pendiente de validación</strong>.

            Nuestro equipo revisará la disponibilidad del espacio y posteriormente

            te confirmará la asignación.

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

                <strong>Número de solicitud:</strong>

                ' . $numeroSolicitud . '

            </p>

            <p style="

                margin:0 0 9px 0;

                font-size:14px;

            ">

                <strong>Espacio:</strong>

                ' . $espacioHtml . '

            </p>

            <p style="

                margin:0 0 9px 0;

                font-size:14px;

            ">

                <strong>Fecha:</strong>

                ' . $fechaHtml . '

            </p>

            <p style="

                margin:0;

                font-size:14px;

            ">

                <strong>Horario:</strong>

                ' . $horarioHtml . '

            </p>

        </div>

        <p style="

            margin:0 0 18px 0;

            font-size:15px;

            line-height:1.7;

            color:#59635f;

        ">

            Recuerda que este mensaje confirma únicamente la recepción de tu solicitud.

            La reserva del espacio quedará confirmada cuando la Biblioteca valide

            su disponibilidad.

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

    $mailUsuario->isHTML(true);

    $mailUsuario->Subject =

        'Solicitud de espacio recibida - ' .

        $numeroSolicitud;

    $mailUsuario->Body =

        plantillaCorreoInstitucional(

            $logoCidUsuario,

            'Hemos recibido tu solicitud',

            'Confirmación de recepción de tu solicitud de espacio.',

            $contenidoUsuario

        );

    $mailUsuario->AltBody =

        "Hola {$nombre},\n\n" .

        "Hemos recibido correctamente tu solicitud para el espacio {$espacioTexto}.\n\n" .

        "Número de solicitud: {$numeroSolicitud}\n" .

        "Fecha: {$fechaTexto}\n" .

        "Horario: {$horarioTexto}\n\n" .

        "La solicitud queda pendiente de validación. Nuestro equipo revisará " .

        "la disponibilidad y posteriormente te confirmará la asignación.\n\n" .

        "Este correo confirma únicamente la recepción de la solicitud.\n\n" .

        "Biblioteca Municipal Isabel Murillo de Luque\n" .

        "Tenjo - Cundinamarca";

    $mailUsuario->send();

} catch (Throwable $e) {

    error_log(

        'Error SMTP confirmación solicitud de espacio ID ' .

        $solicitudId .

        ': ' .

        $e->getMessage()

    );

}

/*

\========================================================

RESPUESTA AL FRONTEND

\========================================================

*/

echo json_encode([

    'success' => true,

    'message' =>

        'Tu solicitud de espacio fue recibida correctamente.',

    'solicitud' =>

        $numeroSolicitud

], JSON_UNESCAPED_UNICODE);
