import $ from 'jquery';
import Templates from 'core/templates';
import Notification from 'core/notification';
import {sectionTextUpsert} from 'local_dta/repositories/ourcasesRepository';


/**
 * Add a new text section to the page.
 * @return {void}
 */
function addTextSection() {
    Templates.render('local_dta/ourcases/section-text-view', {id: 0, title: null}).then((html) => {
        return $('#sections-body').append(html);
    }).fail(Notification.exception);
}


/**
 * Upsert a text section.
 * @param {object} args - The arguments for the function.
 */
function upsertTextSection(args) {
    sectionTextUpsert(args).then(() => {
        return changeSectionHeaderToEdit();
    }).fail(Notification.exception);
}

/**
 * Change the section to edit mode.
 * @param {boolean} toView - Whether to change to view mode.
 * @return {void}
 * */
function changeSectionHeaderToEdit(toView = false) {
    const sectionid = $('#section-header-id').val();
    const title = $('#section-header-title').val();
    const description = $('#section-header-description').val();
    const template = toView ? 'local_dta/ourcases/section-header-edit' : 'local_dta/ourcases/section-header-view';
    Templates.render(template,
     {sectionheader: {id: sectionid, title, description}}).then((html) => {
        return $('#sections-header').html(html);
    }).fail(Notification.exception);
}


/**
 * Set event listeners for the module.
 * @return {void}
 *
 */
function upsertHeaderSection() {
    const ourcaseid = $('#ourcases-id').val();
    const sectionid = $('#section-header-id').val();
    const title = $('#section-header-title').val();
    const text = $('#section-header-description').val();
    const sequence = 0;
    upsertTextSection({ourcaseid, sectionid, title, text, sequence});
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
}


/**
 * Initialize the module.
 * @return {void}
 */
export const init = () => {
    setEventListeners();
};
