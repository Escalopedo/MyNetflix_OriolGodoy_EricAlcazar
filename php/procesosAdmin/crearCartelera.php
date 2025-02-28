<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_director = $_POST["director"];
    $generos = isset($_POST["generos"]) ? $_POST["generos"] : [];
    $img = $_FILES["img"];

    if (!empty($img["name"])) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($img["name"]);
        move_uploaded_file($img["tmp_name"], $target_file);
    } else {
        $target_file = null;
    }

    $query = "INSERT INTO carteleras (titulo, descripcion, img, id_director) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$titulo, $descripcion, $target_file, $id_director]);

    $cartelera_id = $conexion->lastInsertId();

    foreach ($generos as $genero_id) {
        $query = "INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$cartelera_id, $genero_id]);
    }

    echo "Cartelera creada";
} else {
    echo "Error al crear la cartelera";
}
?>