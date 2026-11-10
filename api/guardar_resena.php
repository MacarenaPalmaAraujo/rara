<?php
// api/guardar_resena.php
// Guarda la reseña de un consumidor sobre una feria.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

$idConsumidor = (int) ($_SESSION['usuario_id'] ?? $data['id_consumidor'] ?? 0);
$idFeria      = (int) ($data['id_feria'] ?? 0);
$calificacion = (int) ($data['calificacion'] ?? 0);
$comentario   = trim($data['comentario'] ?? '');

if ($idConsumidor <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para publicar una reseña.']);
    exit;
}
if ($idFeria <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Elegí una feria para dejar tu reseña.']);
    exit;
}
if ($calificacion < 1 || $calificacion > 5) {
    echo json_encode(['status' => 'error', 'message' => 'La calificación debe ser de 1 a 5 estrellas.']);
    exit;
}
if ($comentario === '') {
    echo json_encode(['status' => 'error', 'message' => 'Escribí un comentario para publicar tu reseña.']);
    exit;
}

try {
    // Verificamos que existan el usuario y la feria antes de guardar.
    $stmt = $pdo->prepare(
        'SELECT u.id_usuario, LOWER(r.nombre) AS rol
         FROM Usuarios u
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE u.id_usuario = ?'
    );
    $stmt->execute([$idConsumidor]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'La cuenta del consumidor no existe.']);
        exit;
    }
    if (($usuario['rol'] ?? '') !== 'consumidor') {
        echo json_encode(['status' => 'error', 'message' => 'Solo los usuarios con rol de consumidor pueden publicar reseñas.']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id_feria FROM Ferias WHERE id_feria = ?');
    $stmt->execute([$idFeria]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La feria ya no existe.']);
        exit;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO resenas (id_consumidor, id_feria, calificacion, comentario)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$idConsumidor, $idFeria, $calificacion, $comentario]);

    $idResena = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare(
        "SELECT r.id_resena, r.id_consumidor, r.id_feria, r.calificacion,
                r.comentario, r.fecha_resena, COALESCE(u.nombre, 'Consumidor') AS autor
         FROM resenas r
         LEFT JOIN Usuarios u ON u.id_usuario = r.id_consumidor
         WHERE r.id_resena = ?"
    );
    $stmt->execute([$idResena]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Reseña guardada correctamente',
        'resena' => $stmt->fetch()
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar la reseña: ' . $e->getMessage()]);
}
?>
