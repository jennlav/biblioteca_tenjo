<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

/*
========================================================
PERMITIR SOLO GET
========================================================
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

/*
========================================================
BUSCAR AVISO ACTIVO Y VIGENTE
========================================================
*/

try {

    $sql = "
        SELECT
            id,
            titulo,
            descripcion,
            imagen,
            boton_texto,
            boton_url,
            fecha_inicio,
            fecha_fin
        FROM popup_home
        WHERE estado = 1
          AND (
                fecha_inicio IS NULL
                OR fecha_inicio <= NOW()
              )
          AND (
                fecha_fin IS NULL
                OR fecha_fin >= NOW()
              )
        ORDER BY id DESC
        LIMIT 1
    ";

    $stmt =
        $pdo->prepare($sql);

    $stmt->execute();

    $popup =
        $stmt->fetch();

    /*
    ====================================================
    NO HAY AVISO VIGENTE
    ====================================================
    */

    if (!$popup) {

        echo json_encode([
            'success' => true,
            'popup' => null
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    /*
    ====================================================
    GENERAR URL DE IMAGEN
    ====================================================
    */

    $popup['imagen_url'] =
        null;

    if (!empty($popup['imagen'])) {

        $popup['imagen_url'] =
            '/bibliotecatenjo/' .
            ltrim(
                $popup['imagen'],
                '/'
            );
    }

    /*
    ====================================================
    RESPUESTA
    ====================================================
    */

    echo json_encode([
        'success' => true,
        'popup' => $popup
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' =>
            'No fue posible consultar el aviso.'
    ], JSON_UNESCAPED_UNICODE);
}