<?php
// api/get_favoritos.php
// Devuelve las ferias favoritas del consumidor.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = (int) ($_SESSION['usuario_id'] ?? $_GET['id_consumidor'] ?? 0);

if ($idUsuario <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para ver tus favoritos.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT f.id_feria, f.id_organizador, f.nombre_feria, f.direccion_texto,
                f.descripcion, f.imagen_url, f.categoria, f.instagram,
                ff.fecha_inicio, ff.fecha_fin, ff.hora_apertura, ff.hora_cierre,
                fav.fecha_guardado
         FROM Favoritos fav
         INNER JOIN Ferias f ON fav.id_feria = f.id_feria
         LEFT JOIN Fechas_Feria ff ON ff.id_fecha = (
             SELECT MIN(ff2.id_fecha)
             FROM Fechas_Feria ff2
             WHERE ff2.id_feria = f.id_feria
         )
         WHERE fav.id_consumidor = ?
         ORDER BY fav.fecha_guardado DESC"
    );
    $stmt->execute([$idUsuario]);
    $favoritos = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $favoritos]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener los favoritos: ' . $e->getMessage()]);
}
?>
