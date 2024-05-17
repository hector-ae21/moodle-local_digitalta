import $ from "jquery";
import {SELECTORS} from "./selectors";
import Template from "core/templates";
import Notification from "core/notification";
import {getMentorRequestsByExperience} from "local_dta/repositories/mentor_repository";
import {get_string} from "core/str";
import {setEventListeners} from "./listeners";

const renderRequestsButton = async () => {
  const experienceid = $(SELECTORS.INPUT.EXPERIENCE_ID).val();
  const data = await getMentorRequestsByExperience({
    experienceid: experienceid,
  });
  if (data) {
    get_string("tutoring:request", "local_dta")
      .then((string) => {
        $(SELECTORS.BUTTON_REQUEST).append(data.total + " " + string);
        return true;
      })
      .fail(Notification.exception);
  }
  return true;
};

/**
 * Open mentor requests
 * @param {boolean} hideBack
 * @returns
 */
export const openMentorsRequests = async(hideBack = false) => {
  const experienceid = $(SELECTORS.INPUT.EXPERIENCE_ID).val() || 0;
  const data = await getMentorRequestsByExperience({
    experienceid: experienceid,
  });
  data.hideBack = hideBack;
  Template.render(SELECTORS.TEMPLATES.MENTOR_REQUESTS, data)
    .then((html) => {
      $("#chat").html(html);
      return;
    })
    .fail(Notification.exception);
  return;
};



/**
 * @module mentors/experience_view/main
 */
export default function init() {
  setEventListeners();
  renderRequestsButton();
}
