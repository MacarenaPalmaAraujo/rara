<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Avisos (
        id_aviso INT AUTO_INCREMENT PRIMARY KEY,
        id_emprendedor INT NOT NULL,
        id_feria INT NULL,
        mensaje VARCHAR(255) NOT NULL,
        imagen_url VARCHAR(255) NULL,
        estado VARCHAR(20) DEFAULT 'pendiente',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $columnaImagen = $pdo->query("SHOW COLUMNS FROM Avisos LIKE 'imagen_url'")->fetch();
    if (!$columnaImagen) {
        $pdo->exec("ALTER TABLE Avisos ADD COLUMN imagen_url VARCHAR(255) NULL AFTER mensaje");
    }
    $where = [];
    $params = [];

    if (isset($_GET['id_feria']) && $_GET['id_feria'] !== '') {
        $where[] = 'a.id_feria = ?';
        $params[] = (int) $_GET['id_feria'];
    }

    if (isset($_GET['id_emprendedor']) && $_GET['id_emprendedor'] !== '') {
        $where[] = 'a.id_emprendedor = ?';
        $params[] = (int) $_GET['id_emprendedor'];
    }

    // Mensajes
    $sql = "SELECT a.id_aviso, a.id_emprendedor, a.id_feria, a.mensaje, a.imagen_url, a.fecha_creacion,
                   u.nombre AS emprendedor, f.nombre_feria
            FROM Avisos a
            INNER JOIN Usuarios u ON a.id_emprendedor = u.id_usuario
            LEFT JOIN Ferias f ON a.id_feria = f.id_feria";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $sql .= " ORDER BY a.fecha_creacion DESC LIMIT 50";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $avisos = $stmt->fetchAll();

    echo json_encode(["status" => "success", "data" => $avisos]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al obtener los mensajes: " . $e->getMessage()]);
}
?>
