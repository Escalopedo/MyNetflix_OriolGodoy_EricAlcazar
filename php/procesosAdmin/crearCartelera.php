<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_director = $_POST["director"];
    $generos = isset($_POST["generos"]) ? $_POST["generos"] : [];
    $img = $_FILES["img"];

    // Ruta de la carpeta de imágenes
    $target_dir = "../../img/"; // Asegúrate de que esta ruta sea correcta
    $target_file = $target_dir . basename($img["name"]); // Ruta completa del archivo
    $nombre_archivo = basename($img["name"]); // Solo el nombre del archivo

    // Verificar si se subió una imagen
    if (!empty($img["name"])) {
        // Verificar si el archivo es una imagen
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo json_encode(["status" => "error", "message" => "Solo se permiten archivos JPG, JPEG, PNG y GIF."]);
            exit;
        }

        // Mover la imagen al servidor
        if (move_uploaded_file($img["tmp_name"], $target_file)) {
            // La imagen se subió correctamente
        } else {
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen."]);
            exit;
        }
    } else {
        $nombre_archivo = null; // No se subió ninguna imagen
    }

    // Insertar la cartelera en la base de datos
    try {
        $query = "INSERT INTO carteleras (titulo, descripcion, img, id_director) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$titulo, $descripcion, $nombre_archivo, $id_director]);

        // Obtener el ID de la cartelera recién creada
        $cartelera_id = $conexion->lastInsertId();

        // Insertar los géneros asociados a la cartelera
        foreach ($generos as $genero_id) {
            $query = "INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$cartelera_id, $genero_id]);
        }

        // Respuesta de éxito
        echo json_encode(["status" => "success", "message" => "Cartelera creada correctamente."]);
    } catch (Exception $e) {
        // Respuesta de error
        echo json_encode(["status" => "error", "message" => "Error al crear la cartelera: " . $e->getMessage()]);
    }
} else {
    // Respuesta de error si no es una solicitud POST
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}