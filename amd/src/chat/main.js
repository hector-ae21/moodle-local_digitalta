import $ from 'jquery';
import Template from 'core/templates';
import Notification from 'core/notification';

/**
 * Create a chat in the target
 * @param {string} target
 */
export default function createChatInTarget(target) {
    Template.render('local_dta/test/menu_chat/index', {target}).then((html) => {
        $(target).append(html);
        return;
    }).fail(Notification.exception);
}

const 