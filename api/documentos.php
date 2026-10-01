<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT
            id,
            titulo,
            descripcion,
            archivo,
            nombre_archivo_original,
            tipo_archivo,
            tamano_archivo,
            fecha_documento,
            orden,
            estado
        FROM documentos
        WHERE estado = 1
        ORDER BY orden ASC, fecha_documento DESC, id DESC";

$stmt = $pdo->query($sql);

$documentos = $stmt->fetchAll();

foreach ($documentos as &$documento) {

    if (!empty($documento['archivo'])) {
        $documento['archivo_url'] =
            'http://localhost/bibliotecatenjo/' .
            $documento['archivo'];
    } else {
        $documento['archivo_url'] = null;
    }

}

echo json_encode(
    $documentos,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);