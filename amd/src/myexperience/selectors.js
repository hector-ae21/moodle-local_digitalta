

/**
 * @module myexperience/selectors
 */
export const SELECTORS = {
    ACTIONS: {
        addLike: '[data-action="like"]',
        addDislike: '[data-action="dislike"]',
        sendComment: '[data-action="send-comment"]',
    },
    COMMENTS_LIST: 'ul #commentsList',
    COMMENT_INPUT: 'input #commentInput',
    BUTTONS: {
        likes: '#like-button',
        dislikes: '#dislike-button',
        comment: '#comment-button',
    },
    COUNTS: {
        likes: '#like-button span',
        dislikes: '#dislike-button span',
        comment: '#comment-button span',
    },
    DATA: {
        id: function (id) {
            return `[data-id="${id}"]`;
        },
    },
};