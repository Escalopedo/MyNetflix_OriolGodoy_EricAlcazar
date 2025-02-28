$(document).ready(function() {
    // Función para abrir el modal de crear cartelera
    $('.crear-cartelera').click(function() {
        $('#modalCrearCartelera').show();
    });

    // Función para cerrar el modal
    $('.cerrar').click(function() {
        $('.modal').hide(); // Oculta todos los modales
    });

    // Función para crear una nueva cartelera
    $('#formCrearCartelera').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '../php/procesosAdmin/crearCartelera.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Éxito!',
                    text: 'La cartelera ha sido creada.',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Hubo un problema al crear la cartelera.',
                    icon: 'error'
                });
            }
        });
    });
});