import $ from 'jquery';
import {loadMore, sendMentorRequest } from './main';
import { SELECTORS } from './selectors';


export const setEventListeners = () => {
    $(document).on('click', SELECTORS.BUTTONS.loadMoreButton, function() {
        loadMore($(SELECTORS.BUTTONS.loadMoreButton).val());
    });

    $(SELECTORS.BUTTONS.ADDREQUEST).on('click', function() {
        const id = $(this).closest('.mentor').data('id');
        sendMentorRequest(id);
    });
};