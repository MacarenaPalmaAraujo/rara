<?php
// api/guardar_feria.php
// Guarda una feria publicada por el rol "feria" y su fecha en la base de datos.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Acepta datos enviados como JSON (fetch del frontend) o como formulario.
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

$idOrganizador = (int) ($_SESSION['usuario_id'] ?? $data['id_organizador'] ?? 0);
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
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin) || $fechaFin < $fechaInicio) {
    $fechaFin = $fechaInicio;
}

// Normaliza las horas a formato HH:MM:SS.
if (preg_match('/^\d{2}:\d{2}$/', $horaApertura)) $horaApertura .= ':00';
if (preg_match('/^\d{2}:\d{2}$/', $horaCierre))   $horaCierre   .= ':00';
if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $horaApertura)) $horaApertura = '12:00:00';
if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $horaCierre))   $horaCierre   = '19:00:00';

if ($horaCierre <= $horaApertura) {
    echo json_encode(['status' => 'error', 'message' => 'La hora de cierre debe ser posterior a la hora de apertura.']);
    exit;
}

try {
    // El organizador debe existir y tener el rol "feria".
    $stmt = $pdo->prepare(
        'SELECT u.id_usuario, LOWER(r.nombre) AS rol
         FROM Usuarios u
         LEFT JOIN roles r ON r.id_rol = u.id_rol
         WHERE u.id_usuario = ?'
    );
    $stmt->execute([$idOrganizador]);
    $organizador = $stmt->fetch();

    if (!$organizador) {
        echo json_encode(['status' => 'error', 'message' => 'El usuario organizador no existe.']);
        exit;
    }
    if (($organizador['rol'] ?? '') !== 'feria') {
        echo json_encode(['status' => 'error', 'message' => 'Solo el rol Feria puede registrar ferias.']);
        exit;
    }

    // EVITAR FERIAS DUPLICADAS / SUPERPUESTAS:
    // misma feria (nombre + lugar) en la misma fecha y horario.
    $stmt = $pdo->prepare(
        "SELECT f.id_feria
         FROM Ferias f
         INNER JOIN Fechas_Feria ff ON ff.id_feria = f.id_feria
         WHERE f.estado = 'activa'
           AND LOWER(TRIM(f.nombre_feria)) = LOWER(TRIM(?))
           AND LOWER(TRIM(f.direccion_texto)) = LOWER(TRIM(?))
           AND ff.fecha_inicio = ?
           AND ff.hora_apertura = ?
         LIMIT 1"
    );
    $stmt->execute([$nombreFeria, $direccion, $fechaInicio, $horaApertura]);
    $feriaExistente = $stmt->fetch();

    if ($feriaExistente) {
        echo json_encode([
            'status' => 'error',
            'duplicada' => true,
            'id_feria' => (int) $feriaExistente['id_feria'],
            'message' => 'Ya existe una feria con ese nombre, lugar, fecha y horario. No se puede registrar dos veces.'
        ]);
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
