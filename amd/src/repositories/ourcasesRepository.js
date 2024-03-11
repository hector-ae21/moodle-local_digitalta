import Ajax from 'core/ajax';

/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - ourcaseid: The id of ourcase. (required)
 * - sectionid: The id of the section.
 * - title: The title of the section.
 * - text: The content of the section.
 * - sequence : The sequence of the section.
 * @method sectionTextUpsert
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionTextUpsert = args => {
    const request = {
        methodname: 'local_dta_ourcases_section_text_upsert',
        args: args
    };

    return Ajax.call([request])[0];
};
