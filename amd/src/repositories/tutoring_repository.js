import Ajax from 'core/ajax';

/**
 * Delete meeting by chatid
 *
 * Valid args are:
 * - chatid: Chat id to delete meeting from
 * @method deleteMeeting
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with themes.
 */
export const deleteMeeting = args => {
    const request = {
        methodname: 'local_dta_delete_meeting',
        args: args
    };
    return Ajax.call([request])[0];
};
