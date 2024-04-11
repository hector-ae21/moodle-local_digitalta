import $ from "jquery";
import Templates from "core/templates";
import Notification from "core/notification";

/**
 * Set event listeners for the module.
 * @return {void}
 *
 */
export function upsertHeaderSection() {
    const sectionid = $('#section-header-id').val();
    const ourcaseid = $('#ourcases-id').val();
    const title = $('#section-header-title').val();
    const text = getTinyMCEContent('section-header-description');
    const sequence = 0;
    sectionTextUpsert({ourcaseid, sectionid, title, text, sequence});
    changeSectionHeaderToEdit();
    removeTinyMCEFromArea('section-header-description');
}

/**
 * Change the section to edit mode.
 * @param {boolean} toView - Whether to change to view mode.
 * @return {void}
 * */
function changeSectionHeaderToEdit(toView = false) {
    const id = $('#section-header-id').val();
    const title = $('#section-header-title').val();
    const description = toView ? $('#section-header-description').val() : getTinyMCEContent('section-header-description');
    const template = toView ? 'local_dta/cases/section-header-edit' : 'local_dta/cases/section-header-view';
    Templates.render(template,
     {sectionheader: {id, title, description}}).then((html) => {
        return $('#sections-header').html(html);
    }).fail(Notification.exception);
}