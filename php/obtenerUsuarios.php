<?php
include('conexion.php');

// Consultar los usuarios activos
$query = "SELECT * FROM usuarios WHERE estado = 'activo'";
$usuariosActivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Consultar los usuarios inactivos
$query = "SELECT * FROM usuarios WHERE estado = 'inactivo'";
$usuariosInactivos = $conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Tabla de Usuarios Activos -->
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
                <button class="gestionar-usuario" data-id="<?= $usuario['id'] ?>" data-accion="desactivar">Desactivar</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Tabla de Usuarios Inactivos -->
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
                <button class="gestionar-usuario" data-id="<?= $usuario['id'] ?>" data-accion="activar">Activar</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
?>
