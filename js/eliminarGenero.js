$(document).ready(function() {
    // Eliminar género
    $('.eliminar-genero').on('click', function() {
        var generoId = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../php/procesosAdmin/eliminarGenero.php',
                    type: 'POST',
                    data: {
                        id: generoId
                    },
                    success: function(response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Género eliminado',
                                confirmButtonText: 'Aceptar',
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un problema al eliminar el género: ' + response
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
            }
        });
    });
});