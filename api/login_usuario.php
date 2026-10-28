<?php
header('Content-Type: application/json');
require_once '../db.php';

// Iniciar sesión PHP para poder guardar el estado de autenticación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Leer los datos JSON enviados por el frontend
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$contrasena = trim($data['contrasena'] ?? '');

if (empty($email) || empty($contrasena)) {
    echo json_encode(["status" => "error", "message" => "Por favor, completa todos los campos."]);
    exit;
}

try {
    // Buscar al usuario por su email uniéndolo con su rol
    $stmt = $pdo->prepare("SELECT u.id_usuario, u.nombre, u.email, u.contrasena, r.nombre AS rol FROM Usuarios u INNER JOIN roles r ON u.id_rol = r.id_rol WHERE u.email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // 1. Si no existe el usuario en la BD
    if (!$usuario) {
        echo json_encode([
            "status" => "error", 
            "message" => "Esta cuenta no existe. Verificá el correo o creá una cuenta."
        ]);
        exit;
    }

    // 2. Si la contraseña no coincide
    if (!password_verify($contrasena, $usuario['contrasena'])) {
        echo json_encode([
            "status" => "error", 
            "message" => "Contraseña incorrecta."
        ]);
        exit;
    }

    // 3. Login exitoso
    // Guardamos la sesión en el servidor para restringir acciones por rol
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['rol'] = $usuario['rol'];

    echo json_encode([
        "status" => "success",
        "usuario" => [
            "id_usuario" => $usuario['id_usuario'],
            "nombre" => $usuario['nombre'],
            "email" => $usuario['email'],
            "rol" => $usuario['rol']
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error en el servidor: " . $e->getMessage()]);
}
?>