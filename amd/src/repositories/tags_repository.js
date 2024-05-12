import Ajax from 'core/ajax';

/**
 * Get tags
 *
 * Valid args are:
 * - searchText: The tag to search
 * @method getTags
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with tags.
 */
export const getTags = args => {
    const request = {
        methodname: 'local_dta_tags_get',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Create tags.
 *
 * Valid args are:
 * - tag: The tag to create
 * @method createTags
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
