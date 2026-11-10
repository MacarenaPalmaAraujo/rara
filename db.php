<?php
// db.php - Conexión a la base de datos MySQL / MariaDB
$host = 'localhost';
$db   = 'feriando';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si la base todavía no existe, la creamos automáticamente.
    // Así el proyecto funciona aunque se importe primero el SQL o no.
    try {
        $pdoSinBase = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
        $pdoSinBase->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e2) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudo conectar con la base de datos. Revisá que MySQL esté encendido y que el usuario/clave de db.php sean correctos. Detalle: ' . $e2->getMessage()
        ]);
        exit;
    }
}
?>
