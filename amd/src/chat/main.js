import $ from 'jquery';
import Template from 'core/templates';
import Notification from 'core/notification';
import SELECTORS from './selectors';
import {getChatRooms} from 'local_dta/repositories/chat_repository';


let GLOBAL_TARGET = '';

/**
 * Create a chat in the target
 * @param {string} target
 */
export default function createChatInTarget(target) {
    GLOBAL_TARGET = target;
    initComponent();
    return;
}

const initComponent = () => {
    renderMenuChat();
};

/**
 * Render menu chat
 */
async function renderMenuChat() {
    const {chatrooms} = await getChatRooms();

    // eslint-disable-next-line no-console
    console.log(chatrooms);

    Template.render(SELECTORS.TEMPLATES.MENU_CHAT, {
        chatrooms
    }).then((html) => {
        $(GLOBAL_TARGET).append(html);
        return;
    }).fail(Notification.exception);
}