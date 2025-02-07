<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no logueado']);
    exit();
}

// Verificar si se recibe el ID de la película
if (isset($_POST['pelicula_id'])) {
    $pelicula_id = $_POST['pelicula_id'];
    $user_id = $_SESSION['user_id'];

    // Verificar si el usuario ya ha dado like a esta película
    $query = $conexion->prepare("SELECT * FROM likes WHERE id_carteleras = :pelicula_id AND id_usuarios = :user_id");
    $query->bindParam(':pelicula_id', $pelicula_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();

    // Si el usuario ya dio like, lo quitamos
    if ($query->rowCount() > 0) {
        $deleteQuery = $conexion->prepare("DELETE FROM likes WHERE id_carteleras = :pelicula_id AND id_usuarios = :user_id");
        $deleteQuery->bindParam(':pelicula_id', $pelicula_id, PDO::PARAM_INT);
        $deleteQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteQuery->execute();

        $likeText = 'Dar Like';
    } else {
        // Si no ha dado like, lo añadimos
        $insertQuery = $conexion->prepare("INSERT INTO likes (id_carteleras, id_usuarios) VALUES (:pelicula_id, :user_id)");
        $insertQuery->bindParam(':pelicula_id', $pelicula_id, PDO::PARAM_INT);
        $insertQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertQuery->execute();

        $likeText = 'Quitar Like';
    }

    // Obtener el número actualizado de likes
    $likesQuery = $conexion->prepare("SELECT COUNT(*) as num_likes FROM likes WHERE id_carteleras = :pelicula_id");
    $likesQuery->bindParam(':pelicula_id', $pelicula_id, PDO::PARAM_INT);
    $likesQuery->execute();
    $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
    $num_likes = $likesResult['num_likes']; // Número total de likes

    // Enviar la respuesta con el nuevo número de likes y el texto del like
    echo json_encode([
        'status' => 'success',
        'likeText' => $likeText,
        'numLikes' => $num_likes
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de película no recibido']);
}
?>
