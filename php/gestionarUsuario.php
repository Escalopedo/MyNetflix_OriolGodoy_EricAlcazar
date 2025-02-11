<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $accion = $_POST['accion'];

    if (!is_numeric($id)) {
        echo "ID inválido";
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
            echo "Acción inválida";
            exit();
    }

    $stmt = $conexion->prepare($query);
    if ($stmt->execute([$id])) {
        echo "Acción realizada con éxito";
    } else {
        echo "Error al procesar la solicitud";
    }
}
?>
