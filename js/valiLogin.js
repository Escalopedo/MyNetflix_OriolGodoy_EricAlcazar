// Función genérica para mostrar u ocultar errores
function mostrarError(input, mensajeError, mensaje) {
    input.style.border = '2px solid red';
    mensajeError.style.color = 'red';
    mensajeError.textContent = mensaje;
    mensajeError.classList.add("active"); // Mostrar mensaje
}

function ocultarError(input, mensajeError) {
    input.style.border = '';
    mensajeError.textContent = '';
    mensajeError.classList.remove("active"); // Ocultar mensaje
}

function validarCorreo() {
    var correo = document.getElementById('correo');
    var mensajeError = document.getElementById('errorCorreo');
    var regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (correo.value === "") {
        mostrarError(correo, mensajeError, 'El correo no puede estar vacío');
        return false;
    } else if (!regexCorreo.test(correo.value)) {
        mostrarError(correo, mensajeError, 'El correo no es válido');
        return false;
    } else {
        ocultarError(correo, mensajeError);
        return true;
    }
}


// Validar Contraseña
function validarContrasena() {
    var contrasena = document.getElementById('contrasena');
    var mensajeError = document.getElementById('errorContra');

    if (contrasena.value === "") {
        mostrarError(contrasena, mensajeError, 'La contraseña no puede estar vacía');
        return false;
    } else if (contrasena.value.length < 8) {
        mostrarError(contrasena, mensajeError, 'La contraseña debe tener al menos 8 caracteres');
        return false;
    } else {
        ocultarError(contrasena, mensajeError);
        return true;
    }
}

form = document.querySelector("form");
form.addEventListener("submit", function(event) {
    isValid = validarContrasena() && validarCorreo();
    if (!isValid) {
        event.preventDefault();
    }
});