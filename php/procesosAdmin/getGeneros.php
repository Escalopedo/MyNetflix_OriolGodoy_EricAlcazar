<?php
include('../conexion.php');

$query = "SELECT id, nombre FROM generos";
$result = $conexion->query($query);
$generos = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($generos);
?>
