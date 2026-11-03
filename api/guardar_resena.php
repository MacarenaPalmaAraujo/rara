<?php
// api/guardar_resena.php
// Guarda la reseña de un consumidor sobre una feria.
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

$idConsumidor = (int) ($data['id_consumidor'] ?? 0);
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
    // La tabla puede no existir en instalaciones viejas: la creamos si hace falta.
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS resenas (
            id_resena INT AUTO_INCREMENT PRIMARY KEY,
            id_consumidor INT NOT NULL,
            id_feria INT NOT NULL,
            calificacion INT NOT NULL,
            comentario TEXT NULL,
            fecha_resena TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY idx_resenas_feria (id_feria),
            KEY idx_resenas_consumidor (id_consumidor)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Verificamos que existan el usuario y la feria antes de guardar.
    $stmt = $pdo->prepare('SELECT id_usuario FROM Usuarios WHERE id_usuario = ?');
    $stmt->execute([$idConsumidor]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La cuenta del consumidor no existe.']);
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
