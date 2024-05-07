import Ajax from 'core/ajax';

/**
 * Edit a case.
 *
 * Valid args are:
 * - caseid: The id of the case.
 * - title: The title of the case.
 * - description: The description of the case.
 * - lang: The language of the case.
 * - status: The status of the case.
 * - themes: The themes of the case.
 * - tags: The tags of the case.
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
 * @method getCases
 * @return {Promise} Resolve with warnings.
 */
export const getCases = () => {
    const request = {
        methodname: 'local_dta_cases_get',
        args: {}
    };
    return Ajax.call([request])[0];
};
