import Ajax from "core/ajax";
import Autocomplete from "theme_dta/form-autocomplete";

/**
 * Autocomplete tags.
 * @return {void}
 */
export function autocompleteTags() {
    Autocomplete.enhance('#autocomplete_tags', false, 'local_dta/myexperience/manage/autocomplete_handler_tags');
}

/**
 * Handle new tag.
 * @param {object} data
 * @return {void}
 */
export function handleNewTag(data) {
    if (data.label.startsWith('Create: ')) {
        var tagName = data.value;
        Ajax.call([{
            methodname: 'local_dta_create_tags',
            args: {tag: tagName},
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
