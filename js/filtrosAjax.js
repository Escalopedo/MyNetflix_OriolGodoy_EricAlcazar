$(document).ready(function () {
    // Detectar cambios en los filtros
    $("#searchTitle, #searchGenre, #likedOnly").on("input change", function () {
        applyFilters();
    });

    function applyFilters() {
        let searchTitle = $("#searchTitle").val();
        let searchGenre = $("#searchGenre").val();
        let likedOnly = $("#likedOnly").prop("checked") ? 1 : 0;

        $.ajax({
            url: "../php/filtros.php",
            type: "POST",
            data: { searchTitle, searchGenre, likedOnly },
            dataType: "json",
            success: function (response) {
                updateCatalog(response);
            },
            error: function (xhr, status, error) {
                console.error("Error en AJAX: " + error);
            }
        });
    }

    function updateCatalog(peliculas) {
        let catalogo = $("#catalogo .grid-peliculas");
        catalogo.html("");

        if (peliculas.length === 0) {
            catalogo.html("<p>No se encontraron películas.</p>");
            return;
        }

        peliculas.forEach(pelicula => {
            let peliculaHTML = `
                <div class="pelicula">
                    <a href="detalles.php?id=${pelicula.id}">
                        <img src="${pelicula.img}" alt="${pelicula.titulo}">
                        <p class="titulo">${pelicula.titulo}</p>
                    </a>
                    <a href='like_pelicula.php?id=${pelicula.id}' class="like-button">Like</a>
                </div>
            `;
            catalogo.append(peliculaHTML);
        });
    }
});
