// local/tuplugin/amd/src/insertfilepicker.js
define(['jquery'], function($) {
    return {
        init: function() {
            $(document).ready(function() {
                // Verifica si la variable del filepicker está definida
                if (M.filepickerHtml) {
                    // Inserta el HTML del filepicker en el contenedor especificado
                    $("#filepickerContainer").html(M.filepickerHtml);
                }
            });
        }
    };
});
