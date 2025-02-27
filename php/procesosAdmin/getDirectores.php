<?php
include('../conexion.php');

$query = "SELECT id, nombre FROM directores";
$result = $conexion->query($query);
$directores = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($directores);
?>
