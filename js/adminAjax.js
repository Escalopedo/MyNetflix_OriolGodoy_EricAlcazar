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
            url: "../php/editarCartelera.php",
            type: "POST",
            data: { accion: "get", id: carteleraId },
            dataType: "json",
            success: function(response) {
                $("#editCarteleraId").val(response.id);
                $("#editTitulo").val(response.titulo);
                $("#editDescripcion").val(response.descripcion);

                if (response.img) {
                    $("#prevImg").attr("src", "../img/" + response.img);
                } else {
                    $("#prevImg").attr("src", "");
                }

                $("#modalEditarCartelera").fadeIn();
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
            url: "../php/editarCartelera.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Mostrar un SweetAlert en vez de un alert
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Los cambios fueron guardados correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(function() {
                    // Recargar la página después de hacer clic en "Aceptar"
                    location.reload();
                });
            },
            error: function() {
                // Mostrar un SweetAlert en caso de error
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
                    url: "../php/eliminar_cartelera.php",
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