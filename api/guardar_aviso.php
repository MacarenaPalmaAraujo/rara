<?php
// api/guardar_aviso.php
// Publica el aviso de un emprendedor con lugar, hora, puesto, Instagram y foto.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = $_POST;
if (empty($data)) {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
}

$idEmprendedor = (int) ($_SESSION['usuario_id'] ?? $data['id_emprendedor'] ?? 0);

if ($idEmprendedor <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para publicar tu mensaje.']);
    exit;
}

$mensaje = trim($data['mensaje'] ?? '');
$idFeria = isset($data['id_feria']) && $data['id_feria'] !== '' ? (int) $data['id_feria'] : null;
$lugar = trim($data['lugar'] ?? '');
$hora = trim($data['hora'] ?? '');
$puesto = trim($data['puesto'] ?? '');
$instagram = trim($data['instagram'] ?? '');

if ($mensaje === '') {
    echo json_encode(['status' => 'error', 'message' => 'El mensaje es obligatorio.']);
    exit;
}
if (!$idFeria) {
    echo json_encode(['status' => 'error', 'message' => 'Seleccioná la feria en la que participás.']);
    exit;
}

/**
 * Agrega una columna si la tabla ya existía de una versión anterior.
 */
function feriando_ensure_column(PDO $pdo, string $tabla, string $columna, string $definicion): void
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
    );
    $stmt->execute([$tabla, $columna]);
    if ((int) $stmt->fetchColumn() === 0) {
        $pdo->exec("ALTER TABLE `$tabla` ADD COLUMN `$columna` $definicion");
    }
}

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

    feriando_ensure_column($pdo, 'Avisos', 'lugar', 'VARCHAR(150) NULL');
    feriando_ensure_column($pdo, 'Avisos', 'hora', 'VARCHAR(30) NULL');
    feriando_ensure_column($pdo, 'Avisos', 'puesto', 'VARCHAR(50) NULL');
    feriando_ensure_column($pdo, 'Avisos', 'instagram', 'VARCHAR(100) NULL');
    feriando_ensure_column($pdo, 'Avisos', 'imagen_url', 'VARCHAR(255) NULL');

    // Verificamos el rol real del usuario.
    $stmt = $pdo->prepare(
        'SELECT u.id_usuario, LOWER(r.nombre) AS rol
         FROM Usuarios u
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE u.id_usuario = ?'
    );
    $stmt->execute([$idEmprendedor]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'La cuenta del emprendedor no existe.']);
        exit;
    }
    if (($usuario['rol'] ?? '') !== 'emprendedor') {
        echo json_encode(['status' => 'error', 'message' => 'Solo los emprendedores pueden publicar este mensaje.']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id_feria FROM Ferias WHERE id_feria = ?');
    $stmt->execute([$idFeria]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La feria seleccionada ya no existe.']);
        exit;
    }

    $imagenUrl = null;
    if (!empty($_FILES['foto']['tmp_name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
        $tiposPermitidos = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
        finfo_close($finfo);

        $tamanoMaximo = 5 * 1024 * 1024; // 5 MB

        if (!isset($tiposPermitidos[$mime])) {
            echo json_encode(['status' => 'error', 'message' => 'La foto debe ser JPG, PNG, WEBP o GIF.']);
            exit;
        }

        if ((int) $_FILES['foto']['size'] > $tamanoMaximo) {
            echo json_encode(['status' => 'error', 'message' => 'La foto no puede superar los 5 MB.']);
            exit;
        }

        $directorio = __DIR__ . '/../uploads/avisos';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = 'aviso_' . $idEmprendedor . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $tiposPermitidos[$mime];

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $directorio . '/' . $nombreArchivo)) {
            $imagenUrl = 'uploads/avisos/' . $nombreArchivo;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar la foto.']);
            exit;
        }
    }

    $stmt = $pdo->prepare(
        "INSERT INTO Avisos (id_emprendedor, id_feria, mensaje, lugar, hora, puesto, instagram, imagen_url, estado)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')"
    );
    $stmt->execute([
        $idEmprendedor,
        $idFeria,
        $mensaje,
        $lugar !== '' ? $lugar : null,
        $hora !== '' ? $hora : null,
        $puesto !== '' ? $puesto : null,
        $instagram !== '' ? $instagram : null,
        $imagenUrl
    ]);

    echo json_encode([
        'status' => 'success',
        'id_aviso' => (int) $pdo->lastInsertId(),
        'imagen_url' => $imagenUrl,
        'message' => 'Mensaje publicado con éxito'
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al publicar el mensaje: ' . $e->getMessage()]);
}
?>
