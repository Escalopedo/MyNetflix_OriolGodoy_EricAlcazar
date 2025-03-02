$(document).ready(function() {
    // Editar Cartelera
    $(".editar-cartelera").click(function() {
        let carteleraId = $(this).data("id");
        console.log("ID de la cartelera:", carteleraId); // Depuración

        // Obtener datos de la cartelera
        $.ajax({
            url: "../php/procesosAdmin/editarCartelera.php",
            type: "GET",
            data: { id: carteleraId },
            dataType: "json",
            success: function(response) {
                console.log("Respuesta del servidor:", response); // Depuración
                if (response.status === 'success') {
                    // Asignar los datos de la cartelera
                    $("#editCarteleraId").val(response.data.id);
                    $("#editTitulo").val(response.data.titulo);
                    $("#editDescripcion").val(response.data.descripcion);

                    // Cargar directores
                    loadDirectores(response.data.id_director);

                    // Cargar géneros
                    loadGeneros(response.data.generos);

                    // Imagen previa
                    if (response.data.img) {
                        $("#prevImg").attr("src", "../img/" + response.data.img);
                    } else {
                        $("#prevImg").attr("src", "");
                    }

                    // Mostrar el modal
                    $("#modalEditarCartelera").fadeIn();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Error desconocido',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error); // Depuración
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo obtener la información. Verifica la consola para más detalles.',
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
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar directores:", status, error); // Depuración
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar los directores.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
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
            error: function(xhr, status, error) {
                console.error("Error al cargar géneros:", status, error); // Depuración
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
                console.log("Respuesta del servidor:", response); // Depuración
                if (response.status === 'success') {
                    Swal.fire("Actualizado", response.message, "success");
                    $("#modalEditarCartelera").fadeOut();
                    // Opcional: Actualizar la fila de la cartelera editada en la página
                    // location.reload();
                } else {
                    Swal.fire("Error", response.message || 'Error desconocido', "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error); // Depuración
                Swal.fire("Error", "No se pudo guardar la cartelera. Verifica la consola para más detalles.", "error");
            }
        });
    });
});