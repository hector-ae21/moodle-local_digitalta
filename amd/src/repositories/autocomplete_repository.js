import Ajax from 'core/ajax';
/**
 * Create tags.
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
