import ModalFactory from "core/modal_factory";
import addResourceModal from "local_dta/resources/add_resource_modal";
import { displaylinkResourcesModal } from "local_dta/experiences/modals";
import ModalEvents from "core/modal_events";
import { getList } from "core/normalise";
import { resourcesUpsert } from "local_dta/repositories/resources_repository";
import Notification from "core/notification";

/**
 * Handle the submission of the dialogue.
 *
 * @param {Event} event
 * @param {Modal} modal
 */
const handleDialogueSubmission = async (event, modal) => {
    const form = getList(modal.getRoot())[0].querySelector("form");

    if (!form) {
        return;
    }
    const formData = {
        id: 0,
        name: form.querySelector('input[name="nameInput"]').value,
        path: form.querySelector('input[name="urlInput"]').value,
        description: form.querySelector('textarea[name="descriptionInput"]').value,
        themes: Array.from(
            form.querySelectorAll('select[name="themeSelect"] option:checked'),
            option => option.value),
        tags: Array.from(
            form.querySelectorAll('select[name="tagSelect"] option:checked'),
            option => option.value),
        type: form.querySelector('select[name="typeSelect"]').value,
        format: form.querySelector('input[name="formatInput"]').value,
        lang: form.querySelector('select[name="languageSelect"]').value,
    };

    try {
        await resourcesUpsert(formData);
    } catch (error) {
        Notification.exception(error);
    }
};

/**
 * Display the dialogue.
 *
 * @param {Object} options
 */
export const displayDialogue = async (options) => {
    const languages = [
        { value: 'es', text: 'Spanish' },
        { value: 'en', text: 'English' }
    ];

    const modal = await ModalFactory.create({
        type: addResourceModal.TYPE,
        templateContext: {
            elementid_: Date.now(),
            themes: options.themes,
            tags: options.tags,
            types: options.types,
            format_input_id: options.format_id,
            languages: languages,
            change: options.change
        },
        large: true,
    });
    modal.show();
    const $root = modal.getRoot();
    if (options.change) {
        const changeElement = $root.find("#changeToImportResource").get(0);
        if (changeElement) {
            changeElement.onclick = () => {
                displaylinkResourcesModal(true);
                modal.hide();
            };
        }
    }

    $root.on(ModalEvents.save, (event, modal) => {
        handleDialogueSubmission(event, modal);
    });
};

/**
 * Initialise the module.
 *
 * @param {Object} options
 */
export const init = (options) => {
    if (typeof options.change === 'undefined') {
        options.change = false;
    }
    document.getElementById("addResourceButton").addEventListener("click", () => displayDialogue(options));
};
