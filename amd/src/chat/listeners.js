import $ from "jquery";
import SELECTORS from "./selectors";
import {renderChat, renderMenuChat, handleSendMessage} from "./main";

/**
 * Set event listeners for the module.
 * @return {void}
 * */
export default function setEventListeners() {
    // Open chat
    $(document).on("click", SELECTORS.BUTTONS.OPEN_CHAT, function() {
        const id = $(this).data("id");
        renderChat(id);
    });

    // Back to menu
    $(document).on("click", SELECTORS.BUTTONS.BACK_MENU, function() {
        renderMenuChat();
    });

    // Reply message
    $(document).on("click", SELECTORS.BUTTONS.REPLY, function() {
        handleSendMessage();
    });


}