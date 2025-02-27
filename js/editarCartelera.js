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