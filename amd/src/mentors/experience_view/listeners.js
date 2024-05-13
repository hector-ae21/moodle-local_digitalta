import $ from "jquery";
import MentorView from "./main";
import { SELECTORS } from "./selectors";

export const setEventListeners = () => {
  $(SELECTORS.ACTION.SEE_REQUESTS).on("click", function () {
    MentorView.openMentorsRequests();
  });
};
