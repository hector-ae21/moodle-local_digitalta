import Ajax from 'core/ajax';

/**
 * Toggle the like and dislike state.
 *
 * Valid args are:
 * - instancetype: The instance type.
 * - instanceid: The instance id.
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
 * - instancetype: The instance type.
 * - instanceid: The instance id.
 * - comment: The comment.
 * @method saveComment
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const saveComment = args => {
    const request = {
        methodname: 'local_dta_reactions_save_comment',
        args: args
    };
    return Ajax.call([request])[0];
};

/**
 * Get the comments.
 *
 * Valid args are:
 * - instancetype: The instance type.
 * - instanceid: The instance id.
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
 * - instancetype: The instance type.
 * - instanceid: The instance id.
 * @method saveReport
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const saveReport = args => {
    const request = {
        methodname: 'local_dta_reactions_send_report',
        args: args
    };
    return Ajax.call([request])[0];
};
