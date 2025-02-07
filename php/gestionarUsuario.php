<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Acceso no autorizado"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];

    if (!is_numeric($id)) {
        echo json_encode(["status" => "error", "message" => "ID inválido"]);
        exit();
    }

    switch ($accion) {
        case 'activar':
            $query = "UPDATE usuarios SET estado = 'activo' WHERE id = ?";
            break;
        case 'desactivar':
            $query = "UPDATE usuarios SET estado = 'inactivo' WHERE id = ?";
            break;
        case 'aprobar':
            $query = "UPDATE usuarios SET estado = 'activo' WHERE id = ?";
            break;
        case 'rechazar':
            $query = "DELETE FROM usuarios WHERE id = ?";
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Acción inválida"]);
            exit();
    }

    $stmt = $conexion->prepare($query);
    if ($stmt->execute([$id])) {
        echo json_encode(["status" => "success", "message" => "Acción realizada con éxito"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al procesar la solicitud"]);
    }
}
?>
