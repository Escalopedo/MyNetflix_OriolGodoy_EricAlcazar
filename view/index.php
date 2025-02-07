<?php 
session_start();
include('../php/conexion.php');

$userRole = $_SESSION['rol'] ?? ''; 

// Comprobar si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);

// Obtener todos los géneros
$queryGeneros = $conexion->query("SELECT * FROM generos");
$generos = $queryGeneros->fetchAll(PDO::FETCH_ASSOC);

// Variables de búsqueda
$searchTitle = isset($_POST['searchTitle']) ? $_POST['searchTitle'] : '';
$searchGenre = isset($_POST['searchGenre']) ? $_POST['searchGenre'] : '';
$likedOnly = isset($_POST['likedOnly']) ? $_POST['likedOnly'] : '';

// Construir la consulta SQL con base en los filtros
$query = "SELECT DISTINCT carteleras.* FROM carteleras
          LEFT JOIN cartelera_generos ON carteleras.id = cartelera_generos.id_cartelera
          LEFT JOIN generos ON cartelera_generos.id_genero = generos.id
          LEFT JOIN likes l ON carteleras.id = l.id_carteleras
          WHERE 1";

// Filtros por título
if (!empty($searchTitle)) {
    $query .= " AND carteleras.titulo LIKE :searchTitle";
}

// Filtro por género
if (!empty($searchGenre)) {
    $query .= " AND generos.id = :searchGenre";
}

// Filtro por likes (si el usuario está logueado)
if ($loggedIn && $likedOnly) {
    $query .= " AND l.id_usuarios = :userId";
}

$query .= " ORDER BY carteleras.titulo"; // Ordenar las películas por título

// Ejecutar la consulta
$statement = $conexion->prepare($query);
if (!empty($searchTitle)) {
    $statement->bindValue(':searchTitle', '%' . $searchTitle . '%');
}
if (!empty($searchGenre)) {
    $statement->bindValue(':searchGenre', $searchGenre);
}
if ($loggedIn && $likedOnly) {
    $statement->bindValue(':userId', $_SESSION['user_id']);
}
$statement->execute();
$peliculas = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Streaming</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/OjoNetflix.png" alt="Logo de la Plataforma">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <?php if ($userRole === 'admin'): ?>
                        <li><a href="admin.php">Admin</a></li>
                    <?php endif; ?>
                <?php if ($loggedIn): ?>
                    <li><a href="../php/logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <!-- Top 5 Películas -->
    <section id="top5">
        <h2>Top 5 Películas</h2>
        <div class="slider-container">
            <div class="slider">
                <?php
                    // Mostrar las 5 películas más populares (ordenadas por likes)
                    $queryTop5 = "SELECT c.id, c.titulo, c.img, COUNT(l.id_carteleras) AS likes
                                  FROM carteleras c
                                  LEFT JOIN likes l ON c.id = l.id_carteleras
                                  GROUP BY c.id
                                  ORDER BY likes DESC
                                  LIMIT 5";
                    $resultTop5 = $conexion->query($queryTop5);
                    $i = 0;
                    while($row = $resultTop5->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='slider-item' data-index='{$i}'>";
                        // Envolvemos la imagen en un enlace que lleva a la página de detalles
                        echo "<a href='detalles.php?id={$row['id']}'>
                                <img src='../img/{$row['img']}' alt='{$row['titulo']}'>
                              </a>";
                        echo "</div>";
                        $i++;
                    }
                ?>
            </div>
        </div>
    </section>

    <!-- Filtros de búsqueda -->
    <section id="filtros">
        <form method="POST" action="index.php">
            <div class="filters">
                <input type="text" name="searchTitle" placeholder="Buscar por título..." value="<?php echo htmlspecialchars($searchTitle); ?>">
                <select name="searchGenre">
                    <option value="">Seleccionar género</option>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo $genero['id']; ?>" <?php echo ($searchGenre == $genero['id']) ? 'selected' : ''; ?>>
                            <?php echo $genero['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($loggedIn): ?>
                    <label>
                        <input type="checkbox" name="likedOnly" <?php echo ($likedOnly) ? 'checked' : ''; ?>> Mostrar solo las que me gustan
                    </label>
                <?php endif; ?>
                <button type="submit">Buscar</button>
            </div>
        </form>
    </section>
    
    <!-- Catálogo de Películas -->
    <section id="catalogo">
        <h2>Catálogo de Películas</h2>
        <div class="grid-peliculas">
            <?php foreach ($peliculas as $pelicula): ?>
                <div class="pelicula">
                    <a href="detalles.php?id=<?php echo $pelicula['id']; ?>">
                        <img src="../img/<?php echo $pelicula['img']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                        <p class="titulo"><?php echo $pelicula['titulo']; ?></p>
                    </a>
                    <?php if ($loggedIn): ?>
                        <a href='like_pelicula.php?id=<?php echo $pelicula['id']; ?>' class="like-button">Like</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="../js/slider.js"></script>
</body>
</html>
