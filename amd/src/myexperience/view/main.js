import $ from 'jquery';
import Notification from "core/notification";
import ModalFactory from 'core/modal_factory';
import Templates from 'core/templates';
import {get_string} from 'core/str';
import {toggleStatus} from 'local_dta/repositories/experience_repository';
import { displaylinkResourcesModal, displaylinkCoursesModal } from './modals';


let changeStatusModal = null;

// Selectors
export const SELECTORS = {
    BUTTONS: {
        block: '#block-experience-button',
        unblock: '#open-experience-button',
        confirmBlockModal: '#confirm-block-experience-button',
        addResourceBtn: '#add-resource-button',
        addCasesBtn: "#add-cases-button"
    },
    INPUTS: {
        experienceid: '#experience-id',
    }
};

/**
 * Show the delete section modal
 * @param {int} experienceid
 * @return {void}
 */
async function showChangeStatusModal(experienceid) {
    changeStatusModal = await ModalFactory.create({
        title: get_string("experience_view_block_modal_title", "local_dta"),
        body: Templates.render('local_dta/experiences/view/open-close-modal', {experienceid}),
    });
    changeStatusModal.show();
}

/**
 * Toggle the status of an experience
 * @param {int} experienceid
 * @return {void}
 */
async function toggleExperienceStatus(experienceid) {
    try {
        await toggleStatus(experienceid);
        changeStatusModal.hide();
        window.location.reload();
        return;
    } catch (error) {
        Notification.exception(error);
    }
}

/**
 * Set event listeners
 * @return {void}
 */
function setEventListeners() {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();

    $(document).on('click', `${SELECTORS.BUTTONS.block}, ${SELECTORS.BUTTONS.unblock}`, () => {
        showChangeStatusModal(experienceid);
    });

    $(document).on('click', SELECTORS.BUTTONS.confirmBlockModal, () => {
        toggleExperienceStatus(experienceid);
    });

    $(document).on('click', SELECTORS.BUTTONS.addResourceBtn, () => {
        displaylinkResourcesModal();
    });

    $(document).on('click', SELECTORS.BUTTONS.addCasesBtn, () => {
        displaylinkCoursesModal();
    });

}

export const init = () => {
    setEventListeners();
};