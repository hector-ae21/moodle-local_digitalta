import Ajax from 'core/ajax';

/**
 * Toggle the like and dislike state.
 *
 * Valid args are:
 * - component: The component.
 * - componentinstance: The component instance.
 * - action: The action to perform.
 * @method toogleLikeAndDislike
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const toogleLikeAndDislike = args => {
    const request = {
        methodname: 'local_dta_reactions_toggle_like_dislike',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Save a comment.
 *
 * Valid args are:
 * - component: The component.
 * - componentinstance: The component instance.
 * - comment: The comment.
 * @method addComment
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const addComment = args => {
    const request = {
        methodname: 'local_dta_reactions_add_comment',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Get the comments.
 *
 * Valid args are:
 * - component: The component.
 * - componentinstance: The component instance.
 * @method getComments
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const getComments = args => {
    const request = {
        methodname: 'local_dta_reactions_get_comments',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Saves a report
 *
 * Valid args are:
 * - component: The component.
 * - componentinstance: The component instance.
 * - description: The description.
 * @method toggleReport
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const toggleReport = args => {
    const request = {
        methodname: 'local_dta_reactions_toggle_report',
        args: args
    };
    return Ajax.call([request])[0];
};
