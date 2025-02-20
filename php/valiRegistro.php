<?php 

session_start();
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar si los datos ya existen en la base de datos
    $queryCorreo = $conexion->prepare("SELECT id FROM usuarios WHERE correo = :correo");
    $queryCorreo->execute(['correo' => $correo]);

    $queryTelefono = $conexion->prepare("SELECT id FROM usuarios WHERE telefono = :telefono");
    $queryTelefono->execute(['telefono' => $telefono]);

    if ($queryCorreo->rowCount() > 0) {
        $_SESSION['error'] = "El correo ya está registrado.";
    } elseif ($queryTelefono->rowCount() > 0) {
        $_SESSION['error'] = "El teléfono ya está registrado.";
    } elseif (!preg_match("/^[0-9]{9}$/", $telefono)) {
        $_SESSION['error'] = "El teléfono debe tener 9 dígitos.";
    } elseif (strlen($contrasena) < 8) {
        $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres.";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
    } else {
        // Encriptar contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar en la base de datos con estado 'pendiente'
        $query = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, correo, contrasena, telefono, estado) 
                                     VALUES (:nombre, :apellido, :correo, :contrasena, :telefono, 'pendiente')");

        $insertado = $query->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'contrasena' => $contrasena_hash,
            'telefono' => $telefono
        ]);

        if ($insertado) {
            $_SESSION['success'] = "Registro exitoso. Un administrador debe aprobar tu cuenta.";
        } else {
            $_SESSION['error'] = "Error en el registro. Inténtalo de nuevo.";
        }
    }

    header("Location: ../view/registro.php");
    exit();
}
