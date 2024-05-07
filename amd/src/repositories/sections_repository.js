import Ajax from 'core/ajax';

/**
 * Upsert a section
 *
 * Valid args are:
 * - sectionid: The id of the section.
 * - sectiontype: The type of the section.
 * - component: The component name.
 * - componentinstance: The component instance.
 * - group: The group of the section.
 * - sequence: The sequence of the section.
 * - content: The content of the section.
 * @method sectionUpsert
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionUpsert = args => {
    const request = {
        methodname: 'local_dta_sections_upsert',
        args
    };
    return Ajax.call([request])[0];
};

/**
 * Delete a section.
 *
 * Valid args are:
 * - id (int): The section id.
 * @method sectionDelete
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionDelete = args => {
    const request = {
        methodname: 'local_dta_sections_delete',
        args
    };
    return Ajax.call([request])[0];
};
