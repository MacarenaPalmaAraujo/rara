<?php
header('Content-Type: application/json');
require_once '../db.php';

// Iniciar sesión PHP para verificar el usuario autenticado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents("php://input"), true);

// 1. Jerarquía: si no hay sesión iniciada, no se puede guardar un favorito
if (empty($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Necesitás iniciar sesión para guardar favoritos."]);
    exit;
}

$rolSesion = $_SESSION['rol'] ?? 'consumidor';

// 2. Jerarquía: solo los consumidores pueden guardar favoritos
if ($rolSesion !== 'consumidor') {
    echo json_encode(["status" => "error", "message" => "Solo los consumidores pueden guardar ferias en favoritos."]);
    exit;
}

if (!isset($data['id_feria'])) {
    echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios."]);
    exit;
}

try {
    // Compatibilidad: crear la tabla Favoritos si todavía no existe en la BD
    $pdo->exec("CREATE TABLE IF NOT EXISTS Favoritos (
        id_favorito INT AUTO_INCREMENT PRIMARY KEY,
        id_consumidor INT NOT NULL,
        id_feria INT NOT NULL,
        fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $id_consumidor = (int) $_SESSION['usuario_id'];
    $id_feria = (int) $data['id_feria'];

    // Verificar si el favorito ya existe (toggle)
    $stmt = $pdo->prepare("SELECT id_favorito FROM Favoritos WHERE id_consumidor = ? AND id_feria = ?");
    $stmt->execute([$id_consumidor, $id_feria]);
    $existe = $stmt->fetch();

    if ($existe) {
        // Si existe, se elimina (quitar de favoritos)
        $stmtEliminar = $pdo->prepare("DELETE FROM Favoritos WHERE id_consumidor = ? AND id_feria = ?");
        $stmtEliminar->execute([$id_consumidor, $id_feria]);
        echo json_encode(["status" => "success", "is_favorito" => false]);
    } else {
        // Si no existe, se crea (agregar a favoritos)
        $stmtInsert = $pdo->prepare("INSERT INTO Favoritos (id_consumidor, id_feria) VALUES (?, ?)");
        $stmtInsert->execute([$id_consumidor, $id_feria]);
        echo json_encode(["status" => "success", "is_favorito" => true]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al guardar el favorito: " . $e->getMessage()]);
}
?>
