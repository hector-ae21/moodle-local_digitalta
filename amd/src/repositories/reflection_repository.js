import Ajax from 'core/ajax';

/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - reflectionid (int): The reflection id.
 * - group (string) : The group of the section.
 * - content (string): The content of the section.
 * @method sectionTextUpsert
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionTextUpsert = args => {
    const request = {
        methodname: 'local_dta_reflection_upsert_text_section',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Get cases.
 * @return {Promise} Resolve with warnings.
 */
export const getCases = () => {
    const request = {
        methodname: 'local_dta_get_cases',
        args: {}
    };
    return Ajax.call([request])[0];
};
