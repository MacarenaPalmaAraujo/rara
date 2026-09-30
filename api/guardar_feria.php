<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

try {
    // 1. Insertar la Feria en la tabla Ferias
    $stmt = $pdo->prepare("INSERT INTO Ferias (id_organizador, nombre_feria, direccion_texto, estado) VALUES (?, ?, ?, 'activa')");
    // Usamos el id_organizador enviado o 1 por defecto
    $id_organizador = $data['id_organizador'] ?? 1; 
    $stmt->execute([$id_organizador, $data['nombre_feria'], $data['direccion_texto']]);
    
    $id_feria = $pdo->lastInsertId();

    // 2. Insertar la fecha en Fechas_Feria
    if (isset($data['fecha_inicio'])) {
        $stmtFecha = $pdo->prepare("INSERT INTO Fechas_Feria (id_feria, fecha_inicio, fecha_fin, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?, ?)");
        $stmtFecha->execute([
            $id_feria,
            $data['fecha_inicio'],
            $data['fecha_inicio'],
            $data['hora_apertura'] ?? '12:00:00',
            $data['hora_cierre'] ?? '19:00:00'
        ]);
    }

    echo json_encode(["status" => "success", "message" => "Feria registrada en la BD con éxito"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>