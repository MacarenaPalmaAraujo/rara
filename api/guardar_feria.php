<?php
// api/guardar_feria.php (Ejemplo de estructura esperada)
header('Content-Type: application/json');
// ... tu conexión a PDO y lógica de inserción ...

if ($insercionExitosa) {
    echo json_encode([
        "status" => "success", 
        "message" => "Feria registrada correctamente",
        "id_feria" => $pdo->lastInsertId()
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo registrar"]);
}
?>