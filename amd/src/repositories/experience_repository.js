import Ajax from 'core/ajax';

/**
 * Set the favourite state on a list of courses.
 *
 * Valid args are:
 * - experienceid: The experience id (optional)
 * - userid: The user id
 * - title: The title of the experience
 * - description: The description of the experience
 * - context: The context of the experience
 * - lang : The language of the experience
 * - visible: The visibility of the experience
 * - tags: The tags of the experience
 * @method sectionTextUpsert
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const experienceUpsert = args => {
    const request = {
        methodname: 'local_dta_myexperience_upsert',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Get tags.
 *
 * Valid args are:
 * - tag: The tag to create
 * @method getTags
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with tags.
 */
export const createTags = args => {
    const request = {
        methodname: 'local_dta_create_tags',
        args: args
    };
    return Ajax.call([request])[0];
};
