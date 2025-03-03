$(document).ready(function() {
    // Abrir modal de edición de género
    $('.editar-genero').on('click', function() {
        var generoId = $(this).data('id');
        var generoNombre = $(this).closest('tr').find('td:nth-child(2)').text();

        $('#editGeneroId').val(generoId);
        $('#editNombre').val(generoNombre);

        $('#modalEditarGenero').show(); // Muestra el modal
    });

    // Cerrar modal al hacer clic en la "X"
    $('.cerrar').on('click', function() {
        $('#modalEditarGenero').hide(); // Oculta el modal
    });

    // Cerrar modal al hacer clic fuera del modal
    $(window).on('click', function(event) {
        if (event.target === $('#modalEditarGenero')[0]) {
            $('#modalEditarGenero').hide(); // Oculta el modal
        }
    });

    // Enviar formulario de edición
    $('#formEditarGenero').on('submit', function(e) {
        e.preventDefault();

        var generoId = $('#editGeneroId').val();
        var generoNombre = $('#editNombre').val();

        $.ajax({
            url: '../php/procesosAdmin/editarGenero.php',
            type: 'POST',
            data: {
                id: generoId,
                nombre: generoNombre
            },
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Género actualizado',
                        confirmButtonText: 'Aceptar'
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al actualizar el género: ' + response

                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la solicitud',
                    text: 'Hubo un problema al enviar la solicitud: ' + error
                });
            }
        });
    });
});

// Función para validar el nombre del género en el modal de edición
function validarEditNombreGenero() {
    const nombreGenero = document.getElementById('editNombre').value.trim();
    const errorNombreGenero = document.getElementById('errorEditNombreGenero');

    if (nombreGenero === '') {
        errorNombreGenero.textContent = 'Por favor, ingresa un nombre para el género.';
        errorNombreGenero.style.display = 'block';
        return false;
    } else if (nombreGenero.length < 3 || nombreGenero.length > 50) {
        errorNombreGenero.textContent = 'El nombre debe tener entre 3 y 50 caracteres.';
        errorNombreGenero.style.display = 'block';
        return false;
    } else if (!/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ\-]+$/.test(nombreGenero)) {
        errorNombreGenero.textContent = 'El nombre solo puede contener letras, espacios, guiones y caracteres especiales (á, é, í, ó, ú, ñ).';
        errorNombreGenero.style.display = 'block';
        return false;
    } else {
        errorNombreGenero.style.display = 'none';
        return true;
    }
}

// Función para validar todo el formulario de edición de género
function validarEditFormularioGenero() {
    const esNombreValido = validarEditNombreGenero();

    // Habilitar o deshabilitar el botón de enviar
    const btnEditGenero = document.getElementById('btnEditGenero');
    if (esNombreValido) {
        btnEditGenero.disabled = false; // Habilitar el botón
    } else {
        btnEditGenero.disabled = true; // Deshabilitar el botón
    }
}

// Validar el formulario al intentar enviar
document.getElementById('formEditarGenero').addEventListener('submit', function(event) {
    validarEditFormularioGenero(); // Validar antes de enviar
    if (document.getElementById('btnEditGenero').disabled) {
        event.preventDefault(); // Evitar envío si el botón está deshabilitado
    }
});