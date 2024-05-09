import $ from 'jquery';
import Template from 'core/templates';
import Notification from 'core/notification';
import SELECTORS from './selectors';
import { getChatRooms, sendMessage, getMessages } from 'local_dta/repositories/chat_repository';
import setEventListeners from './listeners';


/**
 * Create a chat in the target
 * @param {string} target
 */
export default function createChatInTarget(target) {
    SELECTORS.TARGET = target;
    initComponent();
    return;
}

// Initialize the component
const initComponent = () => {
    setEventListeners();
    renderMenuChat();
};

/**
 * Render menu chat
 */
export async function renderMenuChat() {
    const { chatrooms } = await getChatRooms();
    Template.render(SELECTORS.TEMPLATES.MENU_CHAT, {
        chatrooms
    }).then((html) => {
        $(SELECTORS.TARGET).html(html);
        return;
    }).fail(Notification.exception);
}

/**
 * Open chat
 * @param {number} id
 * Render chat
 */
export async function renderChat(id) {
    const {messages} = await getMessages({ chatid: id });
    SELECTORS.OPEN_CHAT_ID = id;
    Template.render(SELECTORS.TEMPLATES.CHAT, {
        SELECTORS
    }).then((html) => {
        $(SELECTORS.TARGET).html(html);
        handlerMessages(messages);
        return;
    }).fail(Notification.exception);
}


/**
 * Render messages in chat
 * @param {Array} messages
 */
export async function handlerMessages(messages) {
    let html = '';
    const promises = messages.map((msg) => {
        const {message, timecreated, is_mine} = msg;
        return renderMessage(message, timecreated, is_mine);
    });
    try {
        html = (await Promise.all(promises)).join('');
    } catch (error) {
        Notification.exception(error);
    }
    $(SELECTORS.CONTAINERS.MESSAGES).html(html);
}

/**
 * Render my message
 * @param {string} text
 * @param {string} time
 * @param {boolean} mine
 * @returns {Promise}
 */
export async function renderMessage(text, time, mine) {
    const TEMPLATE = mine ? SELECTORS.TEMPLATES.MY_MESSAGE : SELECTORS.TEMPLATES.OTHER_MESSAGE;
    return Template.render(TEMPLATE, { text, time });
}

/**
 * Handle send message
 * @returns {Promise}
 */
export async function handleSendMessage() {
    const message = $(SELECTORS.INPUTS.CHAT_REPLY).val().trim();
    sendMessage({
        chatid: SELECTORS.OPEN_CHAT_ID,
        message,
    }).then(() => {
        $(SELECTORS.INPUTS.CHAT_REPLY).val('');
        addNewMessage(message);
        return;
    }).fail(Notification.exception);
    return;
}

/**
 * Add new message
 * @param {string} message
 */
async function addNewMessage(message) {
    const date = new Date().toLocaleTimeString('es-ES', {hour12: false, hour: '2-digit', minute: '2-digit'});
    const html = await renderMessage(message, date, true);

    $(SELECTORS.CONTAINERS.MESSAGES).append(html);
}