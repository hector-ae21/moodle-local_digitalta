import $ from "jquery";
import { toogleLikeAndDislike } from "local_dta/repositories/reactions_repository";
import { SELECTORS } from "./selectors";
import Notification from "core/notification";

/**
 * Update the like and dislike buttons in the UI.
 * @param {number} instanceid - The id of the experience.
 * @param {number} likes - The number of likes.
 * @param {number} dislikes - The number of dislikes.
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 * @return {void}
 */
function updateUI(instanceid, likes, dislikes, reaction) {
  const reactionSelectors = {
    1: SELECTORS.BUTTONS.likes,
    0: SELECTORS.BUTTONS.dislikes,
  };

  $(SELECTORS.BUTTONS.likes + SELECTORS.DATA.id(instanceid)).removeClass("active");
  $(SELECTORS.BUTTONS.dislikes + SELECTORS.DATA.id(instanceid)).removeClass("active");

  if (reactionSelectors.hasOwnProperty(reaction)) {
    $(reactionSelectors[reaction] + SELECTORS.DATA.id(instanceid)).addClass("active");
  }

  const likesText = likes ? likes : "";
  $(SELECTORS.DATA.id(instanceid) + SELECTORS.COUNTS.likes).text(likesText);
}

/**
 * Toggle the like and dislike buttons.
 * @param {number} instanceid - The id of the instance
 * @param {number} reaction - The reaction to add. 1 for like, 0 for dislike.
 */
export function toggle(instanceid, reaction = null) {
  const instancetype = $(SELECTORS.BUTTONS.likes).data("instance");
  const reactionSelectors = {
    1: SELECTORS.BUTTONS.likes,
    0: SELECTORS.BUTTONS.dislikes,
  };
  const isActive = $(reactionSelectors[reaction] + SELECTORS.DATA.id(instanceid)).hasClass("active");
  const action = isActive ? null : reaction;
  toogleLikeAndDislike({instancetype, instanceid, action})
    .then((response) => {
      return updateUI(instanceid, response.likes, response.dislikes, action);
    })
    .fail(Notification.exception);
}
