import Autocomplete from "theme_dta/form-autocomplete";

/**
 * Autocomplete themes.
 * @param {HTMLElement} area - The area to autocomplete.
 * @return {void}
 */
export function autocompleteThemes(area) {
    Autocomplete.enhance(area, null, "local_dta/themes/autocomplete_method");
    area = area.replace("#", "");
}
