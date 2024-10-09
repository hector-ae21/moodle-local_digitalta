/**
 * Main module for commmun functionality
 *
 * @module     local_digitalta/commun/main
 * @copyright  2024 ADSDR-FUNIBER Scepter Team
 */

import { setEventListeners } from "local_digitalta/commun/listeners";

export const init = () => {
  setEventListeners();
};
