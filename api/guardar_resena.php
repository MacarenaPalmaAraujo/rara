<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

try {
    $stmt = $pdo->prepare("INSERT INTO Resenas (id_consumidor, id_feria, calificacion, comentario) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['id_consumidor'] ?? 1,
        $data['id_feria'] ?? 1,
        $data['calificacion'],
        $data['comentario']
    ]);

    echo json_encode(["status" => "success", "message" => "Reseña guardada"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>