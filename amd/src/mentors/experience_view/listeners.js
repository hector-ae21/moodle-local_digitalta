import $ from "jquery";
import MentorView from "./main";
import { SELECTORS } from "./selectors";
import {acceptanceMentorRequest} from "local_dta/repositories/tutoring_repository";

export const setEventListeners = () => {
  $(SELECTORS.ACTION.SEE_REQUESTS).on("click", function () {
    MentorView.openMentorsRequests();
  });

  $(document).on("click", SELECTORS.BUTTONS.ACCEPT, function () {
    acceptanceRequestHandler($(this).data("requestid"), true);
  });

  $(document).on("click", SELECTORS.BUTTONS.REJECT, function() {
    acceptanceRequestHandler($(this).data("requestid"), false);
  });
};


/**
 * Acceptance request handler
 * @param {int} requestid
 * @param {bool} acceptance
 */
export async function acceptanceRequestHandler(requestid, acceptance) {
  await acceptanceMentorRequest({
    requestid: requestid,
    acceptance: acceptance,
  });
  window.location.reload();
}