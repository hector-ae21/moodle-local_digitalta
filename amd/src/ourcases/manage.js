import $ from 'jquery';
import Templates from 'core/templates';
import Notification from 'core/notification';
import {sectionTextUpsert} from 'local_dta/repositories/ourcasesRepository';
import ModalFactory from 'core/modal_factory';
import {getString} from 'core/str';


/**
 * Add a new text section to the page.
 * @return {void}
 */
function addTextSection() {
    Templates.render('local_dta/ourcases/section-text-edit', {
        id: new Date().getTime(),
        description: null,
        exist: true
    }).then((html) => {
        return $('#sections-body').append(html);
    }).fail(Notification.exception);
}

/**
 * Upsert a text section.
 * @param {object} args - The arguments for the function.
 */
function upsertText(args) {
    sectionTextUpsert(args).then((data) => {
        return data;
    }).fail(Notification.exception);
}

/**
 * Change the section to edit mode.
 * @param {boolean} toView - Whether to change to view mode.
 * @return {void}
 * */
function changeSectionHeaderToEdit(toView = false) {
    const id = $('#section-header-id').val();
    const title = $('#section-header-title').val();
    const description = $('#section-header-description').val();
    const template = toView ? 'local_dta/ourcases/section-header-edit' : 'local_dta/ourcases/section-header-view';
    Templates.render(template,
     {sectionheader: {id, title, description}}).then((html) => {
        return $('#sections-header').html(html);
    }).fail(Notification.exception);
}


/**
 * Change the section to edit mode.
 * @param {boolean} toView - Whether to change to view mode.
 * @param {number} id - The id of the section to change.
 * @return {void}
 */
function changeSectionToEdit(toView = false, id) {
    const description = $(`#content_${id}`).val();
    const template = toView ? 'local_dta/ourcases/section-text-edit' : 'local_dta/ourcases/section-text-view';
    Templates.render(template,
     {id, description}).then((html) => {
        return $(`#section_${id}`).replaceWith(html);
    }).fail(Notification.exception);
}


/**
 * Set event listeners for the module.
 * @return {void}
 *
 */
function upsertHeaderSection() {
    const sectionid = $('#section-header-id').val();
    const ourcaseid = $('#ourcases-id').val();
    const title = $('#section-header-title').val();
    const text = $('#section-header-description').val();
    const sequence = 0;
    upsertText({ourcaseid, sectionid, title, text, sequence});
    changeSectionHeaderToEdit();
}

/**
 * Set event listeners for the module.
 * @param {number} id - The id of the section to remove.
 * @return {void}
 *
 */
function upsertSection(id) {
    const sectionid = $(`#not_exist_${id}`).val() ? null : $(`#sectionid_${id}`).val();
    const ourcaseid = $('#ourcases-id').val();
    const title = null;
    const text = $(`#content_${id}`).val();
    const sequence = -1;
    upsertText({ourcaseid, sectionid, title, text, sequence});
    changeSectionToEdit(false, id);
    $(`#not_exist_${id}`).val(false);
}

/**
 * Remove a section from the page.
 * @param {number} sectionid - The id of the section to remove.
 * @return {void}
 */
function removeSection(sectionid) {
    if ($(`#not_exist_${sectionid}`).val()) {
        if (sectionid) {
            $(`#section_${sectionid}`).remove();
        }
    } else {
        changeSectionToEdit(false, sectionid);
    }
}

/**
 * Show the delete section modal
 * @param {number} sectionid - The id of the section to remove.
 * @return {void}
 */
async function showDeleteSectionModal(sectionid) {
    const modal = await ModalFactory.create({
        title: getString("ourcases_section_text_delete_modal_title", "local_dta"),
        body: Templates.render('local_dta/ourcases/section-text-modal', {id: sectionid}),
    });
    modal.show();
}

/**
 * Set event listeners for the module.
 * @return {void}
 * */
function setEventListeners() {
    // Add a new text section
    $(document).on('click', '#add-section', () => {
        addTextSection();
    });
    // Edit the header
    $(document).on('click', '#header-edit-button', () => {
        upsertHeaderSection();
    });
    // Change the header section to edit mode
    $(document).on('click', '#header-to-edit-button', () => {
        changeSectionHeaderToEdit(true);
    });
    // Change the header section to view mode withour editing
    $(document).on('click', '#header-edit-close-button', () => {
        changeSectionHeaderToEdit();
    });
    // Remove a section
    $(document).on('click', '.section-close-button', function() {
        removeSection($(this).data('id'));
    });
    // Edit the section
    $(document).on('click', '.section-edit-button', function() {
        upsertSection($(this).data('id'));
    });
    // Change the section to edit mode
    $(document).on('click', '.section-to-edit-button', function() {
        changeSectionToEdit(true, $(this).data('id'));
    });
    // Delete a section
    $(document).on('click', '.section-delete-button', function() {
        showDeleteSectionModal($(this).data('id'));
    });
}


/**
 * Initialize the module.
 * @return {void}
 */
export const init = async() => {
    setEventListeners();
};