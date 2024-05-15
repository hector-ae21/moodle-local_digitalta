import Notification from "core/notification";
import ModalFactory from 'core/modal_factory';
import Templates from 'core/templates';
import {get_string} from 'core/str';
import {toggleStatus} from 'local_dta/repositories/experiences_repository';
import {setEventListeners} from './listeners';
import {deleteContext} from 'local_dta/repositories/context_repository';

let changeStatusModal = null;

// Selectors
export const SELECTORS = {
    BUTTONS: {
        edit: '#edit-experience-button',
        block: '#block-experience-button',
        unblock: '#open-experience-button',
        confirmBlockModal: '#confirm-block-experience-button',
        addResourceBtn: '#add-resource-button',
        addCasesBtn: "#add-cases-button",
        removeContextButton: "#remove-context-button",
        sendMentorRequest: "#send-mentor-request",
    },
    INPUTS: {
        experienceid: '#experience-id',
        mentorsSearch: '#search-mentors-input',
    },
    SECTIONS : {
        searchMentorsResults: '#search-mentors-result',
    }
};

/**
 * Show the delete section modal
 * @param {int} experienceid
 * @return {void}
 */
export async function showChangeStatusModal(experienceid) {
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
export async function toggleExperienceStatus(experienceid) {
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
 * Display the link resources modal
 * @param {int} contextid  The context id.
 */
export async function deleteRelatedContext(contextid) {
    try {
        await deleteContext({id: contextid});
        window.location.reload();
    } catch (error) {
        Notification.exception(error);
    }
}

export const init = (userid = null) => {
    setEventListeners(userid);
};
