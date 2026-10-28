<?php
header('Content-Type: application/json');
require_once '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$data = json_decode(file_get_contents("php://input"), true);
// 1. Jerarquía
if (empty($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Necesitás iniciar sesión para publicar una reseña."]);
    exit;
}
$rolSesion = $_SESSION['rol'] ?? 'consumidor';
// 2. Jerarquía
if ($rolSesion !== 'consumidor') {
    echo json_encode(["status" => "error", "message" => "Solo los usuarios con rol de consumidor pueden publicar reseñas."]);
    exit;
}
if (!isset($data['id_feria']) || !isset($data['calificacion'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
    exit;
}
try {
    $stmt = $pdo->prepare("INSERT INTO Resenas (id_consumidor, id_feria, calificacion, comentario) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        (int) $_SESSION['usuario_id'],
        (int) $data['id_feria'],
        (int) $data['calificacion'],
        $data['comentario'] ?? null
    ]);
    echo json_encode(["status" => "success", "message" => "Reseña guardada"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al guardar la reseña: " . $e->getMessage()]);
}
?>
