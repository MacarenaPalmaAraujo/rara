<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['contrasena'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
    exit;
}

try {
    // Mapeo de roles a los ids de la tabla roles (1=consumidor, 2=emprendedor, 3=feria)
    $rolesMap = [
        'consumidor' => 1,
        'emprendedor' => 2,
        'feria' => 3
    ];
    $idRol = $rolesMap[strtolower($data['rol'] ?? 'consumidor')] ?? 1;

    $stmt = $pdo->prepare("INSERT INTO Usuarios (nombre, email, contrasena, id_rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['nombre'],
        $data['email'],
        password_hash($data['contrasena'], PASSWORD_DEFAULT),
        $idRol
    ]);

    $id_usuario = $pdo->lastInsertId();
    echo json_encode(["status" => "success", "id_usuario" => $id_usuario, "nombre" => $data['nombre']]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "El email ya está registrado o hubo un error."]);
}
?>