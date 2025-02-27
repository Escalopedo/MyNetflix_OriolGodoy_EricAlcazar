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
    $(".editar-cartelera").click(function() {
        let carteleraId = $(this).data("id");
    
        // Obtener datos de la cartelera
        $.ajax({
            url: "../php/procesosAdmin/editarCartelera.php",
            type: "GET",  // Cambié de POST a GET
            data: { id: carteleraId },  // Solo mandas el ID
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    $("#editCarteleraId").val(response.id);
                    $("#editTitulo").val(response.titulo);
                    $("#editDescripcion").val(response.descripcion);
    
                    // Cargar directores en el select
                    $.ajax({
                        url: "../php/procesosAdmin/getDirectores.php",
                        type: "GET",
                        dataType: "json",
                        success: function(directores) {
                            $("#editDirector").empty();
                            directores.forEach(function(director) {
                                let selected = director.id == response.id_director ? "selected" : "";
                                $("#editDirector").append(
                                    `<option value="${director.id}" ${selected}>${director.nombre}</option>`
                                );
                            });
                        }
                    });
    
                    // Cargar géneros en el formulario con checkboxes
                    $.ajax({
                        url: "../php/procesosAdmin/getGeneros.php",
                        type: "GET",
                        dataType: "json",
                        success: function(generos) {
                            $("#editGeneros").empty(); // Limpiar los checkboxes anteriores
                            generos.forEach(function(genero) {
                                // Verificar si el género está seleccionado
                                let checked = response.generos.includes(genero.id.toString()) ? "checked" : "";
                                $("#editGeneros").append(
                                    `<label><input type="checkbox" name="generos[]" value="${genero.id}" ${checked}> ${genero.nombre}</label><br>`
                                );
                            });
                        }
                    });


    
                    // Imagen previa
                    if (response.img) {
                        $("#prevImg").attr("src", "../img/" + response.img);
                    } else {
                        $("#prevImg").attr("src", "");
                    }
    
                    $("#modalEditarCartelera").fadeIn();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
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
    
    $(".cerrar").click(function() {
        $("#modalEditarCartelera").fadeOut();
    });

    // Guardar cambios en la cartelera
    $("#formEditarCartelera").submit(function(event) {
        event.preventDefault();
    
        let formData = new FormData(this);
    
        $.ajax({
            url: "../php/procesosAdmin/editarCartelera.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire("Actualizado", response.message, "success");
                    // Actualizar la fila de la cartelera editada (opcional)
                    $("#modalEditarCartelera").fadeOut();
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "No se pudo guardar la cartelera.", "error");
            }
        });
    });

    // Eliminar Cartelera
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
