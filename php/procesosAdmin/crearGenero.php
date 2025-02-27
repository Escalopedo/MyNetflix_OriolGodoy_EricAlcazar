<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
    $query = "INSERT INTO generos (nombre) VALUES (?)";
    $stmt = $conexion->prepare($query);
    if ($stmt->execute([$nombre])) {
        echo "Género creado";
    } else {
        echo "Error al crear el género";
    }
}
?>