$(document).ready(function () {
    // Acción para gestionar los usuarios: activar, desactivar, aprobar, rechazar
    $(".gestionar-usuario").click(function () {
        var userId = $(this).data("id");
        var accion = $(this).data("accion");
        var tr = $(this).closest("tr"); // Obtén la fila completa

        $.ajax({
            url: "../php/gestionarUsuario.php",
            type: "POST",
            data: { id: userId, accion: accion },
            success: function (response) {
                if (response.indexOf("Acción realizada con éxito") !== -1) {
                    // Realiza el cambio en el botón de la tabla
                    if (accion === "activar") {
                        tr.find("button").text("Desactivar");
                        tr.find("button").data("accion", "desactivar");
                        $("#tablaActivos").append(tr);
                    } else if (accion === "desactivar") {
                        tr.find("button").text("Activar");
                        tr.find("button").data("accion", "activar");
                        $("#tablaInactivos").append(tr);
                    }

                    // Esperar 2 segundos y luego recargar la lista de usuarios
                    setTimeout(function() {
                        obtenerUsuarios(); // Función que recarga los usuarios
                    }, 2000); // 2000 milisegundos = 2 segundos
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
            // Actualizar las tablas de usuarios con los nuevos datos
            $('#usuariosActivos').html($(response).find('#tablaActivos').html());
            $('#usuariosInactivos').html($(response).find('#tablaInactivos').html());
        },
        error: function(xhr, status, error) {
            console.log("Error al obtener usuarios:", error);
        }
    });
}
