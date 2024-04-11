import { setupForElementId } from "editor_tiny/editor";
import {init} from "./cleaner";

/**
 * Create tinyMCE in an elementId.
 * @param {string} elementId - The id of the area to create tinyMCE in.
 * @return {void}
 */
export const createTinyMCE = (elementId) => {
  const options = window.dta_tiny_config;

  if (!options) {
    return;
  }
  setupForElementId({ elementId, options });
  init();
};
