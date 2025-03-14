<?php
session_start();
include('../php/conexion.php');

$userRole = $_SESSION['rol'] ?? ''; 

// Comprobar si el usuario está logueado
$loggedIn = isset($_SESSION['user_id']);

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../view/index.php');
    exit();
}

// Obtener usuarios según su estado
$query = "SELECT * FROM usuarios WHERE estado = 'activo'";
$usuariosActivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM usuarios WHERE estado = 'inactivo'";
$usuariosInactivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM usuarios WHERE estado = 'pendiente'";
$usuariosPendientes = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Obtener los directores de la base de datos
$queryDirectores = "SELECT * FROM directores";
$directores = $conexion->query($queryDirectores)->fetchAll(PDO::FETCH_ASSOC);

// Géneros (para crear y gestionar)
$queryGeneros = "SELECT * FROM generos";
$generos = $conexion->query($queryGeneros)->fetchAll(PDO::FETCH_ASSOC);

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<body>

    <!-- Barra de navegación -->
    <header>
        <div class="logo">
            <img src="../img/OjoNetflix.png" alt="Logo de la Plataforma">
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
            <li><a href="#" class="nav-link" data-seccion="usuarios">USUARIOS</a></li>
            <li><a href="#" class="nav-link" data-seccion="generos">GÉNEROS</a></li>
            <li><a href="#" class="nav-link" data-seccion="carteleras">CARTELERAS</a></li>
        </ul>
                </div>

    <h1>Administración de Contenidos</h1>

    <!-- Sección de Usuarios -->
    <div id="usuarios" class="seccion">
        <h2>Usuarios Activos</h2>
        <table id="tablaActivos">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuariosActivos as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellido'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['telefono'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class=" btn-tabla gestionar-usuario btn-warning" data-id="<?= $usuario['id'] ?>" data-accion="desactivar">Desactivar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Usuarios Inactivos</h2>
        <table id="tablaInactivos">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuariosInactivos as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellido'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['telefono'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class="btn-tabla gestionar-usuario btn-success" data-id="<?= $usuario['id'] ?>" data-accion="activar">Activar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Usuarios Pendientes</h2>
        <table id="tablaPendientes">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuariosPendientes as $usuario) : ?>
                <tr id="usuario_<?= $usuario['id'] ?>">
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellido'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['telefono'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <button class="btn-tabla gestionar-usuario btn-primary" data-id="<?= $usuario['id'] ?>" data-accion="aprobar">Aprobar</button>
                        <button class="btn-tabla gestionar-usuario btn-danger" data-id="<?= $usuario['id'] ?>" data-accion="rechazar">Rechazar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Sección de Géneros -->
    <div id="generos" class="seccion" style="display:none;">
        <h2>Géneros</h2>
        <button class="btn-tabla crear-genero btn-success">Crear Género</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($generos as $genero) : ?>
                <tr>
                    <td><?= $genero['id'] ?></td>
                    <td><?= $genero['nombre'] ?></td>
                    <td>
                        <button class="btn-tabla editar-genero btn-primary" data-id="<?= $genero['id'] ?>">Editar</button>
                        <button class="btn-tabla eliminar-genero btn-danger" data-id="<?= $genero['id'] ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<!-- Modal de Edición de Género -->
<div id="modalEditarGenero" class="modal">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2>Editar Género</h2>
        <form id="formEditarGenero">
            <input type="hidden" id="editGeneroId">
            <label for="editNombre">Nombre:</label>
            <input type="text" id="editNombre" name="nombre" required>
            <button type="submit" class="btn-tabla btn-success">Guardar Cambios</button>
        </form>
    </div>

</div>
    <!-- Modal para crear género -->
    <div id="modalCrearGenero" class="modal">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2>Crear Género</h2>
        <form id="formCrearGenero">
            <label for="crearNombreGenero">Nombre:</label>
            <input type="text" id="crearNombreGenero" name="nombre" required>
            <button type="submit" class="btn-tabla btn-success crear-genero">Crear Género</button>
        </form>
    </div>
</div>
    <!-- Sección de Carteleras -->
    <div id="carteleras" class="seccion" style="display:none;">
        <h2>Carteleras</h2>
        <button class="btn-tabla crear-cartelera btn-success">Crear Cartelera</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Géneros</th>
                <th>Director</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($carteleras as $cartelera) : ?>
                <tr>
                    <td><?= $cartelera['id'] ?></td>
                    <td><?= $cartelera['titulo'] ?></td>
                    <td><?= $cartelera['descripcion'] ?></td>
                    <td><?= $cartelera['generos'] ?></td>
                    <td><?= $cartelera['director'] ?></td>   
                    <td>
                        <?php if (!empty($cartelera['img'])): ?>
                            <img src="../img/<?= $cartelera['img'] ?>" alt="Imagen de la cartelera" width="100" height="100">
                        <?php else: ?>
                            <span>No hay imagen</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn-tabla editar-cartelera btn-primary" data-id="<?= $cartelera['id'] ?>">Editar</button>
                        <button class="btn-tabla eliminar-cartelera btn-danger" data-id="<?= $cartelera['id'] ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- Modal de Edición de Cartelera -->
        <div id="modalEditarCartelera" class="modal">
            <div class="modal-contenido">
                <span class="cerrar">&times;</span>
                <h2>Editar Cartelera</h2>
                <form id="formEditarCartelera" method="POST" action="procesosAdmin/editarCartelera.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editCarteleraId" value="<?= $cartelera['id'] ?>">
                
                    <!-- Título -->
                    <label for="editTitulo">Título:</label>
                    <input type="text" id="editTitulo" name="titulo" value="<?= htmlspecialchars($cartelera['titulo']) ?>" required>

                    <!-- Descripción -->
                    <label for="editDescripcion">Descripción:</label>
                    <textarea id="editDescripcion" name="descripcion" required><?= htmlspecialchars($cartelera['descripcion']) ?></textarea>

                    <!-- Director -->
                    <label for="editDirector">Director:</label>
                    <select id="editDirector" name="director" required>
                        <option value="">Selecciona un director</option>
                        <?php foreach ($directores as $director): ?>
                            <option value="<?= $director['id'] ?>" <?= $cartelera['id_director'] == $director['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($director['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Géneros -->
                    <label for="editGeneros">Géneros:</label>
                        <select id="editGeneros" name="generos[]" multiple size="5" required>
                            <!-- Las opciones se llenan dinámicamente con los géneros -->
                        </select>


                    <!-- Imagen -->
                    <label for="editImg">Imagen:</label>
                    <input type="file" id="editImg" name="img">
                    <?php if (!empty($cartelera['img'])): ?>
                        <img id="prevImg" src="../img/<?= htmlspecialchars($cartelera['img']) ?>" alt="Imagen previa" style="max-width: 100px;">
                    <?php else: ?>
                        <span>No hay imagen</span>
                    <?php endif; ?>

                    <button type="submit" class="btn-tabla btn-success">Guardar cambios</button>
                </form>

            </div>
        </div>



    </div>
<!-- Modal para crear cartelera -->
<div id="modalCrearCartelera" class="modal">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2>Crear Cartelera</h2>
        <form id="formCrearCartelera" enctype="multipart/form-data">
            <label for="crearTitulo">Título:</label>
            <input type="text" id="crearTitulo" name="titulo" required>
            <label for="crearDescripcion">Descripción:</label>
            <textarea id="crearDescripcion" name="descripcion" required></textarea>
            <label for="crearDirector">Director:</label>
            <select id="crearDirector" name="director" required>
                <option value="">Selecciona un director</option>
                <?php foreach ($directores as $director): ?>
                    <option value="<?= $director['id'] ?>"><?= htmlspecialchars($director['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="crearGeneros">Géneros:</label>
                <select id="crearGeneros" name="generos[]" multiple size="5" required>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?= $genero['id'] ?>"><?= htmlspecialchars($genero['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            <label for="crearImg">Imagen:</label>
            <input type="file" id="crearImg" name="img">
            <button type="submit" class="btn-tabla btn-success">Crear Cartelera</button>
        </form>
    </div>

    <script src="../js/ajaxUsuarios.js"></script>
    <script src="../js/adminAjax.js"></script>
    <script src="../js/crearGenero.js"></script>
    <script src="../js/editarGenero.js"></script>
    <script src="../js/eliminarCartelera.js"></script>
    <script src="../js/editarCartelera.js"></script>
    <script src="../js/eliminarGenero.js"></script>
    <script src="../js/crearCartelera.js"></script>

</body>
</html>