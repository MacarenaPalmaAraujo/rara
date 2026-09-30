<?php
// api/get_ferias.php
header('Content-Type: application/json');
require_once '../db.php';

try {
    // Unimos la tabla Ferias con Fechas_Feria usando sus Foreign Keys
    $sql = "SELECT f.id_feria, f.nombre_feria, f.direccion_texto, f.instagram, 
                   ff.fecha_inicio, ff.hora_apertura, ff.hora_cierre
            FROM Ferias f
            LEFT JOIN Fechas_Feria ff ON f.id_feria = ff.id_feria
            WHERE f.estado = 'activa'";

    $stmt = $pdo->query($sql);
    $ferias = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $ferias]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>