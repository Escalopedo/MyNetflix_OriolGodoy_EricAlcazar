<?php
include('../conexion.php'); // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Saneamiento y validación de los datos del formulario
    $titulo = trim($_POST["titulo"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $id_director = intval($_POST["director"] ?? 0);
    $generos = isset($_POST["generos"]) ? $_POST["generos"] : [];
    $img = $_FILES["img"] ?? null;

    // Validar el título
    if (empty($titulo)) {
        echo json_encode(["status" => "error", "message" => "El título es obligatorio."]);
        exit;
    }
    if (strlen($titulo) < 5 || strlen($titulo) > 100) {
        echo json_encode(["status" => "error", "message" => "El título debe tener entre 5 y 100 caracteres."]);
        exit;
    }
    if (!preg_match("/^[a-zA-Z0-9\s.,\-]+$/", $titulo)) {
        echo json_encode(["status" => "error", "message" => "El título solo puede contener letras, números, espacios, comas, puntos y guiones."]);
        exit;
    }

    // Verificar si ya existe una película con el mismo título
    $query = "SELECT id FROM carteleras WHERE titulo = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$titulo]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Ya existe una película con ese título."]);
        exit;
    }
    // Validar la descripción
    if (empty($descripcion)) {
        echo json_encode(["status" => "error", "message" => "La descripción es obligatoria."]);
        exit;
    }
    if (strlen($descripcion) < 10 || strlen($descripcion) > 500) {
        echo json_encode(["status" => "error", "message" => "La descripción debe tener entre 10 y 500 caracteres."]);
        exit;
    }

    // Validar el director
    if ($id_director <= 0) {
        echo json_encode(["status" => "error", "message" => "Debes seleccionar un director válido."]);
        exit;
    }

    // Validar los géneros
    if (empty($generos)) {
        echo json_encode(["status" => "error", "message" => "Debes seleccionar al menos un género."]);
        exit;
    }
    if (count($generos) > 3) {
        echo json_encode(["status" => "error", "message" => "No puedes seleccionar más de 3 géneros."]);
        exit;
    }

    // Validar la imagen (si se subió)
    $nombre_archivo = null;
    if (!empty($img["name"])) {
        // Verificar si el archivo es una imagen
        $imageFileType = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo json_encode(["status" => "error", "message" => "Solo se permiten archivos JPG, JPEG, PNG y GIF."]);
            exit;
        }

        // Verificar el tamaño de la imagen (no más de 5 MB)
        if ($img["size"] > 5 * 1024 * 1024) { // 5 MB
            echo json_encode(["status" => "error", "message" => "La imagen no puede superar los 5 MB."]);
            exit;
        }

        // Mover la imagen al servidor
        $target_dir = "../../img/"; // Asegúrate de que esta ruta sea correcta
        $nombre_archivo = uniqid() . "." . $imageFileType; // Nombre único para evitar colisiones
        $target_file = $target_dir . $nombre_archivo;

        if (!move_uploaded_file($img["tmp_name"], $target_file)) {
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen."]);
            exit;
        }
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
            $genero_id = intval($genero_id);
            if ($genero_id > 0) { // Validar que el ID del género sea válido
                $query = "INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES (?, ?)";
                $stmt = $conexion->prepare($query);
                $stmt->execute([$cartelera_id, $genero_id]);
            }
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
?>