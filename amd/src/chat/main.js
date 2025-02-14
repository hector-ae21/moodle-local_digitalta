/**
 * Main module for tutors experience view
 *
 * @module     local_digitalta/chat/main
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 */

import $ from 'jquery';
import Template from 'core/templates';
import Notification from 'core/notification';
import SELECTORS from 'local_digitalta/chat/selectors';
import { chatsGetRooms, chatsSendMessage, chatsGetMessage, markMessagesAsRead } from 'local_digitalta/repositories/chat_repository';
import setEventListeners from 'local_digitalta/chat/listeners';
import Status from 'local_digitalta/chat/status';

const status = new Status();

/**
 * Create a chat in the target
 * @param {string} target
 * @param {int} experienceid
 * @param {boolean} single
 */
export default function createChatInTarget(target, experienceid = null, single = false) {
    SELECTORS.TARGET = target;
    initComponent(experienceid, single);
    return;
}

/**
 * Initialize chat component
 * @param {*} experienceid
 * @param {*} single
 */
const initComponent = async (experienceid, single) => {
    setEventListeners();
    if (experienceid) {
        openChatFromExperience(experienceid, single);
    }
    else {
        renderMenuChat();
    }
    setInterval(reloaderMessages, 1000);
};

/**
 * Render menu chat
 */
export async function renderMenuChat() {
    const { chatrooms } = await chatsGetRooms({experienceid: 0});
    const chats = chatrooms.filter((chat) => chat.ownexperience === true);
    const tutoringChats = chatrooms.filter((chat) => chat.ownexperience === false);
    Template.render(SELECTORS.TEMPLATES.MENU_CHAT, {
        tutoringChats : {
            length: tutoringChats.length,
            chats: tutoringChats,
            unread: tutoringChats.filter((chat) => chat.unread_messages > 0).length
        },
        chats: {
            length: chats.length,
            chats: chats,
            unread: chats.filter((chat) => chat.unread_messages > 0).length
        },
        isEmpty: tutoringChats.length === 0 && chats.length === 0,
        wwwroot: M.cfg.wwwroot
    }).then((html) => {
        $(SELECTORS.TARGET).html(html);
        status.emptyActiveMessages();
        SELECTORS.OPEN_CHAT_ID = 0;
        return;
    }).fail(Notification.exception);
}

/**
 * Open chat
 * @param {number} id
 * @param {boolean} hideBack
 * Render chat
 */
export async function renderSingleChat(id, hideBack = false) {
    const { messages } = await chatsGetMessage({ chatid: id });
    SELECTORS.OPEN_CHAT_ID = id;
    Template.render(SELECTORS.TEMPLATES.SINGLE_CHAT, {
        hideBack
    }).then((html) => {
        $(SELECTORS.TARGET).html(html);
        handlerMessages(messages);
        status.activeMessages = messages;
        return;
    }).fail(Notification.exception);
}

/**
 * Open chat
 * @param {number} id
 * @param {boolean} hideBack
 * Render chat
 */
export async function renderChat(id, hideBack = false) {
    const { messages, chatroom } = await chatsGetMessage({ chatid: id });
    SELECTORS.OPEN_CHAT_ID = id;
    Template.render(SELECTORS.TEMPLATES.CHAT, {
        hideBack, chatroom
    }).then((html) => {
        $(SELECTORS.TARGET).html(html);
        handlerMessages(messages);
        status.activeMessages = messages;
        return;
    }).fail(Notification.exception);
    await markMessagesAsRead({ chatid: id });
}


/**
 * Render messages in chat
 * @param {Array} messages
 */
export async function handlerMessages(messages) {
    let html = '';
    const promises = messages.map((msg) => {
        const { message, timecreated, is_mine, userfullname, userpicture} = msg;
        return renderMessage(message, timecreated, is_mine, userfullname, userpicture);
    });
    try {
        html = (await Promise.all(promises)).join('');
        $(SELECTORS.CONTAINERS.MESSAGES).html(html);
    } catch (error) {
        Notification.exception(error);
    }
}
/**
 * Reload messages
 */
export async function reloaderMessages() {
    if (SELECTORS.OPEN_CHAT_ID) {
        const { messages } = await chatsGetMessage({ chatid: SELECTORS.OPEN_CHAT_ID });
        handlerNewOtherMessage(messages);
        return;
    }
}

/**
 * Handler new other message
 * @param {object} messages
 */
export async function handlerNewOtherMessage(messages) {
    const newMessages = findDefferencies(messages, status.activeMessages);
    const promises = newMessages.map((msg) => {
        const { message, timecreated, is_mine, userfullname, userpicture } = msg;
        status.activeMessages.push(msg);
        return renderMessage(message, timecreated, is_mine, userfullname, userpicture);
    });
    if (newMessages.length > 0) {
        await markMessagesAsRead({ chatid: SELECTORS.OPEN_CHAT_ID, messageids: newMessages.map((msg) => msg.id) });
    }
    try {
        const html = (await Promise.all(promises)).join('');
        $(SELECTORS.CONTAINERS.MESSAGES).append(html);
    } catch (error) {
        Notification.exception(error);
    }
}

/**
 * Check if two objects are equals
 * @param {Array} arr1
 * @param {Array} arr2
 * @returns {Array}
 */
function findDefferencies(arr1, arr2) {
    return arr1.filter(objeto1 => {
        return !arr2.some(objeto2 => areEqualsByid(objeto1, objeto2));
    });
}

/**
 * Check if two objects are equals
 * @param {object} objeto1
 * @param {object} objeto2
 * @returns {boolean}
 */
function areEqualsByid(objeto1, objeto2) {
    return objeto1.message === objeto2.message && objeto1.timecreated === objeto2.timecreated;
}

/**
 * Render my message
 * @param {string} text
 * @param {string} time
 * @param {boolean} mine
 * @param {string} userfullname
 * @param {string} userpicture
 * @returns {Promise}
 */
export async function renderMessage(text, time, mine, userfullname = '', userpicture = '') {
    const TEMPLATE = mine ? SELECTORS.TEMPLATES.MY_MESSAGE : SELECTORS.TEMPLATES.OTHER_MESSAGE;
    const timeInMilliseconds = time * 1000;
    let dateString = '';
    if (timeInMilliseconds < (Date.now() - (86400000))) {
        dateString = new Date(timeInMilliseconds).toLocaleDateString();
    }
    const timeString =  new Date(time*1000).toLocaleTimeString([], { hour12: false, hour: '2-digit', minute: '2-digit' });
    const dateTimeString = dateString ? `${dateString} ${timeString}` : timeString;
    return Template.render(TEMPLATE, {text, time: dateTimeString, userfullname, userpicture});
}

/**
 * Handle send message
 * @returns {Promise}
 */
export async function handleSendMessage() {
    const message = $(SELECTORS.INPUTS.CHAT_REPLY).val().trim();
    chatsSendMessage({
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
    const date = Math.floor(Date.now() / 1000);
    handlerNewOtherMessage([{message, timecreated: date, is_mine: true}]);
}

/**
 * Open chat from experience
 * @param {int} experienceid
 * @param {boolean} single
 */
export async function openChatFromExperience(experienceid, single=true) {
    const {chatrooms} = await chatsGetRooms({experienceid});
    if (chatrooms.length === 0) {
        return;
    }
    // eslint-disable-next-line @babel/no-unused-expressions
    single ? renderSingleChat(chatrooms[0].id, true) : renderChat(experienceid);
}

/**
 * Render menu tutor
 * @returns {Promise}
 */
export async function renderMenuTutor() {
    Template.render(SELECTORS.TEMPLATES.MENU_MENTOR, {}).then((html) => {
        $(SELECTORS.TARGET).html(html);
        return;
    }).fail(Notification.exception);
}