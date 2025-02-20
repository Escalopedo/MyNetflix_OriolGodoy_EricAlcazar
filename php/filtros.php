<?php
session_start();
include('conexion.php'); // Asegúrate de que la ruta es correcta

$loggedIn = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;

// Obtener datos del filtro enviados por AJAX
$searchTitle = $_POST['searchTitle'] ?? '';
$searchGenre = $_POST['searchGenre'] ?? '';
$likedOnly = isset($_POST['likedOnly']) ? $_POST['likedOnly'] : '';

$query = "SELECT DISTINCT carteleras.* FROM carteleras
          LEFT JOIN cartelera_generos ON carteleras.id = cartelera_generos.id_cartelera
          LEFT JOIN generos ON cartelera_generos.id_genero = generos.id
          LEFT JOIN likes l ON carteleras.id = l.id_carteleras
          WHERE 1";

// Aplicar filtros
if (!empty($searchTitle)) {
    $query .= " AND carteleras.titulo LIKE :searchTitle";
}
if (!empty($searchGenre)) {
    $query .= " AND generos.id = :searchGenre";
}
if ($loggedIn && $likedOnly) {
    $query .= " AND l.id_usuarios = :userId";
}

$query .= " ORDER BY carteleras.titulo";

// Preparar y ejecutar consulta
$statement = $conexion->prepare($query);

if (!empty($searchTitle)) {
    $statement->bindValue(':searchTitle', '%' . $searchTitle . '%');
}
if (!empty($searchGenre)) {
    $statement->bindValue(':searchGenre', $searchGenre);
}
if ($loggedIn && $likedOnly) {
    $statement->bindValue(':userId', $userId);
}

$statement->execute();
$peliculas = $statement->fetchAll(PDO::FETCH_ASSOC);

// Agregar ruta completa de imagen
foreach ($peliculas as &$pelicula) {
    $pelicula['img'] = "../img/" . $pelicula['img'];
}

// Enviar JSON
echo json_encode($peliculas);
