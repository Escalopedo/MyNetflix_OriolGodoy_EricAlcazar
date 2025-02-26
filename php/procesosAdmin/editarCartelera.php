<?php
session_start();
include('../conexion.php');

// Verificar que el método sea GET o POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Este bloque es para obtener los datos de la cartelera
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $stmt = $conexion->prepare("SELECT * FROM carteleras WHERE id = ?");
            $stmt->execute([$id]);
            $cartelera = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartelera) {
                // Obtener los géneros asociados
                $stmtGeneros = $conexion->prepare("SELECT id_genero FROM cartelera_generos WHERE id_cartelera = ?");
                $stmtGeneros->execute([$id]);
                $generos = $stmtGeneros->fetchAll(PDO::FETCH_ASSOC);

                // Extraer solo los ids de los géneros
                $generosArray = array_map(function($genero) {
                    return $genero['id_genero'];
                }, $generos);

                // Responder con los datos en formato JSON
                echo json_encode([
                    'status' => 'success',
                    'id' => $cartelera['id'],
                    'titulo' => $cartelera['titulo'],
                    'descripcion' => $cartelera['descripcion'],
                    'id_director' => $cartelera['id_director'],
                    'generos' => $generosArray,
                    'img' => $cartelera['img']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cartelera no encontrada.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Este bloque es para actualizar la cartelera
    if (isset($_POST['id'], $_POST['titulo'], $_POST['descripcion'], $_POST['director'])) {
        $id = $_POST['id'];
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $id_director = $_POST['director'];
        $generos = $_POST['generos'] ?? [];

        try {
            $conexion->beginTransaction();

            // Manejo de la imagen
            if (!empty($_FILES['img']['name'])) {
                $imgNombre = time() . "_" . basename($_FILES['img']['name']);
                $rutaDestino = "../img/" . $imgNombre;

                // Validar la imagen
                $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
                $extension = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

                if (!in_array($extension, $extensionesPermitidas)) {
                    throw new Exception("Formato de imagen no permitido.");
                }

                if ($_FILES['img']['size'] > 2 * 1024 * 1024) { // 2MB
                    throw new Exception("La imagen es demasiado grande.");
                }

                if (move_uploaded_file($_FILES['img']['tmp_name'], $rutaDestino)) {
                    $stmt = $conexion->prepare("
                        UPDATE carteleras 
                        SET titulo = ?, descripcion = ?, id_director = ?, img = ? 
                        WHERE id = ?
                    ");
                    $stmt->execute([$titulo, $descripcion, $id_director, $imgNombre, $id]);
                } else {
                    throw new Exception("Error al subir la imagen.");
                }
            } else {
                $stmt = $conexion->prepare("
                    UPDATE carteleras 
                    SET titulo = ?, descripcion = ?, id_director = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$titulo, $descripcion, $id_director, $id]);
            }

            // Actualizar géneros
            $stmtDeleteGeneros = $conexion->prepare("DELETE FROM cartelera_generos WHERE id_cartelera = ?");
            $stmtDeleteGeneros->execute([$id]);

            $stmtInsertGeneros = $conexion->prepare("INSERT INTO cartelera_generos (id_cartelera, id_genero) VALUES (?, ?)");
            foreach ($generos as $genero) {
                $stmtInsertGeneros->execute([$id, $genero]);
            }

            $conexion->commit();
            echo json_encode(["status" => "success", "message" => "Cartelera actualizada correctamente."]);
        } catch (Exception $e) {
            $conexion->rollBack();
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos para la actualización."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
