$(document).ready(function() {
    // Control de navegación entre secciones
    $(".nav-link").click(function() {
        var seccion = $(this).data("seccion"); // Obtener el nombre de la sección
        $(".seccion").hide(); // Ocultar todas las secciones
        $("#" + seccion).show(); // Mostrar solo la sección seleccionada
    });

    // Mostrar la primera sección por defecto (Usuarios)
    $(".nav-link[data-seccion='usuarios']").click();

    // Editar Cartelera
    $(document).ready(function() {
        $(".editar-cartelera").click(function() {
            let carteleraId = $(this).data("id");
    
            // Obtener datos de la cartelera (cambiar a GET)
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
    
                        // Cargar géneros en el select
                        $.ajax({
                            url: "../php/procesosAdmin/getGeneros.php",
                            type: "GET",
                            dataType: "json",
                            success: function(generos) {
                                $("#editGeneros").empty();
                                generos.forEach(function(genero) {
                                    let selected = response.generos.includes(genero.id) ? "selected" : "";
                                    $("#editGeneros").append(
                                        `<option value="${genero.id}" ${selected}>${genero.nombre}</option>`
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
