import $ from "jquery";
import {uploadFile} from "../repositories/files_repository";

export const saveFiles = (containerId, fileinputId, fileid, filearea, contextid = 1) => {
  const fileContainer = $(`#${containerId} `);
  const draftid = fileContainer.find(`#${fileinputId}`).attr("Value");

  uploadFile({
    draftid,
    fileid,
    filearea,
    contextid,
  })
    .then((response) => {
      // eslint-disable-next-line no-console
      console.log(response);
    })
    .catch((error) => {
      // eslint-disable-next-line no-console
      console.error(error);
    });
};

export const init = (containerId) => {
  $(`#${containerId}`).html(M.custom.filemanager.html);
};
