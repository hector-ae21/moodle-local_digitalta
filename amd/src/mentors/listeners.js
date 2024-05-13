import $ from 'jquery';
import { SELECTORS, loadMore } from './main';


export const setEventListeners = () => {
    $(document).on('click', SELECTORS.BUTTONS.loadMoreButton, function() {
        const chunkAmount = parseInt($(SELECTORS.BUTTONS.loadMoreButton).val());

        loadMore(chunkAmount);
    });
};