$(document).ready(function() {
    // Abrir modal de edición
    $('.editar-genero').on('click', function() {
        const generoId = $(this).data('id');
        const generoNombre = $(this).closest('tr').find('td:nth-child(2)').text().trim();

        // Validar que los datos existan
        if (!generoId || !generoNombre) {
            Swal.fire('Error', 'No se pudo cargar la información del género', 'error');
            return;
        }

        // Llenar el modal
        $('#editGeneroId').val(generoId);
        $('#editNombre').val(generoNombre);
        $('#modalEditarGenero').show();
    });

    // Validar formulario en tiempo real
    $('#editNombre').on('input', function() {
        validarEditFormularioGenero();
    });

    // Enviar formulario
    $('#formEditarGenero').on('submit', function(e) {
        e.preventDefault();

        // Validar antes de enviar
        if (!validarEditFormularioGenero()) {
            return;
        }

        // Obtener datos
        const generoId = $('#editGeneroId').val();
        const generoNombre = $('#editNombre').val().trim();

        // Enviar AJAX
        $.ajax({
            url: '../php/procesosAdmin/editarGenero.php',
            type: 'POST',
            data: { id: generoId, nombre: generoNombre },
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Género actualizado',
                        confirmButtonText: 'Aceptar'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Hubo un problema: ' + response, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Error de conexión: ' + xhr.statusText, 'error');
            }
        });
    });
});

// Validar nombre del género
function validarEditNombreGenero() {
    const nombre = document.getElementById('editNombre').value.trim();
    const error = document.getElementById('errorEditNombreGenero');

    if (!nombre) {
        error.textContent = 'El nombre es obligatorio.';
        error.style.display = 'block';
        return false;
    }

    if (nombre.length < 3 || nombre.length > 50) {
        error.textContent = 'Debe tener entre 3 y 50 caracteres.';
        error.style.display = 'block';
        return false;
    }

    if (!/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ\-]+$/.test(nombre)) {
        error.textContent = 'Solo letras, espacios y caracteres acentuados.';
        error.style.display = 'block';
        return false;
    }

    error.style.display = 'none';
    return true;
}

// Validar formulario completo
function validarEditFormularioGenero() {
    const isValid = validarEditNombreGenero();
    document.getElementById('btnEditGenero').disabled = !isValid;
    return isValid;
}