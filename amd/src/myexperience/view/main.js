import $ from 'jquery';
import ModalFactory from 'core/modal_factory';
import {get_string} from 'core/str';
import Templates from 'core/templates';

let changeStatusModal = null;

// Selectors
const SELECTORS = {
    BUTTONS: {
        block: '#block-experience-button',
        confirmBlock: '#confirm-block-experience-button',
    },
    INPUTS: {
        experienceid: '#experience-id',
    }
};

/**
 * Show the delete section modal
 * @param {number} experienceid
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
 * Set event listeners
 * @return {void}
 */
function setEventListeners() {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();

    $(document).on('click', SELECTORS.BUTTONS.block, () => {
        showChangeStatusModal(experienceid);
    });

    $(document).on('click', SELECTORS.BUTTONS.confirmBlock, () => {
        changeStatusModal.hide();
    });
}

export const init = () => {
    setEventListeners();
};