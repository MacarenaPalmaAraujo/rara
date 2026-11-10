<?php
// api/guardar_usuario.php
// Registra una cuenta nueva y guarda su rol en la tabla Usuarios.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

$nombre = trim($data['nombre'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$contrasena = (string) ($data['contrasena'] ?? '');
$rolNombre = strtolower(trim($data['rol'] ?? 'consumidor'));

if ($nombre === '' || $email === '' || $contrasena === '') {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'El correo electrónico no es válido.']);
    exit;
}

if (mb_strlen($contrasena) < 4) {
    echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 4 caracteres.']);
    exit;
}

if (!in_array($rolNombre, ['consumidor', 'emprendedor', 'feria'], true)) {
    $rolNombre = 'consumidor';
}

try {
    // La tabla Usuarios guarda id_rol, por eso buscamos el id del rol por su nombre.
    $stmt = $pdo->prepare('SELECT id_rol FROM roles WHERE nombre = ? LIMIT 1');
    $stmt->execute([$rolNombre]);
    $rol = $stmt->fetch();
    $idRol = $rol ? (int) $rol['id_rol'] : 1; // 1 = consumidor por defecto

    $stmt = $pdo->prepare(
        'INSERT INTO Usuarios (nombre, email, contrasena, id_rol) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([
        $nombre,
        $email,
        password_hash($contrasena, PASSWORD_DEFAULT),
        $idRol
    ]);

    $idUsuario = (int) $pdo->lastInsertId();

    // Dejamos la sesión iniciada también del lado del servidor.
    $_SESSION['usuario_id'] = $idUsuario;
    $_SESSION['rol'] = $rolNombre;

    echo json_encode([
        'status' => 'success',
        'id_usuario' => $idUsuario,
        'nombre' => $nombre,
        'email' => $email,
        'rol' => $rolNombre
    ]);
} catch (PDOException $e) {
    // 23000 = clave duplicada (email ya registrado).
    if ((string) $e->getCode() === '23000') {
        echo json_encode(['status' => 'error', 'message' => 'Ese correo ya tiene una cuenta. Iniciá sesión.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la cuenta: ' . $e->getMessage()]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la cuenta: ' . $e->getMessage()]);
}
?>
