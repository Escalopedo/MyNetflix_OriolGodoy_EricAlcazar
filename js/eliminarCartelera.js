$(document).ready(function() {
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