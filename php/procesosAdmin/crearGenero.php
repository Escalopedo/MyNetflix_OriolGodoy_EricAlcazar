<?php
include('../conexion.php'); // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
    // Validar y obtener el nombre del género
    $nombre = trim($_POST["nombre"]);

    // Validar que el nombre no esté vacío
    if (empty($nombre)) {
        echo json_encode(["status" => "error", "message" => "El nombre del género es obligatorio."]);
        exit;
    }

    // Validar la longitud del nombre
    if (strlen($nombre) < 3 || strlen($nombre) > 50) {
        echo json_encode(["status" => "error", "message" => "El nombre del género debe tener entre 3 y 50 caracteres."]);
        exit;
    }

    // Validar el formato del nombre (solo letras, espacios, guiones y caracteres especiales)
    if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ\-]+$/", $nombre)) {
        echo json_encode(["status" => "error", "message" => "El nombre solo puede contener letras, espacios, guiones y caracteres especiales (á, é, í, ó, ú, ñ)."]);
        exit;
    }

    // Verificar si el género ya existe en la base de datos
    $query = "SELECT id FROM generos WHERE nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$nombre]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "El género ya existe."]);
        exit;
    }

    // Insertar el género en la base de datos
    try {
        $query = "INSERT INTO generos (nombre) VALUES (?)";
        $stmt = $conexion->prepare($query);
        if ($stmt->execute([$nombre])) {
            echo json_encode(["status" => "success", "message" => "Género creado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al crear el género."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error al crear el género: " . $e->getMessage()]);
    }
} else {
    // Respuesta de error si no es una solicitud POST o no se envió el nombre
    echo json_encode(["status" => "error", "message" => "Método no permitido o datos incompletos."]);
}
?>