import Ajax from 'core/ajax';

/**
 * Get languages
 *
 * Valid args are:
 * - prioritizeInstalled: Prioritize installed languages
 * @method getLanguages
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with languages.
 */
export const getLanguages = args => {
    const request = {
        methodname: 'local_dta_languages_get',
        args: args
    };
    return Ajax.call([request])[0];
};
