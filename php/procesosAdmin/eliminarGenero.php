<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../conexion.php'); // Ajusta la ruta según la ubicación de conexion.php

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
    // Eliminar relaciones en cartelera_generos
    $query = "DELETE FROM cartelera_generos WHERE id_genero = :id";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Eliminar el género de la tabla generos
    $query = "DELETE FROM generos WHERE id = :id";
    $stmt = $conexion->prepare($query);
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