<?php
session_start();

include('../conexion.php');

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo 'error: no autorizado';
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['nombre'])) {
    echo 'error: datos incompletos';
    exit();
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];

try {
    $query = "UPDATE generos SET nombre = :nombre WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: no se pudo ejecutar la consulta';
    }
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage();
}
?>