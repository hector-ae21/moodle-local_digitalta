import $ from 'jquery';
import ModalFactory from 'core/modal_factory';
import {get_string} from 'core/str';
import Templates from 'core/templates';

let changeStatusModal = null;


/**
 * Show the delete section modal
 * @param {number} experienceid
 * @return {void}
 */
async function showChangeStatusModal(experienceid) {
    changeStatusModal = await ModalFactory.create({
        title: get_string("ourcases_section_text_delete_modal_title", "local_dta"),
        body: Templates.render('local_dta/experiences/view/open-close-modal', {experienceid}),
    });
    changeStatusModal.show();
}

export const init = () => {
    $(document).on('click', '#add-section', () => {
        const experienceid = $(this).data('id');
        showChangeStatusModal(experienceid);
    });
};