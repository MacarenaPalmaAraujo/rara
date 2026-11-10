<?php
// api/get_resenas.php
// Devuelve las reseñas de una feria junto con el nombre de quien la escribió.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

$idFeria = (int) ($_GET['id_feria'] ?? 0);

if ($idFeria <= 0) {
    echo json_encode(['status' => 'success', 'data' => []]);
    exit;
}

try {
    // Si la tabla todavía no existe, devolvemos una lista vacía.
    $tablaExiste = $pdo->query("SHOW TABLES LIKE 'resenas'")->fetchColumn();
    if (!$tablaExiste) {
        echo json_encode(['status' => 'success', 'data' => []]);
        exit;
    }

    $stmt = $pdo->prepare(
        "SELECT r.id_resena, r.id_consumidor, r.id_feria, r.calificacion,
                r.comentario, r.fecha_resena, COALESCE(u.nombre, 'Consumidor') AS autor
         FROM resenas r
         LEFT JOIN Usuarios u ON u.id_usuario = r.id_consumidor
         WHERE r.id_feria = ?
         ORDER BY r.fecha_resena DESC, r.id_resena DESC"
    );
    $stmt->execute([$idFeria]);

    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudieron cargar las reseñas: ' . $e->getMessage()]);
}
?>
