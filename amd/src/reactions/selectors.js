/**
 * @module reactions/selectors
 */

export const SELECTORS = {
    ACTIONS: {
        addLike: '[data-action="like"]',
        addDislike: '[data-action="dislike"]',
        sendComment: '[data-action="send-comment"]',
        viewComment: '[data-action="view-comment"]',
        toggleComments: '[data-target="#commentsCollapse"]',
        sendReport: '[data-action="flag"]',
    },
    COMMENTS_LIST: '#commentsList',
    COMMENT_INPUT: '#commentInput',
    BUTTONS: {
        likes: '#like-button',
        dislikes: '#dislike-button',
        comment: '#comment-button',
        report: "#flag-button"
    },
    COUNTS: {
        likes: '#like-button span',
        dislikes: '#dislike-button span',
        comment: '#comment-button span',
    },
    DATA: {
        id: function(id) {
            return `[data-id="${id}"]`;
        },
    },
};
