$(document).ready(function() {
    // Función para abrir el modal de crear género
    $('.crear-genero').click(function() {
        $('#modalCrearGenero').show();
    });

    // Función para cerrar el modal
    $('.cerrar').click(function() {
        $('#modalCrearGenero').hide();
    });

    // Función para crear un nuevo género
    $('#formCrearGenero').submit(function(event) {
        event.preventDefault();
        var nombre = $('#crearNombreGenero').val();
        $.ajax({
            url: '../php/procesosAdmin/crearGenero.php',
            method: 'POST',
            data: { nombre: nombre },
            success: function(response) {
                Swal.fire({
                    title: 'Éxito!',
                    text: 'El género ha sido creado.',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Hubo un problema al crear el género.',
                    icon: 'error'
                });
            }
        });
    });
});