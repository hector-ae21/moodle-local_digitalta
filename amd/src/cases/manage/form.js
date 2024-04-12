import $ from 'jquery';
import Templates from 'core/templates';
import Notification from 'core/notification';
import {
    sectionTextUpsert,
    sectionTextDelete,
    ourcaseEdit
} from 'local_dta/repositories/ourcases_repository';
import ModalFactory from 'core/modal_factory';
import {get_string} from 'core/str';
import setEventListeners from './listeners';
import {createTinyMCE,
    getTinyMCEContent
} from 'local_dta/tiny/manage';
import {autocompleteTags} from "local_dta/tags/autocomplete";

let sectionTextModal;
let urlView = null;

/**
 * Add a new text section to the page.
 * @return {void}
 */
export function addTextSection() {
    Templates.render('local_dta/cases/section-text-edit', {
        id: new Date().getTime(),
        description: null,
        exist: true
    }).then((html) => {
        $('#sections-body').append(html);
        createTinyMCE($('.card-body:has(textarea)').last().find('textarea').attr('id'));
        return;
    }).fail(Notification.exception);
}


/**
 * Change the section to edit mode.
 * @param {boolean} toView - Whether to change to view mode.
 * @param {number} id - The id of the section to change.
 * @return {void}
 */
export function changeSectionToEdit(toView = false, id) {
    return new Promise((resolve, reject) => {
        const description = toView ? $(`#content_${id}`).html() : $(`#content_${id}`).val();
        const template = toView ? 'local_dta/cases/section-text-edit' : 'local_dta/cases/section-text-view';
        Templates.render(template, {id, description})
            .then((html) => {
                $(`#section_${id}`).replaceWith(html);
                return resolve();
            })
            .fail((error) => {
                reject(error);
                Notification.exception(error);
            });
    });
}


/**
 * Change the section to edit mode.
 * @param {number} id - The id of the section to change.
 * @param {number} toId - The id of the section to change to.
 * @return {void}
 */
function changeSectionToNewId(id, toId) {
    const description = $(`#content_${id}`).val();
    Templates.render("local_dta/cases/section-text-view",
        {id: toId, description, exist: true}).then((html) => {
           return $(`#section_${id}`).replaceWith(html);
    }).fail(Notification.exception);
}


/**
 * Set event listeners for the module.
 * @param {number} id - The id of the section to remove.
 * @return {void}
 *
 */
export function upsertSection(id) {
    const sectionid = $(`#not_exist_${id}`).val() ? null : $(`#sectionid_${id}`).val();
    const ourcaseid = $('#ourcases-id').val();
    const title = null;
    const text = $(`#content_${id}`).val();
    const sequence = -1;

    return sectionTextUpsert({ourcaseid, sectionid, title, text, sequence})
        .then((data) => {
            if (data && data.result) {
                changeSectionToNewId(id, data.sectionid);
                return data;
            } else {
                throw new Error('No se pudo realizar la actualización de la sección.');
            }
        })
        .fail(() => {
            throw new Error('Hubo un error al realizar la solicitud: ');
        });
}

/**
 * Set event listeners for the module.
 * @return {Promise<boolean>} - Resolves to true if the operation was successful, otherwise resolves to false.
 */
function upsertHeaderSection() {
    return new Promise((resolve, reject) => {
        const sectionid = $('#section-header-id').val();
        const ourcaseid = $('#ourcases-id').val();
        const title = $('#section-header-title').val();
        const text = getTinyMCEContent('section-header-description');
        const sequence = 0;

        sectionTextUpsert({ourcaseid, sectionid, title, text, sequence})
            .then((data) => {
                if (data && data.result) {
                    return resolve(true);
                } else {
                    return resolve(false);
                }
            })
            .fail((error) => {
                reject(error);
                Notification.exception(error);
            });
    });
}


/**
 * Remove a section from the page.
 * @param {number} sectionid - The id of the section to remove.
 * @return {void}
 */
export function removeSection(sectionid) {
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
export async function showDeleteSectionModal(sectionid) {
    sectionTextModal = await ModalFactory.create({
        title: get_string("ourcases_section_text_delete_modal_title", "local_dta"),
        body: Templates.render('local_dta/cases/manage/section-text-modal', {modalDeleteId: sectionid}),
    });
    sectionTextModal.show().then(() => {
        $("#modal_delete_id").val(sectionid);
        return;
    }).fail(Notification.exception);

}

/**
 * Show save case modal
 * @return {void}
 */
export async function showSaveCase() {
    const saveModal = await ModalFactory.create({
        title: get_string("ourcases_modal_save_title", "local_dta"),
        body: Templates.render('local_dta/cases/manage/save-modal', {})
    });
    await saveModal.show();
}


/**
 * Delete text section
 * @return {void}
 */
export function deleteSection() {
    const sectionid = $("#modal_delete_id").val();
    const ourcaseid = $('#ourcases-id').val();

    sectionTextDelete({ourcaseid, sectionid}).then((data) => {
        if (data.result) {
            sectionTextModal.hide();
            sectionTextModal = null;
            $(`#section_${sectionid}`).remove();
        }
        return;
    }).fail(Notification.exception);
}

/**
 * Edit a case.
 * @return {void}
 */
export async function changeStatusToComplete() {
    const ourcaseid = $('#ourcases-id').val();
    const status = 1;
    const tags = $("#autocomplete_tags").val();
    await upsertHeaderSection();
    ourcaseEdit({ourcaseid, status, tags}).then((data) => {
        if (data.result) {
            window.location.href = urlView;
        }
        return;
    }).fail(Notification.exception);
}

/**
 * Initialize the module.
 * @param {string} dataUrlView - The url to redirect after save the case.
 * @return {void}
 */
export const init = async(dataUrlView) => {
    setEventListeners();
    autocompleteTags("#autocomplete_tags");
    urlView = dataUrlView;
    createTinyMCE('section-header-description');
};