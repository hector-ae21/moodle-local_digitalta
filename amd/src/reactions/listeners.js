import $ from "jquery";
import { SELECTORS } from "./selectors";
import { toggle } from "./likes_dislikes";
import { sendComment, updateUI as updateCommentsUI } from "./comments";
import { sendReport } from "./reports";

/**
 * Set event listeners for the module.
 * @return {void}
 * */
export function setEventListeners() {
    // LIKES AND DISLIKES
    $(document).on("click", SELECTORS.ACTIONS.addLike, function () {
        toggle($(this).data("id"), 1);
    });
    $(document).on("click", SELECTORS.ACTIONS.addDislike, function () {
        toggle($(this).data("id"), 0);
    });

    // COMMENTS
    $(document).on("click", SELECTORS.ACTIONS.sendComment, function () {
        sendComment();
    });
    $(document).on("click", SELECTORS.ACTIONS.viewComment, function () {
        updateCommentsUI();
    });

    // REPORTS
    $(document).on("click", SELECTORS.ACTIONS.sendReport, function () {
        sendReport($(this).data("id"));
    });
}
