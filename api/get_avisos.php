<?php
// api/get_avisos.php
// Devuelve los mensajes/avisos de los emprendedores.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Avisos (
        id_aviso INT AUTO_INCREMENT PRIMARY KEY,
        id_emprendedor INT NOT NULL,
        id_feria INT NULL,
        mensaje VARCHAR(255) NOT NULL,
        lugar VARCHAR(150) NULL,
        hora VARCHAR(30) NULL,
        puesto VARCHAR(50) NULL,
        instagram VARCHAR(100) NULL,
        imagen_url VARCHAR(255) NULL,
        estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
        fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $columnasNecesarias = [
        'lugar' => 'VARCHAR(150) NULL',
        'hora' => 'VARCHAR(30) NULL',
        'puesto' => 'VARCHAR(50) NULL',
        'instagram' => 'VARCHAR(100) NULL',
        'imagen_url' => 'VARCHAR(255) NULL'
    ];
    foreach ($columnasNecesarias as $columna => $definicion) {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
        );
        $stmt->execute(['Avisos', $columna]);
        if ((int) $stmt->fetchColumn() === 0) {
            $pdo->exec("ALTER TABLE Avisos ADD COLUMN `$columna` $definicion");
        }
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

    $sql = "SELECT a.id_aviso, a.id_emprendedor, a.id_feria, a.mensaje,
                   a.lugar, a.hora, a.puesto, a.instagram, a.imagen_url,
                   a.fecha_creacion, u.nombre AS emprendedor, f.nombre_feria
            FROM Avisos a
            INNER JOIN Usuarios u ON a.id_emprendedor = u.id_usuario
            LEFT JOIN Ferias f ON a.id_feria = f.id_feria";

    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY a.fecha_creacion DESC, a.id_aviso DESC LIMIT 50';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $avisos = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $avisos]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener los mensajes: ' . $e->getMessage()]);
}
?>
