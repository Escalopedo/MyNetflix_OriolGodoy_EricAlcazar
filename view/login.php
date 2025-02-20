<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('conexion.php');
    
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $query->execute(['correo' => $correo]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($contrasena, $user['contrasena'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol'] = $user['rol'];
        header("Location: index.php");
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/valiLogin.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
<div class="registro-container">
    <div class="registro-header">
        <a href="index.php"><img src="../img/OjoNetflix.png" alt="Logo"></a>
    </div>
    <?php if (isset($_GET['error'])): ?>
    <p class="error-login"><?php echo htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>
    <div class="form-grid">
    <form method="POST" action="../php/valiLogin.php">
    <label for="correo">Correo:</label>
    <input type="email" name="correo" id="correo" onblur="validarCorreo()">
    <p id="errorCorreo" class="error-message"></p> <!-- Mensaje de error aquí -->

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" id="contrasena" onblur="validarContrasena()">
    <p id="errorContra" class="error-message"></p> <!-- Mensaje de error aquí -->

    <button type="submit">Iniciar sesión</button>
</form>

    </div>

</div>
</body>
</html>

