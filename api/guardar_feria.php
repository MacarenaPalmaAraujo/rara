<?php
header('Content-Type: application/json');
require_once '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$data = json_decode(file_get_contents("php://input"), true);
// 1. Jerarquía
if (empty($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Necesitás iniciar sesión para registrar una feria."]);
    exit;
}
$rolSesion = $_SESSION['rol'] ?? 'consumidor';
// 2. Jerarquía
if (!in_array($rolSesion, ['feria'], true)) {
    echo json_encode(["status" => "error", "message" => "Tu rol de usuario no tiene permisos para registrar ferias."]);
    exit;
}
if (!isset($data['nombre_feria']) || !isset($data['direccion_texto'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
    exit;
}
try {
    $stmt = $pdo->prepare("INSERT INTO Ferias (id_organizador, nombre_feria, direccion_texto, estado, descripcion, imagen_url, categoria) VALUES (?, ?, ?, 'activa', ?, ?, ?)");
    $id_organizador = (int) $_SESSION['usuario_id'];
    $stmt->execute([
        $id_organizador,
        $data['nombre_feria'],
        $data['direccion_texto'],
        $data['descripcion'] ?? null,
        $data['imagen_url'] ?? null,
        $data['categoria'] ?? null
    ]);
    $id_feria = $pdo->lastInsertId();
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
    echo json_encode(["status" => "error", "message" => "Error al registrar la feria: " . $e->getMessage()]);
}
?>
