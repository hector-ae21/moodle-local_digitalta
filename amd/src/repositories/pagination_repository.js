import Ajax from 'core/ajax';

/**
 * Load mentors.
 *
 * Valid args are:
 * - numLoaded (int): The number of mentors loaded.
 * - numToLoad (int): The number of mentors to load.
 * @method loadMentors
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const loadMentors = args => {
    const request = {
        methodname: 'local_dta_mentors_load',
        args: args,
    };
    return Ajax.call([request])[0];
};