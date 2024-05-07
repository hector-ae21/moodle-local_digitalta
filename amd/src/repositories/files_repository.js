import Ajax from "core/ajax";

/**
 * Upload file from draft.
 *
 * Valid args are:
 * - draftid: The id of the draft.
 * - fileid: The id of the file.
 * - filearea: The file area.
 * - contextid: The context id.
 *
 * @method getUnusedDraftItemId
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const uploadFile = args => {
  const request = {
    methodname: "local_dta_upload_file_from_draft",
    args: args,
  };
  return Ajax.call([request])[0];
};
