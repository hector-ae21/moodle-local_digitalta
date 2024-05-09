/**
 * @module reactions/selectors
 */

const SELECTORS = {
    TARGET: "",
    OPEN_CHAT_ID: 0,
    TEMPLATES: {
        "MENU_CHAT": "local_dta/test/menu_chat/index",
        "CHAT": "local_dta/test/chat/index",
        "MY_MESSAGE": "local_dta/test/chat/message_my",
        "OTHER_MESSAGE": "local_dta/test/chat/message_other"
    },
    BUTTONS: {
        "OPEN_CHAT": ".open-chat",
        "BACK_MENU": "#back-menu",
        "REPLY": "#reply-message"
    },
    INPUTS: {
        "CHAT_REPLY": "#chat-reply-input",
    },
    CONTAINERS: {
        "MESSAGES": "#message-list"
    }
};

export default SELECTORS;