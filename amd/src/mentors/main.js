import $ from "jquery";
import Templates from "core/templates";
import Notification from "core/notification";
import { setEventListeners } from "./listeners";
import { loadMentors } from "../repositories/pagination_repository";

// Selectors
export const SELECTORS = {
  BUTTONS: {
    loadMoreButton: "#load-more-button",
  },
  INPUTS: {
    numLoaded: "#numLoaded",
  },
};

/**
 * Reload mentors.
 * @param {number} offset The number of mentors loaded.
 * @param {number} chunkAmount The number of mentors to load.
 * @return {void}
 */
export function reloadMentors(offset, chunkAmount) {
    //eslint-disable-next-line no-console
    console.log("offset -> ", offset);
    //eslint-disable-next-line no-console
    console.log("chunkAmount -> ", chunkAmount);


  loadMentors({ numLoaded: offset, numToLoad: chunkAmount })
    .then((mentors) => {
      Templates.render("local_dta/mentors/mentors-list", {
        mentors: mentors.mentors,
        ismentorcardvertical: true,
      })
        .then((html, js) => {
          // Hacemos append en el último mentor, el parent es para indicar que se va a hacer append en el div
          Templates.appendNodeContents("#mentor-list", html, js);
          return;
        })
        .fail(Notification.exception);
    })
    .fail(Notification.exception);
}

/**
 * Load more mentors.
 *
 * @param {number} chunkAmount The number of mentors to load.
 * @return {void}
 */
export async function loadMore(chunkAmount) {
  // Traer más mentores y actualizar el número de mentores cargados
    let offset = parseInt($(SELECTORS.INPUTS.numLoaded).val()) + chunkAmount;
    $(SELECTORS.INPUTS.numLoaded).val(offset);

    reloadMentors(offset, chunkAmount);
}

export const init = () => {
  setEventListeners();
};
