<?php
session_start();
include('../php/conexion.php');

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Obtener usuarios según su estado
$query = "SELECT * FROM usuarios WHERE estado = 'activo'";
$usuariosActivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM usuarios WHERE estado = 'inactivo'";
$usuariosInactivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM usuarios WHERE estado = 'pendiente'";
$usuariosPendientes = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Géneros (para crear y gestionar)
$queryGeneros = "SELECT * FROM generos";
$generos = $conexion->query($queryGeneros)->fetchAll(PDO::FETCH_ASSOC);

// Carteleras (para crear y gestionar)
$queryCarteleras = "SELECT * FROM carteleras";
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
</head>
<body>

    <!-- Barra de navegación -->
    <nav>
        <ul>
            <li><a href="#" class="nav-link" data-seccion="usuarios">Usuarios</a></li>
            <li><a href="#" class="nav-link" data-seccion="generos">Géneros</a></li>
            <li><a href="#" class="nav-link" data-seccion="carteleras">Carteleras</a></li>
        </ul>
    </nav>

    <h1>Administración de Contenidos</h1>

    <!-- Sección de Usuarios -->
    <div id="usuarios" class="seccion">
        <h2>Usuarios Activos</h2>
        <table id="tablaActivos">
            <tr>
                <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuariosActivos as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class="gestionar-usuario btn-warning" data-id="<?= $usuario['id'] ?>" data-accion="desactivar">Desactivar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Usuarios Inactivos</h2>
        <table id="tablaInactivos">
            <tr>
                <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuariosInactivos as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class="gestionar-usuario btn-success" data-id="<?= $usuario['id'] ?>" data-accion="activar">Activar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Usuarios Pendientes</h2>
        <table id="tablaPendientes">
            <tr>
                <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuariosPendientes as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class="gestionar-usuario btn-primary" data-id="<?= $usuario['id'] ?>" data-accion="aprobar">Aprobar</button>
                        <button class="gestionar-usuario btn-danger" data-id="<?= $usuario['id'] ?>" data-accion="rechazar">Rechazar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Sección de Géneros -->
    <div id="generos" class="seccion" style="display:none;">
        <h2>Géneros</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Acciones</th>
            </tr>
            <?php foreach ($generos as $genero) : ?>
                <tr>
                    <td><?= $genero['id'] ?></td>
                    <td><?= $genero['nombre'] ?></td>
                    <td>
                        <button class="editar-genero btn-primary" data-id="<?= $genero['id'] ?>">Editar</button>
                        <button class="eliminar-genero btn-danger" data-id="<?= $genero['id'] ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button class="crear-genero btn-success">Crear Género</button>
    </div>

    <!-- Sección de Carteleras -->
    <div id="carteleras" class="seccion" style="display:none;">
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
                    <td>
                        <?php if (!empty($cartelera['img'])): ?>
                            <img src="../img/<?= $cartelera['img'] ?>" alt="Imagen de la cartelera" width="100" height="100">
                        <?php else: ?>
                            <span>No hay imagen</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="editar-cartelera btn-primary" data-id="<?= $cartelera['id'] ?>">Editar</button>
                        <button class="eliminar-cartelera btn-danger" data-id="<?= $cartelera['id'] ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button class="crear-cartelera btn-success">Crear Cartelera</button>
    </div>

    <script src="../js/ajaxUsuarios.js"></script>
    <script src="../js/adminAjax.js"></script>

</body>
</html>
