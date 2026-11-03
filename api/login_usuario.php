<?php
// api/login_usuario.php
// Valida el correo y la contraseña contra la tabla Usuarios y devuelve el rol.
header('Content-Type: application/json');
require_once '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

$email = trim($data['email'] ?? '');
$contrasena = trim($data['contrasena'] ?? '');

if ($email === '' || $contrasena === '') {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, completá todos los campos.']);
    exit;
}

try {
    // El rol se guarda como id_rol; unimos con la tabla roles para devolver su nombre.
    $stmt = $pdo->prepare(
        "SELECT u.id_usuario, u.nombre, u.email, u.contrasena, r.nombre AS rol
         FROM Usuarios u
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE u.email = ?"
    );
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Esta cuenta no existe. Verificá el correo o creá una cuenta.'
        ]);
        exit;
    }

    if (!password_verify($contrasena, $usuario['contrasena'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Contraseña incorrecta.'
        ]);
        exit;
    }

    $rol = strtolower($usuario['rol'] ?? 'consumidor');

    // Guardamos la sesión del servidor para las APIs que validan permisos.
    $_SESSION['usuario_id'] = (int) $usuario['id_usuario'];
    $_SESSION['rol'] = $rol;

    echo json_encode([
        'status' => 'success',
        'usuario' => [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $rol
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
