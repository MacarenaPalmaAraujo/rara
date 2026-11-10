<?php
// api/get_ferias.php
// Devuelve las ferias activas con la primera fecha/horario cargado.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    // Se elige una sola fecha por feria para que el calendario no duplique tarjetas.
    $sql = "SELECT f.id_feria, f.id_organizador, f.nombre_feria, f.direccion_texto,
                   f.instagram, f.descripcion, f.imagen_url, f.categoria, f.estado,
                   ff.fecha_inicio, ff.fecha_fin, ff.hora_apertura, ff.hora_cierre,
                   u.nombre AS organizador
            FROM Ferias f
            LEFT JOIN Fechas_Feria ff ON ff.id_fecha = (
                SELECT MIN(ff2.id_fecha)
                FROM Fechas_Feria ff2
                WHERE ff2.id_feria = f.id_feria
            )
            LEFT JOIN Usuarios u ON u.id_usuario = f.id_organizador
            WHERE f.estado = 'activa'
            ORDER BY ff.fecha_inicio ASC, f.id_feria ASC";

    $stmt = $pdo->query($sql);
    $ferias = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $ferias]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
