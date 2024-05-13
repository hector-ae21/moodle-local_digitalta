import Ajax from 'core/ajax';

/**
 * Get themes
 *
 * Valid args are:
 * - searchText: The theme to search
 * @method getThemes
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with themes.
 */
export const getThemes = args => {
    const request = {
        methodname: 'local_dta_themes_get',
        args: args
    };
    return Ajax.call([request])[0];
};
