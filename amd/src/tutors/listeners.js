import $ from "jquery";
import { sendTutorRequest, cancelTutorRequest } from "local_digitalta/tutors/main";
import { SELECTORS } from "local_digitalta/tutors/selectors";

export const setEventListeners = () => {
  $(SELECTORS.BUTTONS.SEND_MENTOR_REQUEST).on("click", function () {
    const id = $(this).data("experienceid");
    sendTutorRequest(id);
  });

    $(SELECTORS.BUTTONS.CANCEL_MENTOR_REQUEST).on("click", function () {
        const id = $(this).data("experienceid");
        cancelTutorRequest(id);
    });
};
