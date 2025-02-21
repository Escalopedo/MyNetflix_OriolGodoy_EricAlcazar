<?php
include('../php/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'get') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $stmt = $conexion->prepare("SELECT * FROM carteleras WHERE id = ?");
            $stmt->execute([$id]);
            $cartelera = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($cartelera);
        }
    }

    if ($accion === 'editar') {
        if (isset($_POST['id'], $_POST['titulo'], $_POST['descripcion'])) {
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];

            // Verificar si se sube una nueva imagen
            if (!empty($_FILES['img']['name'])) {
                $imgNombre = time() . "_" . $_FILES['img']['name'];
                $rutaDestino = "../img/" . $imgNombre;

                if (move_uploaded_file($_FILES['img']['tmp_name'], $rutaDestino)) {
                    $stmt = $conexion->prepare("UPDATE carteleras SET titulo = ?, descripcion = ?, img = ? WHERE id = ?");
                    $resultado = $stmt->execute([$titulo, $descripcion, $imgNombre, $id]);
                } else {
                    echo "Error al subir la imagen.";
                    exit();
                }
            } else {
                $stmt = $conexion->prepare("UPDATE carteleras SET titulo = ?, descripcion = ? WHERE id = ?");
                $resultado = $stmt->execute([$titulo, $descripcion, $id]);
            }

            echo $resultado ? "Cartelera actualizada correctamente." : "Error al actualizar la cartelera.";
        }
    }
}
?>
