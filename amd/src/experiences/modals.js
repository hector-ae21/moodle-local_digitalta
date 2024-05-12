import { autocompleteThemes } from "local_dta/themes/autocomplete";
import { createTinyMCE, getTinyMCEContent } from "local_dta/tiny/manage";
import { displayDialogue } from "local_dta/resources/manage_resources";
import { experienceGet, experienceUpsert } from "local_dta/repositories/experiences_repository";
import { getAllResources } from "local_dta/repositories/resources_repository";
import { getCases } from "local_dta/repositories/cases_repository";
import { getLanguages } from "local_dta/repositories/languages_repository";
import { getList } from "core/normalise";
/* @EXPERIENCE_PICTURE TODO: Add picture to experience creation
import { prepareDraftHTML, saveFiles } from "local_dta/files/filemanager";
*/
import { SELECTORS } from "./main";
import { upsertContext } from "local_dta/repositories/context_repository";
import * as Cfg from "core/config";
import * as Str from "core/str";
import $ from "jquery";
import Modal from "core/modal";
import ModalEvents from "core/modal_events";
import ModalFactory from "core/modal_factory";
import ModalRegistry from "core/modal_registry";
import Notification from "core/notification";import { autocompleteTags } from "local_dta/tags/autocomplete";

const manageModal = class extends Modal {
    static TYPE = 'local_dta/manageModal';
    static TEMPLATE = 'local_dta/experiences/modals/modal-manage';
    registerEventListeners() {
        // Call the parent registration.
        super.registerEventListeners();
        // Register to close on save/cancel.
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
    }
};

ModalRegistry.register(manageModal.TYPE, manageModal, manageModal.TEMPLATE);

const linkResourcesModal = class extends Modal {
    static TYPE = "local_dta/linkResourcesModal";
    static TEMPLATE = "local_dta/experiences/modals/modal-import-resources";
    registerEventListeners() {
        super.registerEventListeners();
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
    }
};

ModalRegistry.register(linkResourcesModal.TYPE, linkResourcesModal, linkResourcesModal.TEMPLATE);

const linkCasesModal = class extends Modal {
    static TYPE = "local_dta/linkCasesModal";
    static TEMPLATE = "local_dta/experiences/modals/modal-import-cases";
    registerEventListeners() {
        super.registerEventListeners();
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
    }
};

ModalRegistry.register(linkCasesModal.TYPE, linkCasesModal, linkCasesModal.TEMPLATE);

export const showManageModal = async (experienceid) => {
    const string_keys = [
        { key: "teacheracademy:actions:share", component: "local_dta" },
        { key: "teacheracademy:actions:share:description", component: "local_dta" },
        { key: "teacheracademy:actions:share:title", component: "local_dta" },
        { key: "teacheracademy:actions:share:title:placeholder", component: "local_dta" },
        { key: "teacheracademy:actions:share:visibility", component: "local_dta" },
        { key: "teacheracademy:actions:share:language", component: "local_dta" },
        { key: "teacheracademy:actions:share:themes", component: "local_dta" },
        { key: "teacheracademy:actions:share:tags", component: "local_dta" },
        { key: "teacheracademy:actions:share:picture", component: "local_dta" },
        { key: "visibility:public", component: "local_dta" },
        { key: "visibility:private", component: "local_dta" }
    ];
    const strings = Str.get_strings(string_keys);
    Promise.all([strings])
        .then(([strings]) => displayManageModal(strings, experienceid));
};

export const displayManageModal = async (strings, experienceid) => {
    let experience = {};
    if (experienceid !== null) {
        await experienceGet(experienceid).then((response) => {
            experience = response.experience;
        });
    }
    let languages = await getLanguages({prioritizeInstalled: true});
    languages = languages.map((language) => {
        return {
            key: language.code,
            value: language.name,
            selected: language.code === experience.lang
        };
    });
    window.console.log(languages);
    let visibility = [
        { key: 1, value: strings[9] },
        { key: 0, value: strings[10] }
    ];
    visibility = visibility.map((item) => {
        return {
            key: item.key,
            value: item.value,
            selected: item.key === experience.visible
        };
    });
    const modal = await ModalFactory.create({
        type: manageModal.TYPE,
        templateContext: {
            modal: {
                title: strings[0],
                description: strings[1],
                sections: {
                    title: {
                        label: strings[2],
                        placeholder: strings[3]
                    },
                    visibility: {
                        label: strings[4]
                    },
                    language: {
                        label: strings[5]
                    },
                    themes: {
                        label: strings[6]
                    },
                    tags: {
                        label: strings[7]
                    },
                    picture: {
                        label: strings[8]
                    }
                },
                visibility: visibility,
                languages: languages
            },
            current: {
                id: experience.id ?? 0,
                title: experience.title ?? null,
                description: experience.description ?? null,
                visible: experience.visible ?? null,
                lang: experience.lang ?? null,
                tags: experience.tags ?? [],
                themes: experience.themes ?? []
            }
        },
        large: true,
    });
    modal.show();
    const $root = modal.getRoot();
    createTinyMCE('experience-add-description');
    autocompleteTags('#experience-add-tags');
    autocompleteThemes('#experience-add-themes');
    /* @EXPERIENCE_PICTURE TODO: Add picture to experience creation
    await prepareDraftHTML('experience_picture').then((response) => {
        document.querySelector('#experience-add-picture').innerHTML = response.html;
    });
    */
    $root.on(ModalEvents.save, (event, modal) => {
        event.preventDefault();
        const form = $root[0].querySelector('form');
        if (validateManageRequiredFields(form)) {
            handleManageModalSubmission(event, modal);
        }
    });
};

/**
 * Handle the submission of the modal.
 *
 * @param {Event} event
 * @param {Modal} modal
 */
const handleManageModalSubmission = async (event, modal) => {
    window.console.log("UHUHUHU");
    const form = getList(modal.getRoot())[0].querySelector("form");
    if (!form) {
        return;
    }
    const formData = {
        id: form.querySelector('input[name="experience-add-id"]').value,
        title: form.querySelector('input[name="experience-add-title"]').value,
        description: getTinyMCEContent('experience-add-description'),
        visible: form.querySelector('select[name="experience-add-visibility"]').value,
        lang: form.querySelector('select[name="experience-add-lang"]').value,
        themes: Array.from(
            form.querySelectorAll('select[name="experience-add-themes"] option:checked'),
            option => option.value),
        tags: Array.from(
            form.querySelectorAll('select[name="experience-add-tags"] option:checked'),
            option => option.value)
    };
    window.console.log(formData);
    try {
        const response = await experienceUpsert(formData);
        /* @EXPERIENCE_PICTURE TODO: Add picture to experience creation
        await saveFiles('experience-add-picture', 'fileManager', response.experienceid, 'experience_picture');
        */
        Notification.addNotification({
            message: "Experience saved successfully.",
            type: 'success'
        });
        location.href = Cfg.wwwroot + '/local/dta/pages/experiences/view.php?id=' + response.experienceid;
    } catch (error) {
        Notification.exception(error);
    }
};

/**
 * Validate required fields.
 *
 * @param {HTMLElement} form The form to validate.
 * @return {boolean} True if all required fields are filled.
 */
const validateManageRequiredFields = (form) => {
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
    for (const field of requiredFields) {
        if (!field.value) {
            field.focus();
            return false;
        }
    }
    return true;
};

export const displaylinkResourcesModal = async (change) => {
    const { resources } = await getAllResources();
    const modal = await ModalFactory.create({
        type: linkResourcesModal.TYPE,
        templateContext: { elementid_: Date.now(), resources, change: change},
        large: true,
    });
    modal.show();
    const $root = modal.getRoot();
    if (change) {
        const changeElement = $root.find("#changeToAddResource").get(0);
        if (changeElement) {
            changeElement.onclick = () => {
                displayDialogue(true);
                modal.hide();
            };
        }
    }
    $root.on(ModalEvents.save, () => {
        handleResourceModal();
    });
};

const handleResourceModal = () => {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();
    const seleccionados = [];
    $("#resources-group input[type='checkbox']:checked").each(function () {
        // Agregar el valor del checkbox seleccionado al array
        seleccionados.push($(this).val());
    });
    const contextid = [];
    seleccionados.forEach(async (resourceid) => {
        contextid.push(
            upsertContext({
                component: "experience",
                componentinstance: experienceid,
                modifier: "resource",
                modifierinstance: resourceid,
            })
        );
    });
    Promise.all(contextid)
        .then(() => {
            window.location.reload();
            return;
        })
        .catch((error) => {
            Notification.exception(error);
        });
};

export const displaylinkCasesModal = async () => {
    const { cases } = await getCases();

    const modal = await ModalFactory.create({
        type: linkCasesModal.TYPE,
        templateContext: { elementid_: Date.now(), cases },
        large: true,
    });
    modal.show();
    const $root = modal.getRoot();
    $root.on(ModalEvents.save, () => {
        handleCasesModal();
    });
};

const handleCasesModal = () => {
    const experienceid = $(SELECTORS.INPUTS.experienceid).val();
    const seleccionados = [];
    $("#cases-group input[type='checkbox']:checked").each(function () {
        seleccionados.push($(this).val());
    });
    const contextid = [];
    seleccionados.forEach(async (caseid) => {
        contextid.push(
            upsertContext({
                component: "experience",
                componentinstance: experienceid,
                modifier: "case",
                modifierinstance: caseid,
            })
        );
    });
    Promise.all(contextid)
        .then(() => {
            window.location.reload();
            return;
        })
        .catch((error) => {
            Notification.exception(error);
        });
};
