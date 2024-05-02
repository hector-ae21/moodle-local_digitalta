import Modal from 'core/modal';
import ModalRegistry from 'core/modal_registry';
import {getAllResources} from 'local_dta/repositories/resources_repository';
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';

const linkResourcesModal = class extends Modal {
    static TYPE = 'local_dta/linkResourcesModal';

    static TEMPLATE = 'local_dta/experiences/view/modal-resources';

    registerEventListeners() {
        // Call the parent registration.
        super.registerEventListeners();

        // Register to close on save/cancel.
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
    }
};

ModalRegistry.register(linkResourcesModal.TYPE, linkResourcesModal, linkResourcesModal.TEMPLATE);


const linkCasesModal = class extends Modal {
    static TYPE = 'local_dta/linkCasesModal';

    static TEMPLATE = 'local_dta/experiences/view/modal-cases';

    registerEventListeners() {
        // Call the parent registration.
        super.registerEventListeners();

        // Register to close on save/cancel.
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
    }
};

ModalRegistry.register(linkCasesModal.TYPE, linkCasesModal, linkCasesModal.TEMPLATE);


/**
 * Display a modal dialogue.
 * @param {Editor} editor
 */

export const displaylinkResourcesModal = async() => {
    const resources = await getAllResources();
    // eslint-disable-next-line no-console
    console.log(resources);

    const modal = await ModalFactory.create({
        type: linkResourcesModal.TYPE,
        templateContext: {},
        large: true,
    });
    modal.show();
    const $root = modal.getRoot();
    $root.on(ModalEvents.save, (event, modal) => {
        // eslint-disable-next-line no-console
        console.log(event, modal);
    });
};

