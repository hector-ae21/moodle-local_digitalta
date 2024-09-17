import { setupForElementId } from "editor_tiny/editor";
import { clean } from "local_digitalta/tiny/cleaner";
import Notification from "core/notification";
import { generateFileHash } from "../files/filemanager";
import { filesUploadFromDraft } from "../repositories/files_repository";

/**
 * Create tinyMCE in an elementId.
 * @param {string} elementId - The id of the area to create tinyMCE in.
 * @return {void}
 */
export const createTinyMCE = (elementId) => {
  const options = window.digitalta_tiny_config;
  if (!options) {
    return;
  }
  setupForElementId({ elementId, options })
    .then(() => {
      clean();
      return;
    })
    .catch((error) => {
      Notification.exception(error);
    });
};

export const processFiles = async (content) => {
  const baseUrl = M.cfg.wwwroot.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");

  const draftFilePattern = new RegExp(
    `(${baseUrl}\\/draftfile\\.php\\/5\\/user\\/draft\\/(\\d+)\\/[^"?]+)`,
    "g"
  );

  const matches = Array.from(content.matchAll(draftFilePattern));

  for (const match of matches) {
    const fullUrl = match[1];
    const draftid = match[2];
    const filename = match[2];
    const fileid = generateFileHash(filename);

    const response = await filesUploadFromDraft({
      draftid,
      fileid,
      filearea: "local_digitalta",
    });

    if (response.result) {
        content = content.replace(new RegExp(fullUrl, "g"), response.url);
      } else {
        throw new Error(response.error || "Error uploading file");
      }
  }

  return content;
};

/**
 * Remove tinyMCE from an area.
 * @param {string} area - The id of the area to remove tinyMCE from.
 * @return {void}
 */
export const removeTinyMCEFromArea = (area) => {
  window.tinyMCE.get(area).remove();
};

/**
 * Get the content of a tinyMCE area.
 * @param {string} area - The id of the area to get the content from.
 * @returns {string} The content of the tinyMCE area.
 */
export const getTinyMCEContent = (area) => {
  return window.tinyMCE.get(area).getContent();
};
