$(document).ready(function() {
    // Control de navegación entre secciones
    $(".nav-link").click(function() {
        var seccion = $(this).data("seccion"); // Obtener el nombre de la sección
        $(".seccion").hide(); // Ocultar todas las secciones
        $("#" + seccion).show(); // Mostrar solo la sección seleccionada
    });

    // Mostrar la primera sección por defecto (Usuarios)
    $(".nav-link[data-seccion='usuarios']").click();
});

// Editar Género

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


// Eliminar Género

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
                                showConfirmButton: false,
                                timer: 1500
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

// Editar Cartelera
$(document).ready(function() {
    // Abrir el modal cuando se haga clic en "Editar Cartelera"
    $(".editar-cartelera").click(function() {
        let carteleraId = $(this).data("id");

        // Obtener los datos actuales de la cartelera
        $.ajax({
            url: "../php/procesosAdmin/editarCartelera.php",
            type: "POST",
            data: { accion: "get", id: carteleraId },
            dataType: "json",
            success: function(response) {
                $("#editCarteleraId").val(response.id);
                $("#editTitulo").val(response.titulo);
                $("#editDescripcion").val(response.descripcion);
                $("#editDirector").val(response.id_director); // Director actual

                // Limpiar y cargar géneros actuales
                $("#editGeneros").empty();
                response.generos.forEach(function(genero) {
                    $("#editGeneros").append(
                        `<option value="${genero.id}" selected>${genero.nombre}</option>`
                    );
                });

                // Mostrar imagen actual
                if (response.img) {
                    $("#prevImg").attr("src", "../img/" + response.img);
                } else {
                    $("#prevImg").attr("src", "");
                }

                $("#modalEditarCartelera").fadeIn();
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo obtener la información.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Cerrar el modal
    $(".cerrar").click(function() {
        $("#modalEditarCartelera").fadeOut();
    });

    // Guardar cambios en la cartelera
    $("#formEditarCartelera").submit(function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append("accion", "editar");
        formData.append("id", $("#editCarteleraId").val());

        $.ajax({
            url: "../php/procesosAdmin/editarCartelera.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Los cambios fueron guardados correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(function() {
                    location.reload();
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al guardar los cambios. Intenta nuevamente.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});


// Eliminar Cartelera

$(document).ready(function() {
    $(".eliminar-cartelera").on("click", function() {
        let carteleraId = $(this).data("id");
        let row = $(this).closest("tr");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../php/procesosAdmin/eliminarCartelera.php",
                    type: "POST",
                    data: { id: carteleraId },
                    success: function(response) {
                        Swal.fire("Eliminado", "La cartelera ha sido eliminada.", "success");
                        row.fadeOut(500, function() { $(this).remove(); });
                    },
                    error: function() {
                        Swal.fire("Error", "No se pudo eliminar la cartelera.", "error");
                    }
                });
            }
        });
    });
});