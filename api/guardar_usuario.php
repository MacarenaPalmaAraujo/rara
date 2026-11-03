<?php
// api/guardar_usuario.php
// Registra una cuenta nueva y guarda su rol en la tabla Usuarios.
header('Content-Type: application/json');
require_once '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

if (empty($data['nombre']) || empty($data['email']) || empty($data['contrasena'])) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
    exit;
}

$rolNombre = strtolower(trim($data['rol'] ?? 'consumidor'));

try {
    // La tabla Usuarios guarda id_rol, por eso buscamos el id del rol por su nombre.
    $stmt = $pdo->prepare('SELECT id_rol FROM roles WHERE nombre = ?');
    $stmt->execute([$rolNombre]);
    $rol = $stmt->fetch();
    $idRol = $rol ? (int) $rol['id_rol'] : 1; // 1 = consumidor por defecto

    $stmt = $pdo->prepare(
        'INSERT INTO Usuarios (nombre, email, contrasena, id_rol) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([
        trim($data['nombre']),
        trim($data['email']),
        password_hash($data['contrasena'], PASSWORD_DEFAULT),
        $idRol
    ]);

    $idUsuario = (int) $pdo->lastInsertId();

    // Dejamos la sesión iniciada también del lado del servidor.
    $_SESSION['usuario_id'] = $idUsuario;
    $_SESSION['rol'] = $rolNombre;

    echo json_encode([
        'status' => 'success',
        'id_usuario' => $idUsuario,
        'nombre' => trim($data['nombre']),
        'rol' => $rolNombre
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado o hubo un error.']);
}
?>
