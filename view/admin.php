<?php
session_start();
include('../php/conexion.php');

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener datos de usuarios, carteleras y géneros
$usuarios = $conexion->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$carteleras = $conexion->query("SELECT * FROM carteleras")->fetchAll(PDO::FETCH_ASSOC);
$generos = $conexion->query("SELECT * FROM generos")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

    <header>
        <a href="index.php" class="btn-home">🏠 Ir a Inicio</a>
        <a href="../php/logout.php" class="logout">Cerrar sesión</a>
    </header>

    <!-- Panel de Opciones -->
    <nav class="admin-menu">
        <button onclick="mostrarSeccion('usuarios')">Usuarios</button>
        <button onclick="mostrarSeccion('carteleras')">Carteleras</button>
        <button onclick="mostrarSeccion('generos')">Géneros</button>
    </nav>

    <h1>Panel de Administración</h1>

    <!-- Sección Usuarios -->
    <section id="usuarios" class="admin-section">
        <h2>Usuarios</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Rol</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellido'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td class="acciones">
                        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-edit">Editar</a>
                        <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-delete">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Sección Carteleras -->
    <section id="carteleras" class="admin-section" style="display: none;">
        <h2>Carteleras</h2>
        <table>
            <tr>
                <th>ID</th><th>Título</th><th>Descripción</th><th>Imagen</th><th>Acciones</th>
            </tr>
            <?php foreach ($carteleras as $cartelera) : ?>
                <tr>
                    <td><?= $cartelera['id'] ?></td>
                    <td><?= $cartelera['titulo'] ?></td>
                    <td><?= $cartelera['descripcion'] ?></td>
                    <td><img src="../img/<?= $cartelera['img'] ?>" class="img-cartelera"></td>
                    <td class="acciones">
                        <a href="editar_cartelera.php?id=<?= $cartelera['id'] ?>" class="btn btn-edit">Editar</a>
                        <a href="eliminar_cartelera.php?id=<?= $cartelera['id'] ?>" class="btn btn-delete">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Sección Géneros -->
    <section id="generos" class="admin-section" style="display: none;">
        <h2>Géneros</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Acciones</th>
            </tr>
            <?php foreach ($generos as $genero) : ?>
                <tr>
                    <td><?= $genero['id'] ?></td>
                    <td><?= $genero['nombre'] ?></td>
                    <td class="acciones">
                        <a href="editar_genero.php?id=<?= $genero['id'] ?>" class="btn btn-edit">Editar</a>
                        <a href="eliminar_genero.php?id=<?= $genero['id'] ?>" class="btn btn-delete">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <script>
        function mostrarSeccion(seccion) {
            // Ocultar todas las secciones
            document.querySelectorAll('.admin-section').forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            document.getElementById(seccion).style.display = 'block';
        }
    </script>

</body>
</html>
