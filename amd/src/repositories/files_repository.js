import Ajax from "core/ajax";

/**
 * Get unused draft item id.
 * @method getUnusedDraftItemId
 * @param {Object} args Arguments send to the webservice.
 * @return {Promise} Resolve with warnings.
 */
export const uploadFile = args => {
  const request = {
    methodname: "local_dta_upload_file",
    args: args,
  };
  return Ajax.call([request])[0];
};
