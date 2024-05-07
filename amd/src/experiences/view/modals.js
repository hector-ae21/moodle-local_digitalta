import Modal from "core/modal";
import ModalRegistry from "core/modal_registry";
import { getAllResources } from "local_dta/repositories/resources_repository";
import ModalFactory from "core/modal_factory";
import ModalEvents from "core/modal_events";
import { upsertContext } from "local_dta/repositories/context_repository";
import $ from "jquery";
import { SELECTORS } from "./main";
import Notification from "core/notification";
import { getCases } from "local_dta/repositories/cases_repository";
import {displayDialogue} from "local_dta/resources/manage_resources";

const linkResourcesModal = class extends Modal {
  static TYPE = "local_dta/linkResourcesModal";

  static TEMPLATE = "local_dta/experiences/view/modal-resources";

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
  static TYPE = "local_dta/linkCasesModal";

  static TEMPLATE = "local_dta/experiences/view/modal-cases";

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
 * @param {boolean} change
 */
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

/**
 * Handle RESOURCE modal dialogue.
 */
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

/**
 * Display a modal dialogue.
 */
export const displaylinkCoursesModal = async () => {
  const { cases } = await getCases();

  const modal = await ModalFactory.create({
    type: linkCasesModal.TYPE,
    templateContext: { elementid_: Date.now(), cases },
    large: true,
  });
  modal.show();
  const $root = modal.getRoot();
  $root.on(ModalEvents.save, () => {
    handleCoursesModal();
  });
};

/**
 * Handle RESOURCE modal dialogue.
 */
const handleCoursesModal = () => {
  const experienceid = $(SELECTORS.INPUTS.experienceid).val();
  const seleccionados = [];
  $("#cases-group input[type='checkbox']:checked").each(function () {
    // Agregar el valor del checkbox seleccionado al array
    seleccionados.push($(this).val());
  });
  const contextid = [];
  // eslint-disable-next-line no-console
  console.log(seleccionados);
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
