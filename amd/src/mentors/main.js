import $ from "jquery";
import Templates from "core/templates";
import Notification from "core/notification";
import { setEventListeners } from "./listeners";
import { loadMentors } from "../repositories/pagination_repository";
import { SELECTORS } from "./selectors";

/**
 * Reload mentors.
 * @param {number} offset The number of mentors loaded.
 * @param {number} chunkAmount The number of mentors to load.
 * @return {void}
 */
export function reloadMentors(offset, chunkAmount) {
  loadMentors({ numLoaded: offset, numToLoad: chunkAmount })
    .then((mentors) => {
      Templates.render("local_dta/mentors/mentors-list", {
        mentors: mentors.mentors,
        ismentorcardvertical: true,
      })
        .then((html, js) => {
          Templates.appendNodeContents("#mentor-list", html, js);
          return;
        })
        .fail(Notification.exception);
        return;
    })
    .fail(Notification.exception);
}

export const sendMentorRequest = () => {
  // Enviar solicitud de mentoría
  Notification.success("Solicitud enviada correctamente");
}

/**
 * Load more mentors.
 *
 * @param {number} chunkAmount The number of mentors to load.
 * @return {void}
 */
export async function loadMore(chunkAmount) {
  // Traer más mentores y actualizar el número de mentores cargados
  let offset = parseInt($(SELECTORS.INPUTS.numLoaded).val()) + parseInt(chunkAmount);
  $(SELECTORS.INPUTS.numLoaded).val(offset);

  reloadMentors(offset, chunkAmount);
}

export const init = () => {
  setEventListeners();
};
