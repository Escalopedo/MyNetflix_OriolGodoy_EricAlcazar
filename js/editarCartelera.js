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
                    // Cargar los datos de la cartelera en los campos del formulario
                    $("#editCarteleraId").val(response.id);
                    $("#editTitulo").val(response.titulo);
                    $("#editDescripcion").val(response.descripcion);
    
                    // Cargar directores en el select
                    $.ajax({
                        url: "../php/procesosAdmin/getDirectores.php",
                        type: "GET",
                        dataType: "json",
                        success: function(directores) {
                            $("#editDirector").empty(); // Limpiar las opciones anteriores
                            directores.forEach(function(director) {
                                let selected = director.id == response.id_director ? "selected" : "";
                                $("#editDirector").append(
                                    `<option value="${director.id}" ${selected}>${director.nombre}</option>`
                                );
                            });
                        }
                    });
    
                    // Cargar géneros en el select de géneros
                    $.ajax({
                        url: "../php/procesosAdmin/getGeneros.php",
                        type: "GET",
                        dataType: "json",
                        success: function(generos) {
                            $("#editGeneros").empty(); // Limpiar las opciones anteriores
                            generos.forEach(function(genero) {
                                // Verificar si el género está seleccionado
                                let selected = response.generos.includes(genero.id.toString()) ? "selected" : "";
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
    
    // Cerrar modal
    $(".cerrar").click(function() {
        $("#modalEditarCartelera").fadeOut();
    });

    // Guardar cambios en la cartelera
    $("#formEditarCartelera").submit(function(event) {
        event.preventDefault();  // Prevenir el envío normal del formulario
    
        let formData = new FormData(this);  // Recoger todos los datos del formulario
    
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
                    // Aquí puedes actualizar la fila de la cartelera editada si es necesario
                    $("#modalEditarCartelera").fadeOut();
                    // Si es necesario, recargar la página para reflejar los cambios
                    location.reload();
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
