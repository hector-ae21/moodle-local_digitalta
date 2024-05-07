import Notification from "core/notification";
import { setEventListeners } from './listeners';
import { loadMentors } from './pagination';

// Selectors
export const SELECTORS = {
    BUTTONS: {
        loadMoreButton: "#load-more-button",
    },
    INPUTS: {
        numLoaded: '#num-loaded',
    }
};

/**
 * Load more mentors.
 *
 * @param {int} numLoaded The number of mentors loaded.
 * @param {int} numToLoad The number of mentors to load.
 * @return {void}
 */
export async function loadMore(numLoaded, numToLoad) {
    try {
        await loadMentors({numLoaded: numLoaded, numToLoad: numToLoad});
        window.location.reload();
    } catch (error) {
        Notification.exception(error);
    }
}

export const init = () => {
    setEventListeners();
};