<?php 
session_start();
include('../php/conexion.php');

$userRole = $_SESSION['rol'] ?? ''; 
$loggedIn = isset($_SESSION['user_id']);

// Obtener todos los géneros
$queryGeneros = $conexion->query("SELECT * FROM generos");
$generos = $queryGeneros->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las películas (sin filtros al principio)
$queryAllPeliculas = "SELECT * FROM carteleras ORDER BY titulo";
$resultPeliculas = $conexion->query($queryAllPeliculas);
$peliculas = $resultPeliculas->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/OjoNetflix.png" alt="Logo de la Plataforma">
        </div>
        <nav>
            <ul>
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
            <h2>TOP 5 PELÍCULAS</h2>
            <div class="slider-container">
                <div class="slider">
                <?php
                    $queryTop5 = "SELECT c.id, c.titulo, c.descripcion, c.img, d.nombre AS director, COUNT(l.id_carteleras) AS likes
                                FROM carteleras c
                                LEFT JOIN likes l ON c.id = l.id_carteleras
                                INNER JOIN directores d ON c.id_director = d.id
                                GROUP BY c.id
                                ORDER BY likes DESC
                                LIMIT 5";
                    $resultTop5 = $conexion->query($queryTop5);
                    while ($row = $resultTop5->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='slider-item'>";
                        echo "<a href='detalles.php?id={$row['id']}'><img src='../img/{$row['img']}' alt='{$row['titulo']}'></a>";
                        echo "<div class='info'>
                                <h3>{$row['titulo']}</h3>
                                <p>{$row['descripcion']}</p>
                                <span>Director: {$row['director']}</span>
                                <p class='likes'>Likes: {$row['likes']}</p> <!-- Aquí agregamos la cantidad de likes -->
                            </div>";
                        echo "</div>";
                    }
                ?>

            </div>
        </section>
    
    <!-- Catálogo de Películas -->
    <section id="catalogo">
        <h2>CATÁLOGO DE PELICULAS</h2>
                            <!-- Filtros de búsqueda -->
                    <section id="filtros">
                        <form id="filterForm">
                            <div class="filters">
                                <input type="text" id="searchTitle" name="searchTitle" placeholder="Buscar por título...">
                                <select id="searchGenre" name="searchGenre">
                                    <option value="">Seleccionar género</option>
                                    <?php foreach ($generos as $genero): ?>
                                        <option value="<?php echo $genero['id']; ?>"><?php echo $genero['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($loggedIn): ?>
                                    <label>
                                        <input type="checkbox" id="likedOnly" name="likedOnly"> Mostrar solo las que me gustan
                                    </label>
                                <?php endif; ?>
                            </div>
                        </form>
                    </section>
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
    <script src="../js/filtrosAjax.js"></script>
</body>
</html>
