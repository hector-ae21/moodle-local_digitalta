import Ajax from "core/ajax";

/**
 * Delete meeting by chatid
 *
 * Valid args are:
 * - chatid: Chat id to delete meeting from
 * @method deleteMeeting
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with boolean indicating if meeting was deleted.
 */
export const deleteMeeting = (args) => {
  const request = {
    methodname: "local_dta_delete_meeting",
    args: args,
  };
  return Ajax.call([request])[0];
};

/**
 * Get mentors by search text
 * @method searchMentors
 * @param {object} args Arguments send to the webservice.
 * @return {Promise} Resolve with mentors.
 */
export const searchMentors = async (args) => {
  const request = {
    methodname: "local_dta_get_mentors",
    args: args,
  };
  return await Ajax.call([request])[0];
};
