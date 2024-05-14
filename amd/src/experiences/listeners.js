import $ from 'jquery';
import { SELECTORS, deleteRelatedContext } from './main';
import { showChangeStatusModal, toggleExperienceStatus } from './modals';
import { showManageModal, displaylinkResourcesModal, displaylinkCasesModal } from './modals';
import { getMentors } from './tutoring';

export const setEventListeners = () => {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();

    $(document).on('click', SELECTORS.BUTTONS.edit, () => {
        showManageModal(experienceid);
    });

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
        displaylinkCasesModal();
    });

    $(document).on('click', SELECTORS.BUTTONS.removeContextButton, (event) => {
        deleteRelatedContext(event.currentTarget.dataset.contextid);
    });

    $(document).on('input', SELECTORS.INPUTS.mentorsSearch, async (event) => {
        await getMentors(event.currentTarget.value);
    });
};