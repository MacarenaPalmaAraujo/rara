<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['contrasena'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Usuarios (nombre, email, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['nombre'],
        $data['email'],
        password_hash($data['contrasena'], PASSWORD_DEFAULT),
        $data['rol'] ?? 'consumidor'
    ]);

    $id_usuario = $pdo->lastInsertId();
    echo json_encode(["status" => "success", "id_usuario" => $id_usuario, "nombre" => $data['nombre']]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "El email ya está registrado o hubo un error."]);
}
?>