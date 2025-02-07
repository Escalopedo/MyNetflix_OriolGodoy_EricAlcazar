window.onload = function() {
    // Cuando se hace clic en el botón de like
    $('#like-button').click(function() {
        var peliculaId = $(this).data('pelicula-id'); // Obtener el ID de la película
        var likeButton = $(this);
        var likeText = $('#like-text');
        var numLikes = $('#num-likes'); // Elemento donde se muestra el número de likes

        // Realizar una solicitud AJAX
        $.ajax({
            url: '../php/like.php',  // Archivo PHP para manejar el like
            type: 'POST',
            data: {
                pelicula_id: peliculaId
            },
            success: function(response) {
                var responseJson = JSON.parse(response); // Asegurarnos de que la respuesta sea un JSON
                if (responseJson.status === 'success') {
                    // Cambiar el texto del like
                    likeText.text(responseJson.likeText);
                    // Actualizar el número de likes
                    numLikes.text(responseJson.numLikes);
                } else {
                    alert('Error: ' + responseJson.message); // Mostrar mensaje de error si existe
                }
            },
        });
    });
};
