import { tutoringMeetingsDelete } from "local_digitalta/repositories/tutoring_repository";
import $ from "jquery";

export const SELECTORS = {
  BUTTONS: {
    closeCall: "#close-video-meeting",
    closeChatCall: "#close-videocall-from-chat",
  },
};

/**
 * Sets event listeners for the Google Meet functionality.
 */
export const setEventListeners = () => {
  $(document).on("click", SELECTORS.BUTTONS.closeCall, (event) => {
    const id = event.currentTarget.dataset.chatid;
    closeCallOnClick(id);
  });

  $(document).on("click", SELECTORS.BUTTONS.closeChatCall, (event) => {
    const id = event.currentTarget.dataset.chatid;
    closeCallOnClick(id, true);
  });
};

/**
 * Closes the Google Meet call when the close call button is clicked.
 * @param {string} id - The ID of the chat associated with the Google Meet call.
 * @param {boolean} isChat - A boolean indicating whether the call is being closed from chat page.
 */
export async function closeCallOnClick(id, isChat = false) {
  const isDeleted = await tutoringMeetingsDelete({ chatid: id });
  if (isDeleted) {
    if (isChat) {
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set("chatid", id);
      window.location.search = urlParams;
    } else {
      location.reload();
    }
  }
}

/**
 * Initializes the Google Meet functionality by setting event listeners.
 */
export const init = () => {
  setEventListeners();
};
