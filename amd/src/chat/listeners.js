import $ from "jquery";
import SELECTORS from "local_digitalta/chat/selectors";
import {renderMenuChat, handleSendMessage, renderMenuTutor} from "local_digitalta/chat/main";
import { renderChat } from "./main";

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
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('chatid')) {
            urlParams.delete('chatid');
            window.location.search = urlParams;
        }
        renderMenuChat();
    });

    // Back to menu
    $(document).on("click", SELECTORS.BUTTONS.BACK_MENU_EXPERIENCE, function() {
        renderMenuTutor();
    });

    // Reply message
    $(document).on("click", SELECTORS.BUTTONS.REPLY, function() {
        handleSendMessage();
    });

    $(document).on("keydown", SELECTORS.INPUTS.CHAT_REPLY, function(e) {
        if (e.key === "Enter" && this.value.trim() !== "") {
            handleSendMessage();
        }
    });
}