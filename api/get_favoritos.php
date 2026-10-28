<?php
header('Content-Type: application/json');
require_once '../db.php';

// Iniciar sesión PHP para verificar el usuario autenticado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Necesitás iniciar sesión para ver tus favoritos."]);
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

    // Ferias favoritas del consumidor autenticado (con sus datos de fecha)
    $stmt = $pdo->prepare("SELECT f.id_feria, f.nombre_feria, f.direccion_texto, f.imagen_url, f.categoria, ff.fecha_inicio, ff.hora_apertura, ff.hora_cierre FROM Favoritos fav INNER JOIN Ferias f ON fav.id_feria = f.id_feria LEFT JOIN Fechas_Feria ff ON f.id_feria = ff.id_feria WHERE fav.id_consumidor = ?");
    $stmt->execute([(int) $_SESSION['usuario_id']]);
    $favoritos = $stmt->fetchAll();

    echo json_encode(["status" => "success", "data" => $favoritos]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al obtener los favoritos: " . $e->getMessage()]);
}
?>
