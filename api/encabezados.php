<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$vista = trim($_GET['vista'] ?? '');

if ($vista === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Debe indicar la vista.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $sql = "
        SELECT
            id,
            vista,
            etiqueta,
            titulo,
            descripcion,
            estado,
            updated_at
        FROM encabezados_vistas
        WHERE vista = :vista
          AND estado = 1
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':vista' => $vista
    ]);

    $encabezado = $stmt->fetch();

    if (!$encabezado) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'No se encontró un encabezado activo para esta vista.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => (int)$encabezado['id'],
            'vista' => $encabezado['vista'],
            'etiqueta' => $encabezado['etiqueta'],
            'titulo' => $encabezado['titulo'],
            'descripcion' => $encabezado['descripcion'],
            'estado' => (int)$encabezado['estado'],
            'updated_at' => $encabezado['updated_at']
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No fue posible consultar el encabezado.'
    ], JSON_UNESCAPED_UNICODE);
}