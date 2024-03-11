import $ from 'jquery';
import Templates from 'core/templates';
import Notification from 'core/notification';
import {sectionTextUpsert} from 'local_dta/repositories/ourcasesRepository';


/**
 * Add a new text section to the page.
 * @return {void}
 */
function addTextSection() {
    Templates.render('local_dta/ourcases/section-text', {id: 0, title: null}).then((html) => {
        return $('#sections').append(html);
    }).fail(Notification.exception);
}


/**
 * Upsert a text section.
 * @param {object} args - The arguments for the function.
 */
function upsertTextSection(args) {
    sectionTextUpsert(args).then((response) => {
        if (response.warnings.length) {
           return Notification.alert(response.warnings[0].message);
        }
        return Notification.success('Section saved');
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
}

/**
 * Initialize the module.
 * @return {void}
 */
export const init = () => {
    setEventListeners();
};
