<?php
// api/guardar_feria.php
// Guarda una feria publicada por el rol "feria" y su fecha en la base de datos.
header('Content-Type: application/json');
require_once '../db.php';

// Acepta datos enviados como JSON (fetch del frontend) o como formulario.
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

$idOrganizador = (int) ($data['id_organizador'] ?? 0);
$nombreFeria   = trim($data['nombre_feria'] ?? '');
$direccion     = trim($data['direccion_texto'] ?? '');
$categoria     = trim($data['categoria'] ?? '');
$fechaInicio   = trim($data['fecha_inicio'] ?? '');
$fechaFin      = trim($data['fecha_fin'] ?? $fechaInicio);
$horaApertura  = trim($data['hora_apertura'] ?? '');
$horaCierre    = trim($data['hora_cierre'] ?? '');
$descripcion   = trim($data['descripcion'] ?? '');
$instagram     = trim($data['instagram'] ?? '');

// Validaciones mínimas para que la feria pueda mostrarse en el calendario.
if ($idOrganizador <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Necesitás iniciar sesión para registrar una feria.']);
    exit;
}
if ($nombreFeria === '' || $direccion === '' || $fechaInicio === '') {
    echo json_encode(['status' => 'error', 'message' => 'Completá el nombre, la dirección y la fecha de la feria.']);
    exit;
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
    echo json_encode(['status' => 'error', 'message' => 'La fecha de la feria no es válida.']);
    exit;
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaFin = $fechaInicio;
}

// Normaliza las horas a formato HH:MM:SS.
if (preg_match('/^\d{2}:\d{2}$/', $horaApertura)) $horaApertura .= ':00';
if (preg_match('/^\d{2}:\d{2}$/', $horaCierre))   $horaCierre   .= ':00';
if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $horaApertura)) $horaApertura = '12:00:00';
if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $horaCierre))   $horaCierre   = '19:00:00';

try {
    // El organizador debe existir para respetar la clave foránea.
    $stmt = $pdo->prepare('SELECT id_usuario FROM Usuarios WHERE id_usuario = ?');
    $stmt->execute([$idOrganizador]);
    if (!$stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'El usuario organizador no existe.']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO Ferias (id_organizador, nombre_feria, descripcion, direccion_texto, categoria, estado, instagram)
         VALUES (?, ?, ?, ?, ?, 'activa', ?)"
    );
    $stmt->execute([
        $idOrganizador,
        $nombreFeria,
        $descripcion !== '' ? $descripcion : null,
        $direccion,
        $categoria !== '' ? $categoria : null,
        $instagram !== '' ? $instagram : null
    ]);

    $idFeria = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare(
        'INSERT INTO Fechas_Feria (id_feria, fecha_inicio, fecha_fin, hora_apertura, hora_cierre)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$idFeria, $fechaInicio, $fechaFin, $horaApertura, $horaCierre]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Feria registrada correctamente',
        'id_feria' => $idFeria
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la feria: ' . $e->getMessage()]);
}
?>
