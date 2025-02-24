<?php
session_start();
include('../php/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>