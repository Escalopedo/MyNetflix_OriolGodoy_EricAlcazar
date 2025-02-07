<?php 
session_start();
include('../php/conexion.php');

// Comprobar si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);

// Verificar si se pasa el ID de la película por la URL
if (isset($_GET['id'])) {
    $id_pelicula = $_GET['id'];

    // Obtener los detalles de la película
    $query = $conexion->prepare("SELECT * FROM carteleras WHERE id = :id_pelicula");
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
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/OjoNetflix.png" alt="Logo de la Plataforma">
        </div>
        <h1>Netflix</h1>
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

                <a href="index.php" class="back-button">Volver al catálogo</a>
            </div>
        </div>
    </section>
</body>
</html>
