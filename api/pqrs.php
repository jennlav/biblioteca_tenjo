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



                        <!-- ENCABEZADO INSTITUCIONAL -->

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



                        <!-- TÍTULO -->

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



                        <!-- FRANJA -->

                        <tr>



                            <td style="padding:0 30px;">



                                <div style="

                                    height:4px;

                                    background:#d4b300;

                                    border-radius:4px;

                                "></div>



                            </td>



                        </tr>



                        <!-- CONTENIDO -->

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



                        <!-- FOOTER -->

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



$tipoSolicitante = trim($_POST['tipoSolicitante'] ?? '');

$nombreCompleto = trim($_POST['nombreCompleto'] ?? '');

$tipoDocumento = trim($_POST['tipoDocumento'] ?? '');

$numeroDocumento = trim($_POST['numeroDocumento'] ?? '');

$correo = trim($_POST['correoPQRS'] ?? '');

$telefono = trim($_POST['telefonoPQRS'] ?? '');

$tipoPQRS = trim($_POST['tipoPQRS'] ?? '');

$descripcion = trim($_POST['descripcionPQRS'] ?? '');



/*

========================================================

CATÁLOGOS PERMITIDOS

========================================================

*/



$tiposSolicitantePermitidos = [

    'ciudadano',

    'estudiante',

    'docente',

    'funcionario',

    'otro'

];



$tiposDocumentoPermitidos = [

    'cc',

    'ti',

    'ce',

    'pasaporte',

    'otro'

];



$tiposPQRSPermitidos = [

    'peticion',

    'queja',

    'reclamo',

    'sugerencia',

    'denuncia',

    'felicitacion'

];



/*

========================================================

VALIDACIONES

========================================================

*/



$errores = [];



if (

    $tipoSolicitante === '' ||

    !in_array(

        $tipoSolicitante,

        $tiposSolicitantePermitidos,

        true

    )

) {

    $errores[] = 'El perfil seleccionado no es válido.';

}



if ($nombreCompleto === '') {

    $errores[] = 'El nombre completo es obligatorio.';

}



if (mb_strlen($nombreCompleto) > 150) {

    $errores[] = 'El nombre supera la longitud permitida.';

}



if (

    $tipoDocumento === '' ||

    !in_array(

        $tipoDocumento,

        $tiposDocumentoPermitidos,

        true

    )

) {

    $errores[] = 'El tipo de documento no es válido.';

}



if ($numeroDocumento === '') {



    $errores[] = 'El número de documento es obligatorio.';



} elseif (

    !preg_match(

        '/^[A-Za-z0-9\\-]+$/',

        $numeroDocumento

    )

) {



    $errores[] =

        'El número de documento contiene caracteres no permitidos.';

}



if (

    mb_strlen($numeroDocumento) < 5 ||

    mb_strlen($numeroDocumento) > 20

) {

    $errores[] =

        'El documento debe tener entre 5 y 20 caracteres.';

}



if ($correo === '') {



    $errores[] = 'El correo es obligatorio.';



} elseif (

    !filter_var(

        $correo,

        FILTER_VALIDATE_EMAIL

    )

) {



    $errores[] =

        'El correo no tiene un formato válido.';

}



if (mb_strlen($correo) > 180) {

    $errores[] =

        'El correo supera la longitud permitida.';

}



if ($telefono === '') {



    $errores[] =

        'El teléfono o celular es obligatorio.';



} else {



    if (

        !preg_match(

            '/^[\d\s+\\-()]+$/',

            $telefono

        )

    ) {

        $errores[] =

            'El teléfono contiene caracteres no permitidos.';

    }



    $soloDigitos =

        preg_replace(

            '/\D/',

            '',

            $telefono

        );



    if (

        strlen($soloDigitos) < 7 ||

        strlen($soloDigitos) > 15

    ) {

        $errores[] =

            'El teléfono debe tener entre 7 y 15 dígitos.';

    }

}



if (mb_strlen($telefono) > 30) {

    $errores[] =

        'El teléfono supera la longitud permitida.';

}



if (

    $tipoPQRS === '' ||

    !in_array(

        $tipoPQRS,

        $tiposPQRSPermitidos,

        true

    )

) {

    $errores[] =

        'El tipo de PQRSDF seleccionado no es válido.';

}



if ($descripcion === '') {



    $errores[] =

        'La descripción es obligatoria.';



} elseif (

    mb_strlen($descripcion) < 15

) {



    $errores[] =

        'La descripción debe tener al menos 15 caracteres.';

}



if (

    mb_strlen($descripcion) > 1200

) {

    $errores[] =

        'La descripción no puede superar los 1200 caracteres.';

}



/*

========================================================

VALIDAR ARCHIVO OPCIONAL

========================================================

*/



$rutaArchivo = null;

$rutaFisicaGuardada = null;

$nombreArchivoOriginal = null;

$tipoArchivo = null;

$tamanoArchivo = null;

$archivoTemporal = null;

$extension = null;



$archivoSubido =

    isset($_FILES['archivoPQRS']) &&

    $_FILES['archivoPQRS']['error'] !== UPLOAD_ERR_NO_FILE;



if ($archivoSubido) {



    if (

        $_FILES['archivoPQRS']['error'] !== UPLOAD_ERR_OK

    ) {



        $errores[] =

            'Ocurrió un error al cargar el archivo.';



    } else {



        $nombreArchivoOriginal =

            $_FILES['archivoPQRS']['name'];



        $tamanoArchivo =

            (int)$_FILES['archivoPQRS']['size'];



        $archivoTemporal =

            $_FILES['archivoPQRS']['tmp_name'];



        $extension =

            strtolower(

                pathinfo(

                    $nombreArchivoOriginal,

                    PATHINFO_EXTENSION

                )

            );



        $extensionesPermitidas = [

            'pdf',

            'doc',

            'docx',

            'xls',

            'xlsx',

            'jpg',

            'jpeg',

            'png'

        ];



        if (

            !in_array(

                $extension,

                $extensionesPermitidas,

                true

            )

        ) {

            $errores[] =

                'El formato del archivo no está permitido.';

        }



        if (

            $tamanoArchivo >

            8 * 1024 * 1024

        ) {

            $errores[] =

                'El archivo no puede superar los 8 MB.';

        }

    }

}



/*

========================================================

RESPONDER SI HAY ERRORES

========================================================

*/



if (!empty($errores)) {



    http_response_code(422);



    echo json_encode([

        'success' => false,

        'message' =>

            'Hay errores en la información enviada.',

        'errors' => $errores

    ], JSON_UNESCAPED_UNICODE);



    exit;

}



/*

========================================================

GUARDAR ARCHIVO FÍSICO

========================================================

*/



if ($archivoSubido) {



    $directorioDestino =

        __DIR__ .

        '/../uploads/pqrs/';



    if (!is_dir($directorioDestino)) {



        mkdir(

            $directorioDestino,

            0755,

            true

        );

    }



    $nombreNuevo =

        'pqrs_' .

        time() .

        '_' .

        bin2hex(random_bytes(4)) .

        '.' .

        $extension;



    $rutaFisicaGuardada =

        $directorioDestino .

        $nombreNuevo;



    if (

        !move_uploaded_file(

            $archivoTemporal,

            $rutaFisicaGuardada

        )

    ) {



        http_response_code(500);



        echo json_encode([

            'success' => false,

            'message' =>

                'No fue posible guardar el archivo adjunto.'

        ], JSON_UNESCAPED_UNICODE);



        exit;

    }



    $rutaArchivo =

        'uploads/pqrs/' .

        $nombreNuevo;



    $tipoArchivo =

        strtoupper($extension);

}



/*

========================================================

TRAZABILIDAD

========================================================

*/



$ipOrigen =

    $_SERVER['REMOTE_ADDR']

    ?? null;



$userAgent =

    $_SERVER['HTTP_USER_AGENT']

    ?? null;



/*

========================================================

GUARDAR EN BASE DE DATOS

========================================================

*/



try {



    $sql = "INSERT INTO pqrs (

                tipo_solicitante,

                nombre_completo,

                tipo_documento,

                numero_documento,

                correo,

                telefono,

                tipo_pqrs,

                descripcion,

                archivo,

                nombre_archivo_original,

                tipo_archivo,

                tamano_archivo,

                estado,

                ip_origen,

                user_agent,

                correo_enviado

            ) VALUES (

                :tipo_solicitante,

                :nombre_completo,

                :tipo_documento,

                :numero_documento,

                :correo,

                :telefono,

                :tipo_pqrs,

                :descripcion,

                :archivo,

                :nombre_archivo_original,

                :tipo_archivo,

                :tamano_archivo,

                'nuevo',

                :ip_origen,

                :user_agent,

                0

            )";



    $stmt =

        $pdo->prepare($sql);



    $stmt->execute([

        ':tipo_solicitante' =>

            $tipoSolicitante,



        ':nombre_completo' =>

            $nombreCompleto,



        ':tipo_documento' =>

            $tipoDocumento,



        ':numero_documento' =>

            $numeroDocumento,



        ':correo' =>

            $correo,



        ':telefono' =>

            $telefono,



        ':tipo_pqrs' =>

            $tipoPQRS,



        ':descripcion' =>

            $descripcion,



        ':archivo' =>

            $rutaArchivo,



        ':nombre_archivo_original' =>

            $nombreArchivoOriginal,



        ':tipo_archivo' =>

            $tipoArchivo,



        ':tamano_archivo' =>

            $tamanoArchivo,



        ':ip_origen' =>

            $ipOrigen,



        ':user_agent' =>

            $userAgent

    ]);



    $pqrsId =

        (int)$pdo->lastInsertId();



    /*

    ========================================================

    FORMATO DEL NÚMERO DE RADICADO

    Mínimo 3 dígitos: 001, 002, 015, 125, 1000...

    ========================================================

    */



    $radicado =

        str_pad(

            (string)$pqrsId,

            3,

            '0',

            STR_PAD_LEFT

        );



} catch (PDOException $e) {



    /*

    ====================================================

    SI FALLA BD, ELIMINAR ARCHIVO YA GUARDADO

    ====================================================

    */



    if (

        $rutaFisicaGuardada !== null &&

        file_exists($rutaFisicaGuardada)

    ) {

        unlink($rutaFisicaGuardada);

    }



    http_response_code(500);



    echo json_encode([

        'success' => false,

        'message' =>

            'No fue posible registrar la solicitud.'

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



$logoPath =

    __DIR__ .

    '/../public/assets/img/logobiblio.png';



/*

========================================================

ETIQUETAS AMIGABLES

========================================================

*/



$etiquetasSolicitante = [

    'ciudadano' => 'Ciudadano',

    'estudiante' => 'Estudiante',

    'docente' => 'Docente',

    'funcionario' => 'Funcionario',

    'otro' => 'Otro'

];



$etiquetasDocumento = [

    'cc' => 'Cédula de ciudadanía',

    'ti' => 'Tarjeta de identidad',

    'ce' => 'Cédula de extranjería',

    'pasaporte' => 'Pasaporte',

    'otro' => 'Otro'

];



$etiquetasPQRS = [

    'peticion' => 'Petición',

    'queja' => 'Queja',

    'reclamo' => 'Reclamo',

    'sugerencia' => 'Sugerencia',

    'denuncia' => 'Denuncia',

    'felicitacion' => 'Felicitación'

];



$tipoSolicitanteTexto =

    $etiquetasSolicitante[$tipoSolicitante]

    ?? $tipoSolicitante;



$tipoDocumentoTexto =

    $etiquetasDocumento[$tipoDocumento]

    ?? $tipoDocumento;



$tipoPQRSTexto =

    $etiquetasPQRS[$tipoPQRS]

    ?? $tipoPQRS;



/*

========================================================

ESCAPAR DATOS PARA HTML

========================================================

*/



$tipoSolicitanteHtml =

    htmlspecialchars(

        $tipoSolicitanteTexto,

        ENT_QUOTES,

        'UTF-8'

    );



$nombreHtml =

    htmlspecialchars(

        $nombreCompleto,

        ENT_QUOTES,

        'UTF-8'

    );



$tipoDocumentoHtml =

    htmlspecialchars(

        $tipoDocumentoTexto,

        ENT_QUOTES,

        'UTF-8'

    );



$numeroDocumentoHtml =

    htmlspecialchars(

        $numeroDocumento,

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



$tipoPQRSHTML =

    htmlspecialchars(

        $tipoPQRSTexto,

        ENT_QUOTES,

        'UTF-8'

    );



$descripcionHtml =

    nl2br(

        htmlspecialchars(

            $descripcion,

            ENT_QUOTES,

            'UTF-8'

        )

    );



/*

========================================================

1\. CORREO INTERNO A LA BIBLIOTECA

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
        'institutodeculturayturismo@tenjo-cundinamarca.gov.co',
        'Instituto de Cultura y Turismo de Tenjo'
    );

    $mailBiblioteca->addAddress(
        'biblioteca@tenjoculturayturismo.gov.co',
        'Biblioteca Municipal Isabel Murillo de Luque'
    );



    /*

    ====================================================

    RESPONDER AL CIUDADANO

    ====================================================

    */



    $mailBiblioteca->addReplyTo(

        $correo,

        $nombreCompleto

    );



    /*

    ====================================================

    LOGO EMBEBIDO

    ====================================================

    */



    $logoCidBiblioteca = '';



    if (file_exists($logoPath)) {



        $logoCidBiblioteca =

            'logo_biblioteca_pqrs_interno';



        $mailBiblioteca->addEmbeddedImage(

            $logoPath,

            $logoCidBiblioteca,

            'Biblioteca Tenjo'

        );

    }



    /*

    ====================================================

    ADJUNTAR ARCHIVO DE LA PQRSDF

    ====================================================

    */



    if (

        $rutaFisicaGuardada !== null &&

        file_exists($rutaFisicaGuardada)

    ) {



        $mailBiblioteca->addAttachment(

            $rutaFisicaGuardada,

            $nombreArchivoOriginal ?: basename($rutaFisicaGuardada)

        );

    }



    /*

    ====================================================

    CONTENIDO DEL CORREO INTERNO

    ====================================================

    */



    $contenidoInterno = '



        <div style="

            margin-bottom:24px;

            padding:16px 18px;

            background:#f7f8f7;

            border-left:4px solid #d4b300;

            color:#5e6864;

            font-size:14px;

        ">

            <strong>Radicado No. ' . $radicado . '</strong>

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

                    width:155px;

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Perfil

                </td>



                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $tipoSolicitanteHtml . '

                </td>

            </tr>



            <tr>

                <td style="

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

                    Tipo documento

                </td>



                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $tipoDocumentoHtml . '

                </td>

            </tr>



            <tr>

                <td style="

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Documento

                </td>



                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $numeroDocumentoHtml . '

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

            Detalles de la solicitud

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

                    width:155px;

                    padding:11px 8px;

                    font-weight:bold;

                    border-bottom:1px solid #e7ebe9;

                ">

                    Tipo de PQRSDF

                </td>



                <td style="

                    padding:11px 8px;

                    border-bottom:1px solid #e7ebe9;

                ">

                    ' . $tipoPQRSHTML . '

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

            ' . $descripcionHtml . '

        </div>



        ' . (

            $nombreArchivoOriginal

                ? '

                    <p style="

                        margin:20px 0 0 0;

                        font-size:13px;

                        color:#65706b;

                    ">

                        <strong>Archivo adjunto:</strong>

                        ' . htmlspecialchars(

                            $nombreArchivoOriginal,

                            ENT_QUOTES,

                            'UTF-8'

                        ) . '

                    </p>

                '

                : ''

        ) . '

    ';



    /*

    ====================================================

    ASUNTO Y CUERPO

    ====================================================

    */



    $mailBiblioteca->isHTML(true);



    $mailBiblioteca->Subject =

        'Nueva ' .

        $tipoPQRSTexto .

        ' - Radicado ' .

        $radicado;



    $mailBiblioteca->Body =

        plantillaCorreoInstitucional(

            $logoCidBiblioteca,

            'Nueva PQRSDF recibida',

            'Se ha registrado una nueva solicitud desde el sitio web de la Biblioteca.',

            $contenidoInterno

        );



    /*

    ====================================================

    TEXTO PLANO

    ====================================================

    */



    $mailBiblioteca->AltBody =

        "Nueva PQRSDF recibida\n\n" .

        "Radicado: {$radicado}\n" .

        "Perfil: {$tipoSolicitanteTexto}\n" .

        "Nombre: {$nombreCompleto}\n" .

        "Tipo de documento: {$tipoDocumentoTexto}\n" .

        "Documento: {$numeroDocumento}\n" .

        "Correo: {$correo}\n" .

        "Teléfono: {$telefono}\n" .

        "Tipo de PQRSDF: {$tipoPQRSTexto}\n\n" .

        "Descripción:\n{$descripcion}\n\n" .

        (

            $nombreArchivoOriginal

                ? "Archivo adjunto: {$nombreArchivoOriginal}"

                : "Sin archivo adjunto."

        );



    /*

    ====================================================

    ENVIAR CORREO INTERNO

    ====================================================

    */



    $mailBiblioteca->send();



    /*

    ====================================================

    MARCAR NOTIFICACIÓN INTERNA COMO ENVIADA

    ====================================================

    */



    $sql = "UPDATE pqrs

            SET correo_enviado = 1

            WHERE id = :id";



    $stmt =

        $pdo->prepare($sql);



    $stmt->execute([

        ':id' => $pqrsId

    ]);



} catch (Throwable $e) {



    /*

    ====================================================

    SI FALLA EL SMTP, LA PQRSDF YA ESTÁ GUARDADA

    ====================================================

    */



    error_log(

        'Error SMTP correo interno PQRS ID ' .

        $pqrsId .

        ': ' .

        $e->getMessage()

    );

}



/*

========================================================

2\. CONFIRMACIÓN AUTOMÁTICA AL CIUDADANO

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

        $nombreCompleto

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

            'logo_biblioteca_pqrs_confirmacion';



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

            Hola <strong>' . $nombreHtml . '</strong>,

        </p>



        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Hemos recibido correctamente tu

            <strong>' . $tipoPQRSHTML . '</strong>

            a través del sitio web de la

            <strong>Biblioteca Municipal Isabel Murillo de Luque</strong>.

        </p>



        <p style="

            margin:0 0 18px 0;

            font-size:16px;

            line-height:1.7;

        ">

            Tu solicitud será revisada por nuestro equipo y se dará respuesta

            a través de los datos de contacto registrados.

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

                <strong>Tipo de solicitud:</strong>

                ' . $tipoPQRSHTML . '

            </p>



            <p style="

                margin:0;

                font-size:14px;

            ">

                <strong>Número de radicado:</strong>

                ' . $radicado . '

            </p>



        </div>



        <p style="

            margin:0 0 18px 0;

            font-size:15px;

            line-height:1.7;

            color:#59635f;

        ">

            Te recomendamos conservar este correo y tu número de radicado

            como constancia de recepción de la solicitud.

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

        'Confirmación PQRSDF - Radicado ' .

        $radicado;



    $mailUsuario->Body =

        plantillaCorreoInstitucional(

            $logoCidUsuario,

            'Hemos recibido tu PQRSDF',

            'Confirmación de recepción de tu solicitud.',

            $contenidoUsuario

        );



    /*

    ====================================================

    TEXTO PLANO

    ====================================================

    */



    $mailUsuario->AltBody =

        "Hola {$nombreCompleto},\n\n" .

        "Hemos recibido correctamente tu {$tipoPQRSTexto} a través del sitio web " .

        "de la Biblioteca Municipal Isabel Murillo de Luque.\n\n" .

        "Número de radicado: {$radicado}\n" .

        "Tipo de solicitud: {$tipoPQRSTexto}\n\n" .

        "Tu solicitud será revisada por nuestro equipo y se dará respuesta " .

        "a través de los datos de contacto registrados.\n\n" .

        "Conserva este correo y tu número de radicado como constancia de recepción.\n\n" .

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

    SI FALLA LA CONFIRMACIÓN:

    - PQRSDF permanece guardada

    - archivo permanece guardado

    - correo interno no se afecta

    ====================================================

    */



    error_log(

        'Error SMTP confirmación usuario PQRS ID ' .

        $pqrsId .

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

        'Tu solicitud fue recibida correctamente.',

    'radicado' =>

        $radicado

], JSON_UNESCAPED_UNICODE);