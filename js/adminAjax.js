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