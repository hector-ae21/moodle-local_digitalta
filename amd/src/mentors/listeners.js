import $ from 'jquery';
import { loadMentors } from '../../mentors/pagination';
import { SELECTORS } from './main';

export const setEventListeners = () => {
    $(document).on('click', SELECTORS.BUTTONS.loadMoreButton, (numLoaded, numToLoad) => {
        loadMentors(numLoaded, numToLoad);
    });
};