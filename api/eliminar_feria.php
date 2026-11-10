<?php
// api/eliminar_feria.php
// Elimina una feria. Solo puede hacerlo el usuario que la organizó.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$idFeria = (int) ($data['id_feria'] ?? 0);
$idUsuario = (int) ($_SESSION['usuario_id'] ?? $data['id_usuario'] ?? 0);

if ($idFeria <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Falta indicar la feria que querés eliminar.']);
    exit;
}
if ($idUsuario <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para eliminar una feria.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'SELECT f.id_feria, f.id_organizador, LOWER(r.nombre) AS rol
         FROM Ferias f
         INNER JOIN Usuarios u ON u.id_usuario = f.id_organizador
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE f.id_feria = ?'
    );
    $stmt->execute([$idFeria]);
    $feria = $stmt->fetch();

    if (!$feria) {
        echo json_encode(['status' => 'error', 'message' => 'La feria ya no existe.']);
        exit;
    }

    if ((int) $feria['id_organizador'] !== $idUsuario) {
        echo json_encode(['status' => 'error', 'message' => 'Solo el organizador de esta feria puede eliminarla.']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM Ferias WHERE id_feria = ?');
    $stmt->execute([$idFeria]);

    echo json_encode(['status' => 'success', 'message' => 'La feria se eliminó correctamente.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la feria: ' . $e->getMessage()]);
}
?>
