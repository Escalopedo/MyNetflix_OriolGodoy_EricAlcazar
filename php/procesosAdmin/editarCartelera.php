<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
require_once '../conexion.php';

// Verificar si se recibió una solicitud GET para obtener los datos de la cartelera
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $carteleraId = intval($_GET['id']);

        // Obtener los datos de la cartelera
        $query = "SELECT c.id, c.titulo, c.descripcion, c.img, c.id_director, 
                         GROUP_CONCAT(cg.id_genero) AS generos
                  FROM carteleras c
                  LEFT JOIN cartelera_generos cg ON c.id = cg.id_cartelera
                  WHERE c.id = ?
                  GROUP BY c.id";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$carteleraId]);
        $cartelera = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartelera) {
            $cartelera['generos'] = explode(',', $cartelera['generos']); // Convertir géneros en array
            echo json_encode(['status' => 'success', 'data' => $cartelera]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cartelera no encontrada']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de cartelera no proporcionado']);
    }
    exit;
}

// Verificar si se recibió una solicitud POST para actualizar la cartelera
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar los datos recibidos
    $carteleraId = intval($_POST['id']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $id_director = intval($_POST['director']);
    $generos = isset($_POST['generos']) ? $_POST['generos'] : [];
    $img = $_FILES['img'];

    // Validar campos obligatorios
    if (empty($titulo) || empty($descripcion) || empty($id_director)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Procesar la imagen (si se subió una nueva)
    $imgNombre = null;
    if ($img['error'] === UPLOAD_ERR_OK) {
        $imgNombre = uniqid() . '_' . basename($img['name']);
        $imgRuta = '../../img/' . $imgNombre;
        if (!move_uploaded_file($img['tmp_name'], $imgRuta)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen']);
            exit;
        }
    }

    // Actualizar la cartelera en la base de datos
    try {
        $conexion->beginTransaction();

        // Actualizar datos básicos de la cartelera
        $query = "UPDATE carteleras 
                  SET titulo = ?, descripcion = ?, id_director = ?" .
                 ($imgNombre ? ", img = ?" : "") .
                 " WHERE id = ?";
        $stmt = $conexion->prepare($query);
        if ($imgNombre) {
            $stmt->execute([$titulo, $descripcion, $id_director, $imgNombre, $carteleraId]);
        } else {
            $stmt->execute([$titulo, $descripcion, $id_director, $carteleraId]);
        }

        // Eliminar géneros anteriores de la cartelera
        $query = "DELETE FROM cartelera_generos WHERE id_cartelera = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$carteleraId]);

        // Insertar nuevos géneros
        if (!empty($generos)) {
            $query = "INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            foreach ($generos as $id_genero) {
                $stmt->execute([$carteleraId, $id_genero]);
            }
        }

        $conexion->commit();
        echo json_encode(['status' => 'success', 'message' => 'Cartelera actualizada correctamente']);
    } catch (Exception $e) {
        $conexion->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la cartelera: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);