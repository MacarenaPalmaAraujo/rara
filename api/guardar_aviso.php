<?php
header('Content-Type: application/json');
require_once '../db.php';

// Iniciar sesión PHP para poder guardar el estado de autenticación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aceptar datos por FormData (multipart, con foto) o por JSON
$data = $_POST;
if (empty($data)) {
    $data = json_decode(file_get_contents("php://input"), true) ?: [];
}

// 1. Jerarquía: si no hay sesión iniciada, no se puede publicar un mensaje
if (empty($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Necesitás iniciar sesión para publicar tu mensaje."]);
    exit;
}

$rolSesion = $_SESSION['rol'] ?? 'consumidor';

// 2. Jerarquía: solo los emprendedores pueden publicar este mensaje
if ($rolSesion !== 'emprendedor') {
    echo json_encode(["status" => "error", "message" => "Solo los emprendedores pueden publicar este mensaje."]);
    exit;
}

$mensaje = trim($data['mensaje'] ?? '');
if ($mensaje === '') {
    echo json_encode(["status" => "error", "message" => "El mensaje es obligatorio."]);
    exit;
}

try {
    // Compatibilidad: crear la tabla Avisos si todavía no existe en la BD
    $pdo->exec("CREATE TABLE IF NOT EXISTS Avisos (
        id_aviso INT AUTO_INCREMENT PRIMARY KEY,
        id_emprendedor INT NOT NULL,
        id_feria INT NULL,
        mensaje VARCHAR(255) NOT NULL,
        imagen_url VARCHAR(255) NULL,
        estado VARCHAR(20) DEFAULT 'pendiente',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Compatibilidad: agregar la columna imagen_url si la tabla ya existía sin ella
    $columnaImagen = $pdo->query("SHOW COLUMNS FROM Avisos LIKE 'imagen_url'")->fetch();
    if (!$columnaImagen) {
        $pdo->exec("ALTER TABLE Avisos ADD COLUMN imagen_url VARCHAR(255) NULL AFTER mensaje");
    }

    // 3. Guardar la foto publicada por el emprendedor (si envió una)
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
            echo json_encode(["status" => "error", "message" => "La foto debe ser JPG, PNG, WEBP o GIF."]);
            exit;
        }

        if ((int) $_FILES['foto']['size'] > $tamanoMaximo) {
            echo json_encode(["status" => "error", "message" => "La foto no puede superar los 5 MB."]);
            exit;
        }

        $directorio = __DIR__ . '/../uploads/avisos';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = 'aviso_' . (int) $_SESSION['usuario_id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $tiposPermitidos[$mime];

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $directorio . '/' . $nombreArchivo)) {
            $imagenUrl = 'uploads/avisos/' . $nombreArchivo;
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo guardar la foto."]);
            exit;
        }
    }

    // 4. Insertar el mensaje (con foto opcional) en la tabla Avisos
    $stmt = $pdo->prepare("INSERT INTO Avisos (id_emprendedor, id_feria, mensaje, imagen_url, estado) VALUES (?, ?, ?, ?, 'pendiente')");
    $stmt->execute([
        (int) $_SESSION['usuario_id'],
        isset($data['id_feria']) && $data['id_feria'] ? (int) $data['id_feria'] : null,
        $mensaje,
        $imagenUrl
    ]);

    echo json_encode([
        "status" => "success",
        "id_aviso" => $pdo->lastInsertId(),
        "imagen_url" => $imagenUrl,
        "message" => "Mensaje publicado con éxito"
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al publicar el mensaje: " . $e->getMessage()]);
}
?>
