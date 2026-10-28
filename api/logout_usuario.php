<?php
header('Content-Type: application/json');
require_once '../db.php';

// Finalizar la sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();

echo json_encode(["status" => "success", "message" => "Sesión cerrada correctamente"]);
?>
