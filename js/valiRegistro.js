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

// Validar Nombre
function validarNombre() {
    var nombre = document.getElementById('nombre');
    var mensajeError = document.getElementById('errorNombre');

    if (nombre.value === "") {
        mostrarError(nombre, mensajeError, 'El nombre no puede estar vacío');
        return false;
    } else if (/[^a-zA-ZáéíóúÁÉÍÓÚ\s]/.test(nombre.value)) {
        mostrarError(nombre, mensajeError, 'El nombre no puede contener números');
        return false;
    } else if (nombre.value.length < 3) {
        mostrarError(nombre, mensajeError, 'El nombre debe tener al menos 3 caracteres');
        return false;
    } else {
        ocultarError(nombre, mensajeError);
        return true;
    }
}

// Validar Apellido
function validarApellido() {
    var apellido = document.getElementById('apellido');
    var mensajeError = document.getElementById('errorApellido');

    if (apellido.value === "") {
        mostrarError(apellido, mensajeError, 'El apellido no puede estar vacío');
        return false;
    } else if (/[^a-zA-ZáéíóúÁÉÍÓÚ\s]/.test(apellido.value)) {
        mostrarError(apellido, mensajeError, 'El apellido no puede contener números');
        return false;
    } else if (apellido.value.length < 3) {
        mostrarError(apellido, mensajeError, 'El apellido debe tener al menos 3 caracteres');
        return false;
    } else {
        ocultarError(apellido, mensajeError);
        return true;
    }
}

// Validar Correo
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

// Validar Teléfono
function validarTelefono() {
    var telefono = document.getElementById('telefono');
    var mensajeError = document.getElementById('errorTelefono');

    if (telefono.value === "") {
        mostrarError(telefono, mensajeError, 'El teléfono no puede estar vacío');
        return false;
    } else if (!/^\d{9}$/.test(telefono.value)) {
        mostrarError(telefono, mensajeError, 'El teléfono debe tener 9 dígitos');
        return false;
    } else {
        ocultarError(telefono, mensajeError);
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

// Validar Confirmar Contraseña
function validarConfirmarContrasena() {
    var confirmarContrasena = document.getElementById('confirmar_contrasena');
    var mensajeError = document.getElementById('errorConfirmar');
    var contrasena = document.getElementById('contrasena');

    if (confirmarContrasena.value === "") {
        mostrarError(confirmarContrasena, mensajeError, 'Por favor confirme la contraseña');
        return false;
    } else if (confirmarContrasena.value !== contrasena.value) {
        mostrarError(confirmarContrasena, mensajeError, 'Las contraseñas no coinciden');
        return false;
    } else {
        ocultarError(confirmarContrasena, mensajeError);
        return true;
    }
}

// Validar formulario al enviar
document.querySelector("form").addEventListener("submit", function(event) {
    var isValid = validarNombre() && validarApellido() && validarCorreo() && validarTelefono() && validarContrasena() && validarConfirmarContrasena();
    if (!isValid) {
        event.preventDefault();
    }
});