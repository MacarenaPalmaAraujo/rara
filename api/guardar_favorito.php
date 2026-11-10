<?php
// api/guardar_favorito.php
// Guarda o quita una feria de los favoritos del consumidor.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$idUsuario = (int) ($_SESSION['usuario_id'] ?? $data['id_consumidor'] ?? 0);
$idFeria = (int) ($data['id_feria'] ?? 0);

if ($idUsuario <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para guardar favoritos.']);
    exit;
}
if ($idFeria <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Falta indicar la feria.']);
    exit;
}

try {
    // Verificamos el rol real del usuario (aunque la sesión PHP se haya vencido).
    $stmt = $pdo->prepare(
        'SELECT u.id_usuario, LOWER(r.nombre) AS rol
         FROM Usuarios u
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE u.id_usuario = ?'
    );
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'La cuenta no existe.']);
        exit;
    }
    if (($usuario['rol'] ?? '') !== 'consumidor') {
        echo json_encode(['status' => 'error', 'message' => 'Solo los consumidores pueden guardar ferias en favoritos.']);
        exit;
    }

    // La feria debe existir.
    $stmt = $pdo->prepare('SELECT id_feria FROM Ferias WHERE id_feria = ?');
    $stmt->execute([$idFeria]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La feria ya no existe.']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id_favorito FROM Favoritos WHERE id_consumidor = ? AND id_feria = ?');
    $stmt->execute([$idUsuario, $idFeria]);
    $existe = $stmt->fetch();

    if ($existe) {
        $stmtEliminar = $pdo->prepare('DELETE FROM Favoritos WHERE id_consumidor = ? AND id_feria = ?');
        $stmtEliminar->execute([$idUsuario, $idFeria]);
        echo json_encode(['status' => 'success', 'is_favorito' => false, 'message' => 'Se quitó de favoritos.']);
    } else {
        $stmtInsert = $pdo->prepare('INSERT INTO Favoritos (id_consumidor, id_feria) VALUES (?, ?)');
        $stmtInsert->execute([$idUsuario, $idFeria]);
        echo json_encode(['status' => 'success', 'is_favorito' => true, 'message' => 'Se guardó en favoritos.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar el favorito: ' . $e->getMessage()]);
}
?>
