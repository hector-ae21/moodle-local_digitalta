import Ajax from 'core/ajax';


/**
 * Valid args are:
 * - id (int) The id of the instance CASE OR EXPERIENCE.
 * - action (int) The action to perform. Valid values are 'like' or 'dislike' 0 for dislike or 1 for like.
 * - type (int) The type of the instance. Valid values are 'case' or 'experience'. 0 for cases or 1 for experience.
 * Set the favourite state on a list of courses.
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
 * @param {Object} args - The arguments for the function.
 * @returns {Promise} Resolve with warnings.
 */
export const saveComment = args => {
    const request = {
        methodname: 'local_dta_myexperience_save_comment',
        args: args
    };

    return Ajax.call([request])[0];
};

export const getComments = args => {
    const request = {
        methodname: 'local_dta_myexperience_get_comments',
        args: args
    };

    return Ajax.call([request])[0];
};
