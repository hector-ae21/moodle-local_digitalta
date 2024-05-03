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


/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - ourcaseid: The id of ourcase. (required)
 * - sectionid: The id of the section.
 * @method sectionTextDelete
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionTextDelete = args => {
    const request = {
        methodname: 'local_dta_ourcases_section_text_delete',
        args: args
    };
    return Ajax.call([request])[0];
};


/**
 * Edit a ourcase.
 *
 * Valid args are:
 * - ourcaseid: The id of ourcase. (required)
 * - experienceid: The id of the experience.
 * - userid: The id of the user.
 * - date: The date of the case.
 * - status: The status of the case
 * @method ourcaseEdit
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const ourcaseEdit = args => {
    const request = {
        methodname: 'local_dta_ourcases_edit',
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
        methodname: 'local_dta_ourcases_get',
        args: {}
    };
    return Ajax.call([request])[0];
};
