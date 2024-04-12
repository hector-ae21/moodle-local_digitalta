import $ from "jquery";
import {toogleLikeAndDislike, saveComment, getComments} from "local_dta/repositories/reactions_repository";
import Notification from "core/notification";
import Template from "core/templates";
import {SELECTORS} from "../myexperience/selectors";

/**
 * Toggle the like and dislike buttons.
 * @param {number} caseid - The id of the case.
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 */
function toggle(caseid, reaction = null) {
    const reactionSelectors = {
        '1': SELECTORS.BUTTONS.likes,
        '0': SELECTORS.BUTTONS.dislikes,
    };
    const isActive = $(reactionSelectors[reaction] + SELECTORS.DATA.id(caseid)).hasClass("active");
    const action = isActive ? null : reaction;
    toogleLikeAndDislike({id: caseid, action, type: 0})
        .then((response) => {
            return updateReactionsUI(caseid, response.likes, response.dislikes, action);
        })
        .fail(Notification.exception);
}

/**
 * Update the like and dislike buttons in the UI.
 * @param {number} caseid - The id of the case.
 * @param {number} likes - The number of likes.
 * @param {number} dislikes - The number of dislikes.
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 * @return {void}
 */
function updateReactionsUI(caseid, likes, dislikes, reaction) {
    const reactionSelectors = {
        '1': SELECTORS.BUTTONS.likes,
        '0': SELECTORS.BUTTONS.dislikes,
    };

    $(SELECTORS.BUTTONS.likes + SELECTORS.DATA.id(caseid)).removeClass("active");
    $(SELECTORS.BUTTONS.dislikes + SELECTORS.DATA.id(caseid)).removeClass("active");

    if (reactionSelectors.hasOwnProperty(reaction)) {
        $(reactionSelectors[reaction] + SELECTORS.DATA.id(caseid)).addClass("active");
    }

    const likesText = likes ? likes : "";
    $(SELECTORS.DATA.id(caseid) + SELECTORS.COUNTS.likes).text(likesText);
}

/**
 * Send the comment to the server.
 * @return {void}
 */
function sendComment() {
    const comment = $(SELECTORS.COMMENT_INPUT).val().trim();
    const caseid = $(SELECTORS.ACTIONS.sendComment).data("id");

    if (comment) {
        saveComment({caseid, comment})
            .then((response) => {
                if (response.result) {
                    return updateCommentsUI();
                }
                return Notification.exception(response);
            })
            .fail(Notification.exception);
        $(SELECTORS.COMMENT_INPUT).val("");
    }
}

/**
 * Update the comments in the UI.
 * @return {void}
 */
async function updateCommentsUI() {
    const caseid = $(SELECTORS.ACTIONS.sendComment).data("id");
    if (!caseid) {
        return;
    }

    try {
        const response = await getComments({caseid});
        const comments = response.comments.map((comment) => ({
            comment: comment.comment,
            userfullname: comment.user.fullname,
        }));

        await Template.render("local_dta/experiences/comments", {comments})
            .then((html) => {
                $(SELECTORS.COMMENTS_LIST).html(html);
                return;
            })
            .catch(Notification.exception);

        $(SELECTORS.ACTIONS.viewComment + SELECTORS.DATA.id(caseid) + " span").text(response.comments.length);
    } catch (error) {
        Notification.exception(error);
    }
}

/**
 * Set the events for the module.
 * @return {void}
 */
function setEvents() {
    $(document).on("click", SELECTORS.ACTIONS.addLike, function() {
        toggle($(this).data("id"), 1);
    });
    $(document).on("click", SELECTORS.ACTIONS.addDislike, function() {
        toggle($(this).data("id"), 0);
    });
    $(document).on("click", SELECTORS.ACTIONS.sendComment, sendComment);
    $(document).on("click", SELECTORS.ACTIONS.viewComment, updateCommentsUI);
}

export const init = () => {
    setEvents();
    updateCommentsUI();
};