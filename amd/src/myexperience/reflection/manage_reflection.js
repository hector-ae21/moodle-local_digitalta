import $ from "jquery";
import ModalFactory from "core/modal_factory";
import ModalEvents from 'core/modal_events';
import Templates from "core/templates";
import { get_string } from "core/str";
import { setupForElementId } from "editor_tiny/editor";
import { sectionTextUpsert } from "local_dta/repositories/reflection_repository";
import Notification from "core/notification";
import { getCases } from "../../repositories/reflection_repository";

let tinyConfig;

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
  $(".editor").each(function () {
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
  const { target, group, id } = data;
  const reflectionid = $("#reflectionid").val();
  const content = window.tinyMCE.get(target).getContent();
  sectionTextUpsert({ reflectionid, group, content, id })
    .then(() => {
      Notification.addNotification({
        message: "Section saved successfully.",
        type: "success",
      });
      return;
    })
    .fail(Notification.exception);
}

/**
 * Show save case modal
 * @return {void}
 */
async function showImportCase() {
  const cases = await getCases();
  const saveModal = await ModalFactory.create({
    type: ModalFactory.types.SAVE_CANCEL,
    title: get_string("experience_reflection_import_cases_title", "local_dta"),
    body: Templates.render("local_dta/experiences/manage/import-case-modal", { cases }),
  });
  saveModal.setSaveButtonText("Import");

  saveModal.getRoot().on(ModalEvents.save, () => {
    const caseIds = [];
    const selectedCases = saveModal.getRoot().find("input:checked");
    selectedCases.each(function () {
      caseIds.push($(this).val());
    });
    saveModal.hide();
    //eslint-disable-next-line no-console
    console.log(caseIds);
  }
  );

  saveModal.show();
}

/**
 * Activate the step.
 * @param {number} stepNum - The step number to activate.
 * @return {void}
 * */
function activateStep(stepNum = 1) {
  const steps = $("#stepbar").find("li");

  // Change the active step
  for (let i = 0; i < steps.length; i++) {
    if (i < stepNum) {
      $(steps[i]).addClass("active");
    } else {
      $(steps[i]).removeClass("active");
    }
  }

  // Change the active section
  switch (stepNum) {
    case 1:
      showSection("what");
      break;
    case 2:
      showSection("so_what");
      break;
    case 3:
      showSection("now_what");
      break;
  }
}

/**
 * Show the section.
 * @param {string} section - The section to show.
 * @return {void}
 * */
function showSection(section = "what") {
  const sections = $(".sections").find(".section");

  sections.each(function () {
    const sectionId = $(this).attr("id");
    if (sectionId === `section_${section}`) {
      $(this).addClass("active");
      $(this).addClass("d-flex").removeClass("d-none");
    } else {
      $(this).removeClass("active");
      $(this).addClass("d-none").removeClass("d-flex");
    }
  });
}

/**
 * Set event listeners for the module.
 * @return {void}
 * */
function setEventListeners() {
  // Save section
  $(document).on("click", ".submit", function () {
    saveTextSection($(this));
  });

  // Add-Section-Menu Collapse
  $(document).on("click", "#add_button", function () {
    const importerParent = $(this).closest("#importer");
    const importerDiv = importerParent.find("#import_div");
    const addIcon = $(this).find("i");
    // importerDiv.css('display', importerDiv.css('display') == 'flex' ? 'none' : 'flex');
    if (importerParent.hasClass("collapsed")) {
      importerParent.removeClass("collapsed");
      importerDiv.css("display", "flex");
      addIcon.removeClass("fa fa-plus-circle").addClass("fa fa-minus-circle");
    } else {
      importerParent.addClass("collapsed");
      importerDiv.hide();
      addIcon.removeClass("fa fa-minus-circle").addClass("fa fa-plus-circle");
    }
  });

  // Change Section
  $(document).on("click", "#btn_what", function () {
    activateStep();
  });

  $(document).on("click", "#btn_so_what", function () {
    activateStep(2);
  });

  $(document).on("click", "#btn_now_what", function () {
    activateStep(3);
  });

  // Import Buttons
  $(document).on("click", ".import_button", function () {
    const buttonId = $(this).attr("id");

    switch (buttonId) {
      case "import_cases":
        showImportCase();
        break;
      case "import_resources":
        //eslint-disable-next-line no-console
        console.log("Import Resources");
        break;
    }
  });

  // on LOAD - ready is deprecated
  $(function () {
    activateStep();
  });
}

export const init = () => {
  setEventListeners();
  setTinyConfig();
  setDefaultTinyMCE();
};
