<?php
session_start();
include('../conexion.php');

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo 'error: no autorizado';
    exit();
}

if (!isset($_POST['id'])) {
    echo 'error: datos incompletos';
    exit();
}

$id = $_POST['id'];

try {
    // Eliminar el género de la base de datos
    $query = "DELETE FROM generos WHERE id = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $id);
    
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage();
}
?>