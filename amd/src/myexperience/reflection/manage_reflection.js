import $ from 'jquery';
import Templates from 'core/templates';
import ModalFactory from 'core/modal_factory';
import {get_string} from 'core/str';
import {setupForElementId} from 'editor_tiny/editor';
import {sectionTextUpsert} from 'local_dta/repositories/reflection_repository';
import Notification from 'core/notification';

let tinyConfig;

/**
 * Set the events for the module.
 * @return {void}
 */
function setEventListeners() {
  document.querySelectorAll('.section').forEach(section => {
    var collapseButton = section.querySelector('.header');

    if (collapseButton) {
      collapseButton.addEventListener('click', () => {
        var collapseIcon = collapseButton.querySelector('i');
        var sectionContent = section.querySelector('.questions');
        if (collapseButton.classList.contains('collapsed')) {
          collapseButton.classList.remove('collapsed');
          collapseIcon.classList.remove('fa-chevron-down');
          collapseIcon.classList.add('fa-chevron-right');
          sectionContent.style.display = 'none';
        } else {
          collapseButton.classList.add('collapsed');
          collapseIcon.classList.remove('fa-chevron-right');
          collapseIcon.classList.add('fa-chevron-down');
          sectionContent.style.display = 'flex';
        }
      });
    }
});

/**
 * Show save case modal
 * @return {void}
 */
async function showImportCase() {
  const saveModal = await ModalFactory.create({
      title: get_string("experience_reflection_import_cases_title", "local_dta"),
      body: Templates.render('local_dta/experiences/reflection/import-case-modal', {
      }),
  });
  saveModal.show();
}


  document.querySelectorAll('#importer').forEach(importer => {
    var addButton = importer.querySelector('#add_button');
    var importerDiv = importer.querySelector('#import_div');

    // Add Button Clic Event
    addButton.addEventListener('click', () => {
        importerDiv.style.display = importerDiv.style.display == 'flex' ? 'none' : 'flex';
    });

    importerDiv.querySelectorAll('.import_button').forEach(importButton => {
      importButton.addEventListener('click', () => {
        var buttonId = importButton.id;

        switch (buttonId) {
          case "tiny":
            //eslint-disable-next-line no-console
            console.log("Tiny");
            break;
          case "tiny_record":
            //eslint-disable-next-line no-console
            console.log("Tiny Record");
            break;
          case "import_cases":
            showImportCase();
            break;
          case "import_experiences":
            //eslint-disable-next-line no-console
            console.log("Import Experiences");
            break;
          case "import_tutor_conc":
            //eslint-disable-next-line no-console
            console.log("Import Tutor Conc");
            break;
          case "import_resources":
            //eslint-disable-next-line no-console
            console.log("Import Resources");
            break;
        }
      });
    });
  });

  $(document).on('click', '.submit', function() {
    saveTextSection($(this));
  });
}

/**
 * Create tinyMCE in an area.
 * @param {string} area - The id of the area to create tinyMCE in.
 * @return {void}
 */
function createTinyMCE(area) {
    setupForElementId({
        elementId: `${area}`,
        options: tinyConfig,
    });
}

/**
 * Set event listeners for the module.
 * @return {void}
 * */
function setDefaultTinyMCE() {
  $('.editor').each(function() {
    createTinyMCE(this.id);
  });
}

/**
 * Set the tinyMCE config.
 * @return {void}
 */
function setTinyConfig() {
  tinyConfig = window.dta_tiny_config;
}

/**
 * Save the text section.
 * @param {object} btn - The data to save.
 * @return {void}
 */
function saveTextSection(btn) {
  const data = btn.data();
  const {target, group, id} = data;
  const reflectionid = $('#reflectionid').val();
  const content = window.tinyMCE.get(target).getContent();
  sectionTextUpsert({reflectionid, group, content, id}).then(() => {

    Notification.addNotification({
        message: 'Section saved successfully.',
        type: 'success'
    });
    return;

  }).fail(Notification.exception);
}

export const init = () => {
    setEventListeners();
    setTinyConfig();
    setDefaultTinyMCE();
};