import Ajax from 'core/ajax';

/**
 * Get chat rooms.
 *
 * Valid args are:
 * None
 * @method getChatRooms
 * @return {Promise}
 */
export const getChatRooms = () => {
    const request = {
        methodname: 'local_dta_get_chat_rooms',
        args: {}
    };
    return Ajax.call([request])[0];
};
