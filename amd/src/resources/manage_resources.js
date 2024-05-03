import ModalFactory from 'core/modal_factory';
import addResourceModal from 'local_dta/resources/add_resource_modal';
import ModalEvents from 'core/modal_events';
import {getList} from 'core/normalise';
import {resourcesUpsert} from 'local_dta/repositories/resources_repository';
import Notification from 'core/notification';

/**
 * Handle the submission of the dialogue.
 *
 * @param {Event} event
 * @param {Modal} modal
 */
const handleDialogueSubmission = async(event, modal) => {
  const form = getList(modal.getRoot())[0].querySelector('form');

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
      lang: form.querySelector('select[name="languageSelect"]').value
  };

  try {
      const response = await resourcesUpsert(formData);
      // eslint-disable-next-line no-console
      console.log(response);
  } catch (error) {
      Notification.exception(error);
  }

};

const displayDialogue = async() => {

  const themes = [
  ];

  const modal = await ModalFactory.create({
      type: addResourceModal.TYPE,
      templateContext: {elementid_:  Date.now(), themes: themes},
      large: true,
  });
  modal.show();
  const $root = modal.getRoot();
  $root.on(ModalEvents.save, (event, modal) => {
      handleDialogueSubmission(event, modal);
  });
};

export const init = () => {
  document.getElementById('addResourceButton').addEventListener('click', displayDialogue);
};

