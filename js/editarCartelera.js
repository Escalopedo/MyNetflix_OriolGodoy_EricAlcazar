$(document).ready(function() {
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
                    // Asignar los datos de la cartelera
                    $("#editCarteleraId").val(response.id);
                    $("#editTitulo").val(response.titulo);
                    $("#editDescripcion").val(response.descripcion);

                    // Cargar directores
                    loadDirectores(response.id_director);

                    // Cargar géneros
                    loadGeneros(response.generos);

                    // Imagen previa
                    if (response.img) {
                        $("#prevImg").attr("src", "../img/" + response.img);
                    } else {
                        $("#prevImg").attr("src", "");
                    }

                    // Mostrar el modal
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

    // Función para cargar directores en el select
    function loadDirectores(selectedId) {
        $.ajax({
            url: "../php/procesosAdmin/getDirectores.php",
            type: "GET",
            dataType: "json",
            success: function(directores) {
                $("#editDirector").empty();
                directores.forEach(function(director) {
                    let selected = director.id == selectedId ? "selected" : "";
                    $("#editDirector").append(
                        `<option value="${director.id}" ${selected}>${director.nombre}</option>`
                    );
                });
            }
        });
    }
// Función para cargar géneros en el formulario con el <select>
function loadGeneros(selectedGeneros) {
    $.ajax({
        url: "../php/procesosAdmin/getGeneros.php",
        type: "GET",
        dataType: "json",
        success: function(generos) {
            $("#editGeneros").empty(); // Limpiar las opciones anteriores del <select>

            if (generos && generos.length > 0) {
                generos.forEach(function(genero) {
                    // Verifica si el género está seleccionado
                    let selected = selectedGeneros.includes(genero.id.toString()) ? "selected" : "";
                    $("#editGeneros").append(
                        `<option value="${genero.id}" ${selected}>${genero.nombre}</option>`
                    );
                });
            } else {
                $("#editGeneros").append('<option value="">No se encontraron géneros disponibles</option>');
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar los géneros.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}


    // Cerrar modal
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
                    $("#modalEditarCartelera").fadeOut();
                    // Opcional: Actualizar la fila de la cartelera editada en la página
                    // location.reload();
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "No se pudo guardar la cartelera.", "error");
            }
        });
    });
});
