import $ from "jquery";
import Notification from "core/notification";
import {toogleLikeAndDislike} from "local_dta/repositories/reactionsRepository";
import {SELECTORS} from "./selectors";
import {saveComment} from "../repositories/reactionsRepository";

/**
 * Toggle the like and dislike buttons.
 * @param {number} experienceid - The id of the experience.
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 */
function toggle(experienceid, reaction = null) {
  const reactionSelectors = {
    "1": SELECTORS.BUTTONS.likes,
    "0": SELECTORS.BUTTONS.dislikes,
  };
  const isActive = $(reactionSelectors[reaction] + SELECTORS.DATA.id(experienceid)).hasClass("active");
  const action = isActive ? null : reaction;

  toogleLikeAndDislike({experienceid, action})
    .then((response) => {
      return updateReactionsUI(experienceid, response.likes, response.dislikes, action);
    })
    .fail(Notification.exception);
}

/**
 * Update the like and dislike buttons in the UI.
 * @param {number} experienceid - The id of the experience.
 * @param {number} likes - The number of likes.
 * @param {number} dislikes - The number of dislikes.
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 * @return {void}
 */
function updateReactionsUI(experienceid, likes, dislikes, reaction) {
  const reactionSelectors = {
    "1": SELECTORS.BUTTONS.likes,
    "0": SELECTORS.BUTTONS.dislikes,
  };

  $(SELECTORS.BUTTONS.likes + SELECTORS.DATA.id(experienceid)).removeClass("active");
  $(SELECTORS.BUTTONS.dislikes + SELECTORS.DATA.id(experienceid)).removeClass("active");

  if (reactionSelectors.hasOwnProperty(reaction)) {
    $(reactionSelectors[reaction] + SELECTORS.DATA.id(experienceid)).addClass("active");
  }

  const likesText = likes ? likes : "";
  $(SELECTORS.DATA.id(experienceid) + SELECTORS.COUNTS.likes).text(likesText);
}

/**
 * Toggle the comments section.
 * @return {void}
 */
function toggleComments() {
  $("#commentsCollapse").collapse("toggle");
}

/**
 * Send the comment to the server.
 * @return {void}
 */
function sendComment() {
    const comment = $(SELECTORS.COMMENT_INPUT).val().trim();
    const experienceid = $(SELECTORS.COMMENT_INPUT).data('id');

    if (comment) {
        saveComment({experienceid, comment})
        .then((response) => {
            //Add the comment to the list
            const comment = response.comment;
            const html = `<li class="list-group-item">${comment.comment}</li>`;
            $(SELECTORS.COMMENTS_LIST).append(html);
            //Update the comment count
            const count = $(SELECTORS.COUNTS.comment).text();
            $(SELECTORS.COUNTS.comment).text(parseInt(count) + 1);
            return;
        })
        .fail(Notification.exception);
        $(SELECTORS.COMMENT_INPUT).val('');
    }
}

/**
 * Update the comments in the UI.
 * @return {void}
 */
function updateCommentsUI() {
    

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
  $(document).on("click", SELECTORS.ACTIONS.toggleComments, toggleComments);
  $(document).on("click", SELECTORS.ACTIONS.sendComment, sendComment);
}

export const init = () => {
  setEvents();
};
