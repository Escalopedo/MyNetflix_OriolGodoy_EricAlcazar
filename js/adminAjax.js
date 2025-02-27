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