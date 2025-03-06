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

    // Función para cargar géneros en el formulario
    function loadGeneros(selectedGeneros) {
        $.ajax({
            url: "../php/procesosAdmin/getGeneros.php",
            type: "GET",
            dataType: "json",
            success: function(generos) {
                $("#editGeneros").empty(); // Limpiar las opciones anteriores

                if (generos && generos.length > 0) {
                    generos.forEach(function(genero) {
                        // Verifica si el género está seleccionado
                        let selected = selectedGeneros.includes(genero.id.toString()) ? "selected" : "";
                        $("#editGeneros").append(
                            `<option value="${genero.id}" ${selected}>${genero.nombre}</option>`
                        );
                    });
                } else {
                    $("#editGeneros").append('<option>No se encontraron géneros disponibles</option>');
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
        $("#formEditarCartelera")[0].reset(); // Limpiar formulario
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
                    Swal.fire({
                        title: 'Éxito!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Recargar página
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Error desconocido',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", status, error); // Depuración
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo guardar la cartelera. Verifica la consola para más detalles.',
                    icon: 'error'
                });
            }
        });
    });

    // Validaciones
    function validarEditTitulo() {
        const titulo = document.getElementById('editTitulo').value.trim();
        const errorTitulo = document.getElementById('errorEditTitulo');

        if (titulo === '') {
            errorTitulo.textContent = 'Por favor, ingresa un título.';
            errorTitulo.style.display = 'block';
            return false;
        } else if (titulo.length < 5 || titulo.length > 100) {
            errorTitulo.textContent = 'El título debe tener entre 5 y 100 caracteres.';
            errorTitulo.style.display = 'block';
            return false;
        } else if (!/^[a-zA-Z0-9\s.,\-]+$/.test(titulo)) {
            errorTitulo.textContent = 'El título solo puede contener letras, números, espacios, comas, puntos y guiones.';
            errorTitulo.style.display = 'block';
            return false;
        } else {
            errorTitulo.style.display = 'none';
            return true;
        }
    }

    function validarEditDescripcion() {
        const descripcion = document.getElementById('editDescripcion').value.trim();
        const errorDescripcion = document.getElementById('errorEditDescripcion');

        if (descripcion === '') {
            errorDescripcion.textContent = 'Por favor, ingresa una descripción.';
            errorDescripcion.style.display = 'block';
            return false;
        } else if (descripcion.length < 10 || descripcion.length > 500) {
            errorDescripcion.textContent = 'La descripción debe tener entre 10 y 500 caracteres.';
            errorDescripcion.style.display = 'block';
            return false;
        } else {
            errorDescripcion.style.display = 'none';
            return true;
        }
    }

    function validarEditDirector() {
        const director = document.getElementById('editDirector').value;
        const errorDirector = document.getElementById('errorEditDirector');

        if (director === '') {
            errorDirector.textContent = 'Por favor, selecciona un director.';
            errorDirector.style.display = 'block';
            return false;
        } else {
            errorDirector.style.display = 'none';
            return true;
        }
    }

    function validarEditGeneros() {
        const generos = document.querySelectorAll('#editGeneros option:selected');
        const errorGeneros = document.getElementById('errorEditGeneros');

        if (generos.length === 0) {
            errorGeneros.textContent = 'Por favor, selecciona al menos un género.';
            errorGeneros.style.display = 'block';
            return false;
        } else if (generos.length > 3) {
            errorGeneros.textContent = 'No puedes seleccionar más de 3 géneros.';
            errorGeneros.style.display = 'block';
            return false;
        } else {
            errorGeneros.style.display = 'none';
            return true;
        }
    }

    function validarEditImagen() {
        const img = document.getElementById('editImg').files[0];
        const errorImg = document.getElementById('errorEditImg');

        if (img) {
            // Validar el tipo de archivo
            const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            if (!tiposPermitidos.includes(img.type)) {
                errorImg.textContent = 'Por favor, sube una imagen válida (JPEG, PNG, GIF).';
                errorImg.style.display = 'block';
                return false;
            }

            // Validar el tamaño del archivo (no más de 5 MB)
            const tamañoMaximo = 5 * 1024 * 1024; // 5 MB
            if (img.size > tamañoMaximo) {
                errorImg.textContent = 'La imagen no puede superar los 5 MB.';
                errorImg.style.display = 'block';
                return false;
            }
        }
        errorImg.style.display = 'none';
        return true;
    }

    // Validar todo el formulario
    function validarEditFormulario() {
        const validaciones = [
            validarEditTitulo(),
            validarEditDescripcion(),
            validarEditDirector(),
            validarEditGeneros(),
            validarEditImagen()
        ];

        const btnEnviar = document.getElementById('btnEditEnviar');
        btnEnviar.disabled = !validaciones.every(Boolean);
    }

    // Eventos de validación en tiempo real
    $('#editTitulo').on('input', validarEditFormulario);
    $('#editDescripcion').on('input', validarEditFormulario);
    $('#editDirector').on('change', validarEditFormulario);
    $('#editGeneros').on('change', validarEditFormulario);
    $('#editImg').on('change', validarEditFormulario);

    // Validar al enviar el formulario
    document.getElementById('formEditarCartelera').addEventListener('submit', function(event) {
        validarEditFormulario(); // Validar antes de enviar
        if (document.getElementById('btnEditEnviar').disabled) {
            event.preventDefault(); // Evitar envío si hay errores
        }
    });
});