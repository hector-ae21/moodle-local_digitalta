// En tu_plugin/amd/src/interactions.js
define(['jquery', 'core/templates', 'core/notification'], function($, Templates, Notification) {
    var likeCount = 256; // Valor inicial de likes quemado para el ejemplo.
    // Actualizar la lista de comentarios, que podría provenir de una fuente de datos en el servidor.
    var commentList = [{
        name: "Usuario 1", comment: "Este es un comentario."
    }, {
        name: "Usuario 2", comment: "¡Otro comentario aquí!"
    }];

    /**
     * Agrega un like al contenido.
     * @return {void}
     */
    function addLike() {
        likeCount++;
        $('#likeCount').text(likeCount);
        // Aquí se implementaría la llamada AJAX para actualizar los likes en el servidor.
    }

    /**
     * Agrega un dislike al contenido.
     * @return {void}
     */
    function addDislike() {
        // La lógica de dislike se implementaría aquí. No hay una actualización visible en la UI para el número de dislikes.
    }

    /**
     * Muestra u oculta la sección de comentarios.
     * @return {void}
     */
    function toggleComments() {
        $('#commentsCollapse').collapse('toggle');
    }

    /**
     * Envía un comentario al servidor.
     * @return {void}
     */
    function sendComment() {
        var commentText = $('#commentText').val().trim();
        if (commentText) {
            commentList.push({name: "Usuario Nuevo", comment: commentText}); // Agregar el comentario al array.
            updateCommentsList(); // Actualizar la lista de comentarios en la UI.
            $('#commentText').val(''); // Limpiar el campo de texto.
            // Aquí se implementaría la llamada AJAX para enviar el comentario al servidor.
        }
    }

    /**
     * Actualiza la lista de comentarios en la UI.
     * @return {void}
     */
    function updateCommentsList() {
        Templates.render('local_dta/myexperience/comment', {comments: commentList}).then(function(html) {
            return $('#commentsList').html(html);
        }).fail(Notification.exception);
    }

    // Exponer las funciones al objeto window para hacerlas accesibles globalmente.
    window.addLike = addLike;
    window.addDislike = addDislike;
    window.toggleComments = toggleComments;
    window.sendComment = sendComment;

    return {
        init: function() {
            updateCommentsList(); // Llamar a esta función para cargar los comentarios cuando se inicializa la página.
        }
    };
});
