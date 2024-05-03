import ModalFactory from "core/modal_factory";
import addResourceModal from "local_dta/resources/add_resource_modal";
import { displaylinkResourcesModal } from "local_dta/myexperience/view/modals";
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
    // theme: form.querySelector('select[name="themeSelect"] option:checked').textContent,
    // tags: Array.from(form.querySelector('select[name="tagSelect"]').selectedOptions).map(option => option.value),
    type: form.querySelector('select[name="fileTypeSelect"]').value,
    lang: form.querySelector('select[name="languageSelect"]').value,
  };

  try {
    const response = await resourcesUpsert(formData);
    // eslint-disable-next-line no-console
    console.log(response);
  } catch (error) {
    Notification.exception(error);
  }
};

const displayDialogue = async (change) => {
  const themes = [];

  const modal = await ModalFactory.create({
    type: addResourceModal.TYPE,
    templateContext: { elementid_: Date.now(), themes: themes, change: change },
    large: true,
  });
  modal.show();
  const $root = modal.getRoot();
  if (change) {
    const changeElement = $root.find("#changeToImportResource").get(0);
    if (changeElement) {
      changeElement.onclick = () => {
        displaylinkResourcesModal();
        modal.hide();
      };
    }
  }

  $root.on(ModalEvents.save, (event, modal) => {
    handleDialogueSubmission(event, modal);
  });
};

export const init = (change) => {
  if (!change) {
    change = false;
  }
  document.getElementById("addResourceButton").addEventListener("click", () => displayDialogue(change));
};
