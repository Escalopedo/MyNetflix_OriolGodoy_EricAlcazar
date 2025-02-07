<?php
session_start();
include('conexion.php');

$errores = []; // Array para almacenar los errores

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    // Validación de campos vacíos
    if (empty($correo)) {
        $errores['correo'] = "El campo correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El correo no tiene un formato válido.";
    }

    if (empty($contrasena)) {
        $errores['contrasena'] = "El campo contraseña es obligatorio.";
    }

    // Si no hay errores, proceder con la autenticación
    if (empty($errores)) {
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $query->execute(['correo' => $correo]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];
            header("Location: ../view/index.php");
            exit();
        } else {
            $errores['general'] = "Correo o contraseña incorrectos.";
        }
    }
}
?>