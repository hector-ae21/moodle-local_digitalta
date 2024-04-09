import {Ajax} from "core/ajax";
import {Autocomplete} from "core/form-autocomplete";

var createOptionText = 'Create: ';
/**
 * Autocomplete tags.
 * @return {void}
 */
export function autocompleteTags() {
    Autocomplete.enhance('#autocomplete_tags', false, {
        transport: function(searchText, success, failure) {
            Ajax.call([{
                methodname: 'tu_metodo_de_busqueda',
                args: {search: searchText},
                done: function(result) {
                    if (result.length === 0) {
                        // Si no hay resultados, ofrece la opción de crear una nueva etiqueta
                        success([{label: createOptionText + searchText, value: searchText}]);
                    } else {
                        success(result);
                    }
                },
                fail: failure
            }]);
        }
    });
}

/**
 * Handle new tag.
 * @param {object} data
 * @return {void}
 */
export function handleNewTag(data) {
    if (data.label.startsWith(createOptionText)) {
        var tagName = data.value;
        Ajax.call([{
            methodname: 'tu_metodo_para_crear_etiqueta',
            args: {name: tagName},
            done: function(result) {
                return result;
            },
            fail: function(err) {
                // eslint-disable-next-line no-console
                console.log(err);
            }
        }]);
    }
}
