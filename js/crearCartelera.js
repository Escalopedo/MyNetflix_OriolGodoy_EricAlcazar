$(document).ready(function() {
    // Función para abrir el modal de crear cartelera
    $('.crear-cartelera').click(function() {
        $('#modalCrearCartelera').show();
    });

    // Función para cerrar el modal
    $('.cerrar').click(function() {
        $('#modalCrearCartelera').hide();
    });

    // Validar el formulario cuando el usuario interactúa con los campos
    $('#crearTitulo').on('input blur', validarFormulario);
    $('#crearDescripcion').on('input blur', validarFormulario);
    $('#crearDirector').on('change', validarFormulario);
    $('#crearGeneros').on('change', validarFormulario);
    $('#crearImg').on('change', validarFormulario);

    // Función para crear una nueva cartelera
    $('#formCrearCartelera').submit(function(event) {
        event.preventDefault();

        // Validar el formulario antes de enviar
        if (!validarFormulario()) {
            return; // Detener el envío si la validación falla
        }

        var formData = new FormData(this);
        $.ajax({
            url: '../php/procesosAdmin/crearCartelera.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === "success") {
                    Swal.fire({
                        title: 'Éxito!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Mostrar errores del servidor en el formulario
                    if (data.errors) {
                        mostrarErrores(data.errors); // Errores específicos de campos
                    } else if (data.message) {
                        mostrarErrores({ message: data.message }); // Error general
                    }
                    deshabilitarBotonEnviar(); // Deshabilitar el botón de enviar si hay errores
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de red o del servidor
                if (xhr.responseText) {
                    const data = JSON.parse(xhr.responseText);
                    mostrarErrores({ message: data.message || 'Hubo un problema al crear la cartelera.' });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Hubo un problema al crear la cartelera.',
                        icon: 'error'
                    });
                }
                deshabilitarBotonEnviar(); // Deshabilitar el botón de enviar si hay errores
            }
        });
    });

    function mostrarErrores(errors) {
        // Limpiar mensajes de error anteriores
        $('.error-message').hide().text('');

        // Mostrar errores específicos de cada campo
        if (errors.titulo) {
            $('#errorTitulo').text(errors.titulo).show();
        }
        if (errors.descripcion) {
            $('#errorDescripcion').text(errors.descripcion).show();
        }
        if (errors.director) {
            $('#errorDirector').text(errors.director).show();
        }
        if (errors.generos) {
            $('#errorGeneros').text(errors.generos).show();
        }
        if (errors.img) {
            $('#errorImagen').text(errors.img).show();
        }

        // Mostrar mensaje de error general (si existe)
        if (errors.message) {
            $('#errorGeneral').text(errors.message).show();
        }
    }
    // Función para deshabilitar el botón de enviar
    function deshabilitarBotonEnviar() {
        $('#btnEnviar').prop('disabled', true);
    }
});

// Función para validar el título
function validarTitulo() {
    const titulo = document.getElementById('crearTitulo').value.trim();
    const errorTitulo = document.getElementById('errorTitulo');

    if (titulo === '') {
        errorTitulo.textContent = 'Por favor, ingresa un título.';
        errorTitulo.style.display = 'block';
        return false;
    } else if (titulo.length < 5 || titulo.length > 100) {
        errorTitulo.textContent = 'El título debe tener entre 5 y 100 caracteres.';
        errorTitulo.style.display = 'block';
        return false;
    } else if (!/^[a-zA-Z0-9\s.,\-]+$/.test(titulo)) {
        errorTitulo.textContent = 'El título solo puede contener letras, números, espacios, comas, puntos y guiones.';
        errorTitulo.style.display = 'block';
        return false;
    } else {
        errorTitulo.style.display = 'none';
        return true;
    }
}

// Función para validar la descripción
function validarDescripcion() {
    const descripcion = document.getElementById('crearDescripcion').value.trim();
    const errorDescripcion = document.getElementById('errorDescripcion');

    if (descripcion === '') {
        errorDescripcion.textContent = 'Por favor, ingresa una descripción.';
        errorDescripcion.style.display = 'block';
        return false;
    } else if (descripcion.length < 10 || descripcion.length > 500) {
        errorDescripcion.textContent = 'La descripción debe tener entre 10 y 500 caracteres.';
        errorDescripcion.style.display = 'block';
        return false;
    } else {
        errorDescripcion.style.display = 'none';
        return true;
    }
}

// Función para validar el director
function validarDirector() {
    const director = document.getElementById('crearDirector').value;
    const errorDirector = document.getElementById('errorDirector');

    if (director === '') {
        errorDirector.textContent = 'Por favor, selecciona un director.';
        errorDirector.style.display = 'block';
        return false;
    } else {
        errorDirector.style.display = 'none';
        return true;
    }
}

// Función para validar los géneros
function validarGeneros() {
    const generos = document.getElementById('crearGeneros');
    const generosSeleccionados = Array.from(generos.selectedOptions).map(option => option.value);
    const errorGeneros = document.getElementById('errorGeneros');

    if (generosSeleccionados.length === 0) {
        errorGeneros.textContent = 'Por favor, selecciona al menos un género.';
        errorGeneros.style.display = 'block';
        return false;
    } else if (generosSeleccionados.length > 3) {
        errorGeneros.textContent = 'No puedes seleccionar más de 3 géneros.';
        errorGeneros.style.display = 'block';
        return false;
    } else {
        errorGeneros.style.display = 'none';
        return true;
    }
}

// Función para validar la imagen
function validarImagen() {
    const img = document.getElementById('crearImg').files[0];
    const errorImagen = document.getElementById('errorImagen');

    if (img) {
        // Validar el tipo de archivo
        const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!tiposPermitidos.includes(img.type)) {
            errorImagen.textContent = 'Por favor, sube una imagen válida (JPEG, PNG, GIF).';
            errorImagen.style.display = 'block';
            return false;
        }

        // Validar el tamaño del archivo (no más de 5 MB)
        const tamañoMaximo = 5 * 1024 * 1024; // 5 MB
        if (img.size > tamañoMaximo) {
            errorImagen.textContent = 'La imagen no puede superar los 5 MB.';
            errorImagen.style.display = 'block';
            return false;
        }

        // Validar dimensiones de la imagen (opcional)
        const imagen = new Image();
        imagen.src = URL.createObjectURL(img);
        imagen.onload = function() {
            if (this.width > 1920 || this.height > 1080) {
                errorImagen.textContent = 'La imagen no puede superar las dimensiones 1920x1080.';
                errorImagen.style.display = 'block';
                return false;
            }
        };
    }
    errorImagen.style.display = 'none';
    return true;
}

// Función para validar todo el formulario
function validarFormulario() {
    const esTituloValido = validarTitulo();
    const esDescripcionValida = validarDescripcion();
    const esDirectorValido = validarDirector();
    const esGenerosValido = validarGeneros();
    const esImagenValida = validarImagen();

    // Habilitar o deshabilitar el botón de enviar
    const btnEnviar = document.getElementById('btnEnviar');
    if (esTituloValido && esDescripcionValida && esDirectorValido && esGenerosValido && esImagenValida) {
        btnEnviar.disabled = false; // Habilitar el botón
    } else {
        btnEnviar.disabled = true; // Deshabilitar el botón
    }

    // Retornar true si todos los campos son válidos
    return esTituloValido && esDescripcionValida && esDirectorValido && esGenerosValido && esImagenValida;
}