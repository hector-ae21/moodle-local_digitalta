import $ from "jquery";
import { SELECTORS } from "./selectors";
import Notification from "core/notification";
import { toggleReport } from "../repositories/reactions_repository";

/**
 * Toggle the like and dislike buttons.
 * @param {number} componentinstance - The id of the instance
 */
export function sendReport(componentinstance) {
    const component = $(SELECTORS.BUTTONS.report).data("component");
    toggleReport({ instancetype, componentinstance })
        .then((response) => {
            return updateUI(componentinstance, response.reactiontype);
        })
        .fail(Notification.exception);
}

/**
 * Update report button in the UI.
 * @param {number} componentinstance - The id of the experience.
 * @param {number} reactiontype - The type of reaction. 1 to add, -1 to remove.
 * @return {void}
 */
function updateUI(componentinstance, reactiontype) {
    const element = $(SELECTORS.BUTTONS.report + SELECTORS.DATA.id(componentinstance));
    if (reactiontype === 1 && !element.hasClass("active")) {
        element.addClass("active");
    } else if (reactiontype === -1 && element.hasClass("active")) {
        element.removeClass("active");
    }
}
