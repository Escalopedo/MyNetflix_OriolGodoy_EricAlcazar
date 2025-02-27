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