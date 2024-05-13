import Ajax from 'core/ajax';

/**
 * Load mentors.
 *
 * Valid args are:
 * - experienceid (int): The experience id.
 * @method getMentorsByExperience
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const getMentorRequestsByExperience = args => {
    const request = {
        methodname: 'local_dta_get_mentor_requests',
        args: args,
    };
    return Ajax.call([request])[0];
};