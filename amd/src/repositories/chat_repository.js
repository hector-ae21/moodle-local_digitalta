import Ajax from 'core/ajax';

/**
 * Get chat rooms.
 *
 * Valid args are:
 * experienceid (int) - The experience id (optional)
 * @method getChatRooms
 * @param {Object} args
 * @return {Promise}
 */
export const getChatRooms = args => {
    const request = {
        methodname: 'local_dta_get_chat_rooms',
        args
    };
    return Ajax.call([request])[0];
};


/**
 * Send chat message
 *
 * Valid args are:
 * chatid (int) - The chat id
 * message (string) - The message
 * userid (int) - The user id
 * @method sendMessage
 * @param {Object} args
 * @return {Promise}
 */
export const sendMessage = args => {
    const request = {
        methodname: 'local_dta_add_message',
        args
    };
    return Ajax.call([request])[0];
};


/**
 * Get Messages
 *
 * Valid args are:
 * chatid (int) - The chat id
 * userid (int) - The user id (optional)
 * @method getMessages
 * @param {Object} args
 * @return {Promise}
 */
export const getMessages = args => {
    const request = {
        methodname: 'local_dta_get_messages',
        args
    };
    return Ajax.call([request])[0];
};
