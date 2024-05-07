import $ from 'jquery';
import { SELECTORS, deleteRelatedContext } from './main';
import { showChangeStatusModal, toggleExperienceStatus } from './modals';
import { displaylinkResourcesModal, displaylinkCoursesModal } from './modals';
import { loadMentors } from '../../mentors/pagination';

export const setEventListeners = () => {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();

    $(document).on('click', `${SELECTORS.BUTTONS.block}, ${SELECTORS.BUTTONS.unblock}`, () => {
        showChangeStatusModal(experienceid);
    });

    $(document).on('click', SELECTORS.BUTTONS.confirmBlockModal, () => {
        toggleExperienceStatus(experienceid);
    });

    $(document).on('click', SELECTORS.BUTTONS.addResourceBtn, () => {
        displaylinkResourcesModal(true);
    });

    $(document).on('click', SELECTORS.BUTTONS.addCasesBtn, () => {
        displaylinkCoursesModal();
    });

    $(document).on('click', SELECTORS.BUTTONS.removeContextButton, (event) => {
        deleteRelatedContext(event.currentTarget.dataset.contextid);
    });

    $(document).on('click', SELECTORS.BUTTONS.loadMoreButton, (numLoaded, numToLoad) => {
        loadMentors(numLoaded, numToLoad);
    });
};