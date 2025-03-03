$(document).ready(function() {
    // Función para abrir el modal de crear género
    $('.crear-genero').click(function() {
        $('#modalCrearGenero').show();
    });

    // Función para cerrar el modal
    $('.cerrar').click(function() {
        $('#modalCrearGenero').hide();
    });

    // Función para validar el nombre del género
    window.validarNombreGenero = function() {
        const nombreGenero = $('#crearNombreGenero').val().trim();
        const errorNombreGenero = $('#error-nombre');

        if (nombreGenero === '') {
            errorNombreGenero.text('Por favor, ingresa un nombre para el género.').show();
            return false;
        } else if (nombreGenero.length < 3 || nombreGenero.length > 50) {
            errorNombreGenero.text('El nombre debe tener entre 3 y 50 caracteres.').show();
            return false;
        } else if (!/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ\-]+$/.test(nombreGenero)) {
            errorNombreGenero.text('El nombre solo puede contener letras, espacios, guiones y caracteres especiales (á, é, í, ó, ú, ñ).').show();
            return false;
        } else {
            errorNombreGenero.hide();
            return true;
        }
    };

    // Validar el nombre del género cuando el campo pierde el foco (onblur)
    $('#crearNombreGenero').on('blur', function() {
        validarNombreGenero();
        habilitarBotonEnviar(); // Habilitar o deshabilitar el botón después de validar
    });

    // Validar el nombre del género mientras el usuario escribe (oninput)
    $('#crearNombreGenero').on('input', function() {
        validarNombreGenero();
        habilitarBotonEnviar(); // Habilitar o deshabilitar el botón después de validar
    });

    // Función para habilitar o deshabilitar el botón de enviar
    function habilitarBotonEnviar() {
        const esValido = validarNombreGenero();
        if (esValido) {
            $('#btnCrearGenero').prop('disabled', false); // Habilitar el botón
        } else {
            $('#btnCrearGenero').prop('disabled', true); // Deshabilitar el botón
        }
    }

    // Función para enviar el formulario
    $('#formCrearGenero').submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe automáticamente

        // Validar el nombre del género
        if (!validarNombreGenero()) {
            return; // Detener el envío si la validación falla
        }

        // Obtener el nombre del género
        const nombre = $('#crearNombreGenero').val().trim();

        // Enviar los datos al servidor usando AJAX
        $.ajax({
            url: '../php/procesosAdmin/crearGenero.php',
            method: 'POST',
            data: { nombre: nombre },
            success: function(response) {
                // Convertir la respuesta a JSON
                const data = JSON.parse(response);

                if (data.status === "success") {
                    // Mostrar mensaje de éxito con SweetAlert
                    Swal.fire({
                        title: 'Éxito!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        // Limpiar el formulario y recargar la página
                        $('#crearNombreGenero').val('');
                        $('#error-nombre').hide();
                        location.reload(); // Recargar la página para ver los cambios
                    });
                } else {
                    // Mostrar mensaje de error debajo del campo
                    $('#error-nombre').text(data.message).show();
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de red o del servidor
                if (xhr.responseText) {
                    const data = JSON.parse(xhr.responseText);
                    $('#error-nombre').text(data.message).show();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Hubo un problema al crear el género.',
                        icon: 'error'
                    });
                }
            }
        });
    });
});