import $ from "jquery";
import { uploadFile } from "../repositories/files_repository";

export const saveFiles = (containerId, fileinputId) => {
  const fileContainer = $(`#${containerId} `);
  const filename = fileContainer.find(`.fp-filename`).text();
  const itemid = fileContainer.find(`#${fileinputId}`).attr("Value");

  uploadFile({
    itemid, filename
  }).then((response) => {
    // eslint-disable-next-line no-console
    console.log(response);
  } );
};

export const init = (containerId) => {
  $(`#${containerId}`).html(M.custom.filemanager.html);
};

