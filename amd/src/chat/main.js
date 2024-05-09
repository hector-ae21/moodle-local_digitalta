import $ from 'jquery';
import Template from 'core/templates';
import Notification from 'core/notification';

/**
 * Create a chat in the target
 * @param {string} target
 */
export default function createChatInTarget(target) {
    getChatRooms();
    Template.render('local_dta/chat/main', {target}).then((html) => {
        $(target).append(html);
        return;
    }).fail(Notification.exception);
}

/**
 * Get chat rooms from the specified URL
 * @returns {Promise}
 */
const getChatRooms = () => {
    const url = 'http://localhost/moodle/local/dta/classes/chat/chat_ajax.php?action=get_chat_rooms';
    return fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // eslint-disable-next-line no-console
            console.log(response.json());
            return response.json();
        })
        .catch(error => {
            // eslint-disable-next-line no-console
            console.error('Error fetching chat rooms:', error);
        });
};