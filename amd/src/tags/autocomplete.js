import $ from "jquery";
import Autocomplete from "theme_dta/form-autocomplete";
import {createTags} from "local_dta/repositories/autocomplete_repository";
import Notification from "core/notification";


/**
 * Autocomplete tags.
 * @param {HTMLElement} area - The area to autocomplete.
 * @return {void}
 */
export function autocompleteTags(area) {
    Autocomplete.enhance(area);
    setDefaultSelectedTags(area);
    area = area.replace("#", "");
    document.getElementById(area).addEventListener("change", function(e) {
        handleNewTag(e.target.selectedOptions);
    });
}

/**
 * Set default selected tags.
 * @param {*} selector - The selector.
 */
function setDefaultSelectedTags(selector) {
  var select = $(selector);
  select.find('option[selected]').each(function() {
      select.val($(this).val()).trigger('change');
  });
}

/**
 * Handle new tag.
 * @param {Array} selectedOptions - The selected options.
 * @return {void}
 */
async function handleNewTag(selectedOptions) {

    for (var i = 0; i < selectedOptions.length; i++) {
      if (selectedOptions[i].value === "-1") {
        selectedOptions[i].label = selectedOptions[i].label.replace("Create: ", "");
        const {id} = await saveNewTag(selectedOptions[i].label);
        selectedOptions[i].value = parseInt(id);
      }
    }
}

/**
 * Save new tag
 * @param {string} tagName - The tag name.
 * @return {Promise}
 */
async function saveNewTag(tagName) {
    try {
      return await createTags({
        tag: tagName
      });
    } catch (error) {
      return Notification.exception(error);
    }
}