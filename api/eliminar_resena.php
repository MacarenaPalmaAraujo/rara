<?php
// api/eliminar_resena.php
// Elimina un comentario. Puede borrarlo su autor o el organizador de la feria.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$idResena = (int) ($data['id_resena'] ?? 0);
$idUsuario = (int) ($_SESSION['usuario_id'] ?? $data['id_usuario'] ?? 0);

if ($idResena <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Falta indicar el comentario que querés eliminar.']);
    exit;
}
if ($idUsuario <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para eliminar un comentario.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'SELECT r.id_resena, r.id_consumidor, r.id_feria, f.id_organizador
         FROM resenas r
         LEFT JOIN Ferias f ON f.id_feria = r.id_feria
         WHERE r.id_resena = ?'
    );
    $stmt->execute([$idResena]);
    $resena = $stmt->fetch();

    if (!$resena) {
        echo json_encode(['status' => 'error', 'message' => 'El comentario ya no existe.']);
        exit;
    }

    $esAutor = (int) $resena['id_consumidor'] === $idUsuario;
    $esOrganizador = (int) ($resena['id_organizador'] ?? 0) === $idUsuario;

    if (!$esAutor && !$esOrganizador) {
        echo json_encode(['status' => 'error', 'message' => 'Solo el autor del comentario o el organizador de la feria pueden eliminarlo.']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM resenas WHERE id_resena = ?');
    $stmt->execute([$idResena]);

    echo json_encode(['status' => 'success', 'message' => 'Comentario eliminado.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el comentario: ' . $e->getMessage()]);
}
?>
