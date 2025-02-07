$(document).ready(function () {
    // Acción para gestionar los usuarios: activar, desactivar, aprobar, rechazar
    $(".gestionar-usuario").click(function () {
        let userId = $(this).data("id");
        let accion = $(this).data("accion");

        // Enviar la solicitud AJAX
        $.ajax({
            url: "../php/gestionarUsuario.php",
            type: "POST",
            data: { id: userId, accion: accion },
            dataType: "json", // Esperamos respuesta JSON
            success: function (response) {
                // Verificamos si la respuesta contiene el estado de éxito
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();  // Recargar la página si la acción fue exitosa
                } else {
                    alert(response.message);  // Mostrar el mensaje de error si hay
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Mejor manejo de errores con más detalles
                console.error("Error en la solicitud:", textStatus, errorThrown);
                alert("Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo.");
            }
        });
    });

    // Función para aprobar usuarios (pendientes)
    $(".gestionar-usuario[data-accion='aprobar']").click(function () {
        let userId = $(this).data("id");
        // Enviar solicitud para aprobar
        gestionarUsuario(userId, 'aprobar');
    });

    // Función para rechazar usuarios (pendientes)
    $(".gestionar-usuario[data-accion='rechazar']").click(function () {
        let userId = $(this).data("id");
        // Enviar solicitud para rechazar
        gestionarUsuario(userId, 'rechazar');
    });

    // Función común para activar, desactivar, aprobar y rechazar usuarios
    function gestionarUsuario(userId, accion) {
        $.ajax({
            url: "../php/gestionarUsuario.php",
            type: "POST",
            data: { id: userId, accion: accion },
            dataType: "json", // Esperamos respuesta JSON
            success: function (response) {
                // Verificamos si la respuesta contiene el estado de éxito
                if (response.status === "success") {
                    location.reload();  // Recargar la página si la acción fue exitosa
                } else {
                    alert(response.message);  // Mostrar el mensaje de error si hay
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Mejor manejo de errores con más detalles
                console.error("Error en la solicitud:", textStatus, errorThrown);
                alert("Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo.");
            }
        });
    }
});
