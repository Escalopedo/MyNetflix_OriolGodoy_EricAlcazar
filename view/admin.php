<?php
session_start();
include('../php/conexion.php');

$userRole = $_SESSION['rol'] ?? ''; 
$loggedIn = isset($_SESSION['user_id']);

// Verificación de administrador
if (!$loggedIn || $_SESSION['rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit();
}

// Obtener usuarios por estado
$usuariosActivos = $conexion->query("SELECT * FROM usuarios WHERE estado = 'activo'")->fetchAll(PDO::FETCH_ASSOC);
$usuariosInactivos = $conexion->query("SELECT * FROM usuarios WHERE estado = 'inactivo'")->fetchAll(PDO::FETCH_ASSOC);
$usuariosPendientes = $conexion->query("SELECT * FROM usuarios WHERE estado = 'pendiente'")->fetchAll(PDO::FETCH_ASSOC);

// Obtener géneros
$generos = $conexion->query("SELECT * FROM generos")->fetchAll(PDO::FETCH_ASSOC);

// Obtener directores
$directores = $conexion->query("SELECT * FROM directores")->fetchAll(PDO::FETCH_ASSOC);

// Obtener carteleras con directores y géneros
$queryCarteleras = "
    SELECT c.*, d.nombre AS director, GROUP_CONCAT(g.nombre SEPARATOR ', ') AS generos
    FROM carteleras c
    LEFT JOIN directores d ON c.id_director = d.id
    LEFT JOIN cartelera_generos cg ON c.id = cg.id_cartelera
    LEFT JOIN generos g ON cg.id_genero = g.id
    GROUP BY c.id, d.nombre
";
$carteleras = $conexion->query($queryCarteleras)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:wght@300;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="../img/OjoNetflix.png" alt="Logo">
    </div>
    <nav>
        <ul>
            <?php if ($loggedIn): ?>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="../php/logout.php">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="link-tipo">
    <ul>
        <li><a href="#" class="nav-link" data-seccion="usuarios">Usuarios</a></li>
        <li><a href="#" class="nav-link" data-seccion="generos">Géneros</a></li>
        <li><a href="#" class="nav-link" data-seccion="carteleras">Carteleras</a></li>
    </ul>
</div>

<h1>Administración de Contenidos</h1>

<!-- Sección de Usuarios -->
<div id="usuarios" class="seccion">
    <h2>Usuarios Activos</h2>
    <table id="tablaActivos">
        <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr>
        <?php foreach ($usuariosActivos as $usuario): ?>
            <tr id="usuario_<?= $usuario['id'] ?>">
                <td><?= htmlspecialchars($usuario['id']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                <td><button class="btn-tabla btn-warning gestionar-usuario" data-id="<?= $usuario['id'] ?>" data-accion="desactivar">Desactivar</button></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- Sección de Géneros -->
<div id="generos" class="seccion" style="display:none;">
    <h2>Géneros</h2>
    <table>
        <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
        <?php foreach ($generos as $genero): ?>
            <tr>
                <td><?= htmlspecialchars($genero['id']) ?></td>
                <td><?= htmlspecialchars($genero['nombre']) ?></td>
                <td>
                    <button class="btn-tabla btn-primary editar-genero" data-id="<?= $genero['id'] ?>">Editar</button>
                    <button class="btn-tabla btn-danger eliminar-genero" data-id="<?= $genero['id'] ?>">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button class="btn-tabla btn-success crear-genero">Crear Género</button>
</div>

<!-- Sección de Carteleras -->
<div id="carteleras" class="seccion" style="display:none;">
    <h2>Carteleras</h2>
    <table>
        <tr><th>ID</th><th>Título</th><th>Descripción</th><th>Géneros</th><th>Director</th><th>Imagen</th><th>Acciones</th></tr>
        <?php foreach ($carteleras as $cartelera): ?>
            <tr>
                <td><?= htmlspecialchars($cartelera['id']) ?></td>
                <td><?= htmlspecialchars($cartelera['titulo']) ?></td>
                <td><?= htmlspecialchars($cartelera['descripcion']) ?></td>
                <td><?= htmlspecialchars($cartelera['generos']) ?></td>
                <td><?= htmlspecialchars($cartelera['director']) ?></td>   
                <td>
                    <?php if (!empty($cartelera['img'])): ?>
                        <img src="../img/<?= htmlspecialchars($cartelera['img']) ?>" alt="Imagen" width="100">
                    <?php else: ?>
                        <span>No hay imagen</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn-tabla btn-primary editar-cartelera" data-id="<?= $cartelera['id'] ?>">Editar</button>
                    <button class="btn-tabla btn-danger eliminar-cartelera" data-id="<?= $cartelera['id'] ?>">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button class="btn-tabla btn-success crear-cartelera">Crear Cartelera</button>
</div>
<!-- Modal para editar cartelera -->
<div id="modalEditarCartelera" class="modal">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2>Editar Cartelera</h2>
        <form id="formEditarCartelera" enctype="multipart/form-data">
            <input type="hidden" id="editCarteleraId">
            
            <label for="editTitulo">Título:</label>
            <input type="text" id="editTitulo" name="titulo" required>

            <label for="editDescripcion">Descripción:</label>
            <textarea id="editDescripcion" name="descripcion" required></textarea>

            <label for="editDirector">Director:</label>
            <select id="editDirector" name="director" required>
                <option value="">Selecciona un director</option>
                <?php foreach ($directores as $director): ?>
                    <option value="<?= $director['id'] ?>"> <?= $director['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="editGeneros">Géneros:</label>
            <select id="editGeneros" name="generos[]" multiple size="1" required>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?= $genero['id'] ?>"> <?= $genero['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="editImg">Imagen:</label>
            <input type="file" id="editImg" name="img">
            <img id="prevImg" src="" alt="Imagen actual" width="100">
            <button type="submit" class="btn-tabla btn-success">Guardar Cambios</button>
        </form>
    </div>
</div>
<script src="../js/ajaxUsuarios.js"></script>
<script src="../js/adminAjax.js"></script>
</body>
</html>
