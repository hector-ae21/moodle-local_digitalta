import $ from "jquery";
import { sendTutorRequest, cancelTutorRequest, acceptTutorRequest } from "local_digitalta/tutors/main";
import { SELECTORS } from "local_digitalta/tutors/selectors";

export const setEventListeners = () => {
  const experienceid = $(SELECTORS.INPUTS.experienceid).val();

  $(SELECTORS.BUTTONS.SEND_MENTOR_REQUEST).on("click", function () {
    sendTutorRequest(experienceid);
  });

  $(SELECTORS.BUTTONS.CANCEL_MENTOR_REQUEST).on("click", function () {
    cancelTutorRequest(experienceid);
  });


  $(SELECTORS.BUTTONS.ACCEPT_MENTOR_REQUEST).on("click", function () {
    const requestid = ($(this).data("requestid"));
    acceptTutorRequest(requestid, true);
  });

  $(SELECTORS.BUTTONS.REJECT_MENTOR_REQUEST).on("click", function () {
    const requestid = ($(this).data("requestid"));
    acceptTutorRequest(requestid, false);
  });
};
