<?php
// db.php - Conexión a la base de datos MySQL
$host = "localhost";
$db   = "feriando"; // O "feriando", según como llamaste a tu BD en phpMyAdmin
$user = "root";
$pass = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     header('Content-Type: application/json');
     echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()]);
     exit;
}
?>