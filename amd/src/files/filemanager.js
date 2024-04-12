import $ from "jquery";
import { uploadFile } from "../repositories/files_repository";
import Notification from "core/notification";

/**
 * Save files to the server.
 * @param {string} containerId - The id of the container.
 * @param {string} fileinputId - The id of the file input.
 * @param {string} fileid - The id of the file.
 * @param {string} filearea - The area of the file.
 * @param {number} contextid - The context id.
 * @return {Promise} Resolve with warnings.
 */
export const saveFiles = (containerId, fileinputId, fileid, filearea, contextid = 1) => {
  const fileContainer = $(`#${containerId} `);
  const draftid = fileContainer.find(`#${fileinputId}`).attr("Value");

  return uploadFile({
    draftid,
    fileid,
    filearea,
    contextid,
  })
    .then((response) => {
      return response;
    })
    .fail(Notification.exception);
};

export const init = (containerId) => {
  $(`#${containerId}`).html(M.custom.filemanager.html);
};
