import $ from "jquery";
import { SELECTORS } from "./selectors";
import Notification from "core/notification";
import { saveReport } from "../repositories/reactions_repository";

/**
 * Update report button in the UI.
 * @param {number} instanceid - The id of the experience.
 * @return {void}
 */
function updateUI(instanceid) {
  const element = $(SELECTORS.BUTTONS.report + SELECTORS.DATA.id(instanceid));

  if (!element.hasClass("active")) {
      element.addClass("active");
  }
}

/**
 * Toggle the like and dislike buttons.
 * @param {number} instanceid - The id of the instance
 */
export function sendReport(instanceid) {
  const isActive = $(SELECTORS.BUTTONS.report + SELECTORS.DATA.id(instanceid)).hasClass("active");
  if (isActive) {
    return;
  }
  const instancetype = $(SELECTORS.BUTTONS.report).data("instance");

  saveReport({ instancetype, instanceid })
    .then(() => {
      return updateUI(instanceid);
    })
    .fail(Notification.exception);
}
