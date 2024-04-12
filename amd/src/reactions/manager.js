import { setEventListeners } from "./listeners";
import { updateUI as updateCommentsUI } from "./comments";

export const init = () => {
  setEventListeners();
  updateCommentsUI();
};
