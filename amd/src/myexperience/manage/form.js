import $ from "jquery";
import Notification from "core/notification";
import {setupForElementId} from "editor_tiny/editor";
import {setEventListeners} from "./listeners";
import {activateStep} from "./steps";
import {experienceUpsert} from "./../../repositories/experience_repository";
import {autocompleteTags} from "./autocomplete";

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
  $(".editor").each(function() {
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
 * Collapse the add section menu.
 * @return {void}
 */
export function collapseAddSectionMenu() {
  const importerParent = $(this).closest("#importer");
  const importerDiv = importerParent.find("#import_div");
  const addIcon = $(this).find("i");
  if (importerParent.hasClass("collapsed")) {
    importerParent.removeClass("collapsed");
    importerDiv.css("display", "flex");
    addIcon.removeClass("fa fa-plus-circle").addClass("fa fa-minus-circle");
  } else {
    importerParent.addClass("collapsed");
    importerDiv.hide();
    addIcon.removeClass("fa fa-minus-circle").addClass("fa fa-plus-circle");
  }
}

// /**
//  * Save the text section.
//  * @param {object} btn - The data to save.
//  * @return {void}
//  */
// function saveTextSection(btn) {
//   const data = btn.data();
//   const {target, group, id} = data;
//   const reflectionid = $("#reflectionid").val();
//   const content = window.tinyMCE.get(target).getContent();
//   sectionTextUpsert({ reflectionid, group, content, id })
//     .then(() => {
//       Notification.addNotification({
//         message: "Section saved successfully.",
//         type: "success",
//       });
//       return;
//     })
//     .fail(Notification.exception);
// }

/**
 * Show save case modal
 * @return {void}
 */
// async function showImportCase() {
//   const cases = await getCases();
//   const saveModal = await ModalFactory.create({
//     type: ModalFactory.types.SAVE_CANCEL,
//     title: get_string("experience_reflection_import_cases_title", "local_dta"),
//     body: Templates.render("local_dta/experiences/manage/import-case-modal", { cases }),
//   });
//   saveModal.setSaveButtonText("Import");

//   saveModal.getRoot().on(ModalEvents.save, () => {
//     const caseIds = [];
//     const selectedCases = saveModal.getRoot().find("input:checked");
//     selectedCases.each(function () {
//       caseIds.push($(this).val());
//     });
//     saveModal.hide();
//     //eslint-disable-next-line no-console
//     console.log(caseIds);
//   }
//   );

//   saveModal.show();
// }

/**
 * Save the experience.
 * @return {void}
 * */
export async function saveExperience() {
  const experienceTitle = $("#experience_title").val(),
  experienceVisibility = $("#experience_visibility").val(),
    experienceLang = $("#experience_lang").val(),
    experienceIntroduction = window.tinyMCE.get("experience_introduction").getContent(),
    experienceProblem = window.tinyMCE.get("experience_problem").getContent();

    try {
      await experienceUpsert({
        id: 0,
        title: experienceTitle,
        description: experienceIntroduction,
        context: experienceProblem,
        lang: experienceLang,
        visible: experienceVisibility
      });
      Notification.addNotification({
        message: "Experience saved successfully.",
        type: "success",
      });
      activateStep(2);
      return;
    } catch (error) {
      Notification.exception(error);
    }
}


export const init = () => {
  setTinyConfig();
  setEventListeners();
  setDefaultTinyMCE();
  activateStep();
  autocompleteTags();
};
