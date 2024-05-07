import Ajax from 'core/ajax';

/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - caseid: The id of the case. (required)
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
        methodname: 'local_dta_cases_section_text_upsert',
        args: args
    };
    return Ajax.call([request])[0];
};


/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - caseid: The id of the case. (required)
 * - sectionid: The id of the section.
 * @method sectionTextDelete
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const sectionTextDelete = args => {
    const request = {
        methodname: 'local_dta_cases_section_text_delete',
        args: args
    };
    return Ajax.call([request])[0];
};


/**
 * Edit a case.
 *
 * Valid args are:
 * - caseid: The id of the case. (required)
 * - experienceid: The id of the experience.
 * - userid: The id of the user.
 * - date: The date of the case.
 * - status: The status of the case
 * @method caseEdit
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const caseEdit = args => {
    const request = {
        methodname: 'local_dta_cases_edit',
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
        methodname: 'local_dta_cases_get',
        args: {}
    };
    return Ajax.call([request])[0];
};
