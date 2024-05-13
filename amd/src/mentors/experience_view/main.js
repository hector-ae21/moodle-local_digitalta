import $ from "jquery";
import { SELECTORS } from "./selectors";
import { TEMPLATES } from "./templates";
import Template from "core/templates";
import Notification from "core/notification";
import { getMentorRequestsByExperience } from "local_dta/repositories/mentor_repository";
import { get_string } from "core/str";
import { setEventListeners } from "./listeners";

const renderRequestsButton = () => {
  const experienceid = $(SELECTORS.INPUT.EXPERIENCE_ID).val();
  getMentorRequestsByExperience({ experienceid: experienceid })
    .then((data) => {
      if (data) {
        get_string("tutoring:request", "local_dta")
          .then((string) => {
            $(SELECTORS.BUTTON_REQUEST).append(data.total + " " + string);
            return true;
          })
          .fail(Notification.exception);
      }
      return true;
    })
    .fail(Notification.exception);
};

export const openMentorsRequests = () => {
  const experienceid = $(SELECTORS.INPUT.EXPERIENCE_ID).val();
  getMentorRequestsByExperience({ experienceid: experienceid })
    .then((data) => {
      Template.render(TEMPLATES.MENTOR_REQUESTS, data)
        .then((html) => {
          $("#chat").html(html);
          return;
        })
        .fail(Notification.exception);
        return;
    })
    .fail(Notification.exception);
};

/**
 * @module mentors/experience_view/main
 */
export default function init() {
  setEventListeners();
  renderRequestsButton();
}
