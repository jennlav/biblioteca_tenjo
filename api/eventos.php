<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

/*
 * Endpoint público de eventos.
 * Expone únicamente registros activos y genera rutas de imagen
 * independientes del dominio para soportar entorno local y producción.
 */
$sql = "SELECT
            id,
            titulo,
            descripcion,
            fecha_inicio,
            fecha_fin,
            categoria,
            imagen,
            estado
        FROM eventos
        WHERE estado = 1
        ORDER BY fecha_inicio ASC";

$stmt = $pdo->query($sql);
$eventos = $stmt->fetchAll();

/* Calcula automáticamente la ruta base del proyecto según el entorno. */
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$projectBase = rtrim(dirname(dirname($scriptName)), '/.');

foreach ($eventos as &$evento) {
    if (!empty($evento['imagen'])) {
        $imagePath = '/' . ltrim((string) $evento['imagen'], '/');
        $evento['imagen_url'] = ($projectBase ?: '') . $imagePath;
    } else {
        $evento['imagen_url'] = null;
    }
}
unset($evento);

echo json_encode(
    $eventos,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
