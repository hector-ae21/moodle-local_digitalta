/**
 * Main module for tutors experience view
 *
 * @module     local_digitalta/tutors/experience_view/main
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 */

import $ from "jquery";
import { SELECTORS } from "local_digitalta/tutors/experience_view/selectors";
import Template from "core/templates";
import Notification from "core/notification";
import { tutoringRequestsGet } from "local_digitalta/repositories/tutoring_repository";
import { get_string } from "core/str";
import { setEventListeners } from "local_digitalta/tutors/experience_view/listeners";

const renderRequestsButton = async () => {
  const experienceid = 0;
  const data = await tutoringRequestsGet({
    experienceid: experienceid,
  });
  if (data) {
    get_string("tutoring:request", "local_digitalta")
      .then((string) => {
        $(SELECTORS.BUTTON_REQUEST).append(data.total + " " + string);
        return true;
      })
      .fail(Notification.exception);
  }
  return true;
};

/**
 * Open tutor requests
 * @param {boolean} hideBack
 * @returns
 */
export const openTutorsRequests = async (hideBack = false) => {
  const experienceid = 0;
  const data = await tutoringRequestsGet({
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
 * @module tutors/experience_view/main
 */
export const init = () => {
  setEventListeners();
  renderRequestsButton();
};
