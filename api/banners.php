<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT
            id,
            titulo,
            descripcion,
            imagen,
            boton1_texto,
            boton1_url,
            boton2_texto,
            boton2_url,
            orden,
            estado
        FROM banners
        WHERE estado = 1
        ORDER BY orden ASC, id ASC";

$stmt = $pdo->query($sql);

$banners = $stmt->fetchAll();

foreach ($banners as &$banner) {

    if (!empty($banner['imagen'])) {
        $banner['imagen_url'] =
            'http://localhost/bibliotecatenjo/' .
            $banner['imagen'];
    } else {
        $banner['imagen_url'] = null;
    }

}

echo json_encode(
    $banners,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);