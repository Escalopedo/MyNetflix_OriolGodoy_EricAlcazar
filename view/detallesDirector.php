<?php 
session_start();
include('../php/conexion.php');

// Comprobar si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);

// Verificar si se pasa el ID del director por la URL
if (isset($_GET['id'])) {
    $id_director = $_GET['id'];

    // Obtener los detalles del director
    $queryDirector = $conexion->prepare("SELECT * FROM directores WHERE id = :id_director");
    $queryDirector->bindParam(':id_director', $id_director, PDO::PARAM_INT);
    $queryDirector->execute();
    $director = $queryDirector->fetch(PDO::FETCH_ASSOC);

    // Si no se encuentra el director, redirigir al inicio
    if (!$director) {
        header("Location: index.php");
        exit();
    }

    // Obtener las películas asociadas a ese director
    $queryPeliculas = $conexion->prepare("SELECT c.titulo, c.id FROM carteleras c WHERE c.id_director = :id_director");
    $queryPeliculas->bindParam(':id_director', $id_director, PDO::PARAM_INT);
    $queryPeliculas->execute();
    $peliculas = $queryPeliculas->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Detalles del Director</title>
    <link rel="stylesheet" href="../css/detalles.css">
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

    <section id="detalle-director">
        <div class="detalle-container">
            <div class="detalle-imagen">
                <img src="../img/<?php echo $director['img']; ?>" alt="Imagen de <?php echo $director['nombre']; ?>">
            </div>
            <div class="detalle-info">
                <h2><?php echo $director['nombre']; ?></h2>
                <p><strong>Año de Nacimiento:</strong> <?php echo $director['fecha_nacimiento']; ?></p>
                <p><strong>Descripción:</strong> <?php echo $director['descripcion']; ?></p>

                <h3>Películas dirigidas:</h3>
                <ul>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <li><a href="detalles.php?id=<?php echo $pelicula['id']; ?>"><?php echo $pelicula['titulo']; ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <a href="index.php" class="back-button">Volver al catálogo</a>
            </div>
        </div>
    </section>
</body>
</html>
