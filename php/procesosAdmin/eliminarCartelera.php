<?php
include('../conexion.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Obtener la imagen para eliminarla del servidor
    $query = "SELECT img FROM carteleras WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$id]);
    $cartelera = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cartelera && !empty($cartelera['img'])) {
        $imagePath = "../../img/" . $cartelera['img'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Eliminar imagen del servidor
        }
    }

    // Eliminar las relaciones en la tabla cartelera_generos
    $query = "DELETE FROM cartelera_generos WHERE id_cartelera = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$id]);

    // Eliminar las relaciones en la tabla likes
    $query = "DELETE FROM likes WHERE id_carteleras = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$id]);

    // Eliminar la cartelera de la base de datos
    $query = "DELETE FROM carteleras WHERE id = ?";
    $stmt = $conexion->prepare($query);
    if ($stmt->execute([$id])) {
        echo "Cartelera eliminada";
    } else {
        echo "Error al eliminar";
    }
}
?>