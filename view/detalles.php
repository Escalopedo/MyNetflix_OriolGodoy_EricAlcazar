<?php 
session_start();
include('../php/conexion.php');

// Comprobar si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);

// Verificar si se pasa el ID de la película por la URL
if (isset($_GET['id'])) {
    $id_pelicula = $_GET['id'];

    // Obtener los detalles de la película
    $query = $conexion->prepare("SELECT c.*, d.nombre AS director
    FROM carteleras c
    LEFT JOIN directores d ON c.id_director = d.id
    WHERE c.id = :id_pelicula");
    $query->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $query->execute();
    $pelicula = $query->fetch(PDO::FETCH_ASSOC);

    
    // Si no se encuentra la película, redirigir al inicio
    if (!$pelicula) {
        header("Location: index.php");
        exit();
    }

    // Obtener el número de likes de la película
    $likesQuery = $conexion->prepare("SELECT COUNT(*) as num_likes FROM likes WHERE id_carteleras = :id_pelicula");
    $likesQuery->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $likesQuery->execute();
    $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
    $num_likes = $likesResult['num_likes']; // Número total de likes

    // Verificar si el usuario ya ha dado like a la película
    $liked = false;
    if ($loggedIn) {
        $checkLike = $conexion->prepare("SELECT * FROM likes WHERE id_carteleras = :id_carteleras AND id_usuarios = :id_usuarios");
        $checkLike->bindParam(':id_carteleras', $id_pelicula, PDO::PARAM_INT);
        $checkLike->bindParam(':id_usuarios', $_SESSION['user_id'], PDO::PARAM_INT);
        $checkLike->execute();
        if ($checkLike->rowCount() > 0) {
            $liked = true;
        }
    }

    // Obtener los géneros asociados a la película
    $queryGeneros = $conexion->prepare("SELECT g.nombre FROM generos g
                                        JOIN cartelera_generos cg ON g.id = cg.id_genero
                                        WHERE cg.id_cartelera = :id_pelicula");
    $queryGeneros->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $queryGeneros->execute();
    $generos = $queryGeneros->fetchAll(PDO::FETCH_ASSOC);

    // Crear un array de los nombres de los géneros
    $generos_pelicula = array_map(function($genero) {
        return $genero['nombre'];
    }, $generos);
    $generos_pelicula_str = implode(", ", $generos_pelicula); // Unir los géneros en una cadena separada por comas
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Película</title>
    <link rel="stylesheet" href="../css/detalles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/likes.js" defer></script> <!-- Enlazar el archivo de AJAX -->
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
                <?php if ($loggedIn): ?>
                    <li><a href="../php/logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section id="detalle-pelicula">
        <div class="detalle-container">
            <div class="detalle-imagen">
                <img src="../img/<?php echo $pelicula['img']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
            </div>
            <div class="detalle-info">
                <h2><?php echo $pelicula['titulo']; ?></h2>
                <p><strong>Descripción:</strong> <?php echo $pelicula['descripcion']; ?></p>

                <!-- Mostrar género de la película -->
                <p><strong>Género:</strong> <?php echo $generos_pelicula_str; ?></p>

                <!-- Mostrar número de likes -->
                <p><strong>Likes:</strong> <span id="num-likes"><?php echo $num_likes; ?></span></p>

                <?php if ($loggedIn): ?>
                    <!-- Botón de Like -->
                    <button id="like-button" data-pelicula-id="<?php echo $pelicula['id']; ?>" class="like-button">
                        <span id="like-text"><?php echo $liked ? 'Quitar Like' : 'Dar Like'; ?></span>
                    </button>
                <?php else: ?>
                    <!-- Si no está logueado, no puede dar Like -->
                    <p>Inicia sesión para dar Like.</p>
                <?php endif; ?>

                <p><strong>Director:</strong> 
                    <a href="detallesDirector.php?id=<?php echo $pelicula['id_director']; ?>">
                        <?php echo $pelicula['director']; ?>
                    </a>
                </p>

                <a href="index.php" class="back-button">Volver al catálogo</a>
            </div>
        </div>
    </section>
</body>
</html>
