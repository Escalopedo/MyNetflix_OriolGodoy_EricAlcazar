<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

// Limpiar mensajes después de mostrarlos
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/registro.css">
    <script src="../js/valiRegistro.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
<div class="registro-container">
    <div class="registro-header">
        <img src="../img/OjoNetflix.png" alt="Logo">
    </div>

    <!-- Mostrar mensaje de error si existe -->
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="../php/valiRegistro.php" method="POST" id="form">
        <div class="form-grid">
            <div class="form-column">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" onblur="validarNombre()">
                <div class="error-container">
                    <p id="errorNombre" class="error-message"></p>
                </div>

                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" onblur="validarCorreo()">
                <div class="error-container">
                    <p id="errorCorreo" class="error-message"></p>
                </div>

                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" onblur="validarContrasena()">
                <div class="error-container">
                    <p id="errorContra" class="error-message"></p>
                </div>
            </div>

            <div class="form-column">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" onblur="validarApellido()">
                <div class="error-container">
                    <p id="errorApellido" class="error-message"></p>
                </div>

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" onblur="validarTelefono()">
                <div class="error-container">
                    <p id="errorTelefono" class="error-message"></p>
                </div>

                <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" onblur="validarConfirmarContrasena()">
                <div class="error-container">
                    <p id="errorConfirmar" class="error-message"></p>
                </div>
            </div>
        </div>

        <button type="submit">Registrarse</button>
    </form>
</div>
</body>
</html>
