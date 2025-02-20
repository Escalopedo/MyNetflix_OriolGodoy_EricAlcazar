$(document).ready(function () {
    // Acción para gestionar los usuarios: activar, desactivar, aprobar, rechazar
    $(".gestionar-usuario").click(function () {
        var userId = $(this).data("id");
        var accion = $(this).data("accion");
        var tr = $(this).closest("tr"); // Obtiene la fila completa
        var boton = tr.find(".gestionar-usuario"); // Encuentra el botón dentro de la fila

        $.ajax({
            url: "../php/gestionarUsuario.php",
            type: "POST",
            data: { id: userId, accion: accion },
            success: function (response) {
                if (response.indexOf("Acción realizada con éxito") !== -1) {
                    // Verifica la acción realizada y actualiza el botón correctamente
                    if (accion === "activar") {
                        boton.text("Desactivar");
                        boton.data("accion", "desactivar");
                        $("#tablaActivos").append(tr);
                    } else if (accion === "desactivar") {
                        boton.text("Activar");
                        boton.data("accion", "activar");
                        $("#tablaInactivos").append(tr);
                    } else if (accion === "aprobar") {
                        boton.text("Desactivar");
                        boton.data("accion", "desactivar");
                        $("#tablaActivos").append(tr);
                    } else if (accion === "rechazar") {
                        tr.remove(); // Si se rechaza, elimina la fila
                    }

                    // Remueve posibles duplicados de botones en la fila
                    tr.find(".gestionar-usuario").not(":first").remove();

                    // Recargar la lista de usuarios después de 2 segundos
                    setTimeout(function() {
                        obtenerUsuarios();
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error al procesar la solicitud:", error);
            }
        });
    });
});

// Función para obtener los usuarios de nuevo desde el servidor
function obtenerUsuarios() {
    $.ajax({
        url: "../php/obtenerUsuarios.php", // Archivo que retorna los usuarios actualizados
        type: "GET",
        success: function(response) {
            $('#usuariosActivos').html($(response).find('#tablaActivos').html());
            $('#usuariosInactivos').html($(response).find('#tablaInactivos').html());
        },
        error: function(xhr, status, error) {
            console.log("Error al obtener usuarios:", error);
        }
    });
}
