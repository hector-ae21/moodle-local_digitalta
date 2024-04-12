import $ from "jquery";
import Notification from "core/notification";
import {createTinyMCE, getTinyMCEContent} from './../../tiny/manage';
import {setEventListeners} from "./listeners";
import {activateStep} from "./steps";
import {experienceUpsert} from "local_dta/repositories/experience_repository";
import {sectionTextUpsert} from "local_dta/repositories/reflection_repository";
import {autocompleteTags} from "local_dta/tags/autocomplete";
import { saveFiles } from "../../files/filemanager";


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

/**
 * Save the text section.
 * @param {object} btn - The data to save.
 * @param {number} step - The step to activate.
 * @return {void}
 */
export function saveTextSection(btn, step) {
  const data = btn.data();
  const {target, group} = data;
  const reflectionid = $("#reflectionid").val();
  const content = getTinyMCEContent(target);

  sectionTextUpsert({ reflectionid, group, content})
    .then(() => {
      Notification.addNotification({
        message: "Section saved successfully.",
        type: "success",
      });
      activateStep(step + 1);
      return;
    })
    .fail(Notification.exception);
}

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
    experienceProblem = window.tinyMCE.get("experience_problem").getContent(),
    tags = $("#autocomplete_tags").val();

    try {
      experienceUpsert({
        id: 0,
        title: experienceTitle,
        description: experienceIntroduction,
        context: experienceProblem,
        lang: experienceLang,
        visible: experienceVisibility,
        tags
      }).then((response) => {
        saveFiles("featurePicture", "fileManager", response.experienceid, "experience_picture");
        Notification.addNotification({
          message: "Experience saved successfully.",
          type: "success",
        });
        activateStep(2);
        $("#reflectionid").val(response.reflectionid);
        return;
      }).fail(Notification.exception);
    } catch (error) {
      Notification.exception(error);
    }
}


export const init = () => {
  setDefaultTinyMCE();
  autocompleteTags("#autocomplete_tags");
  activateStep();
  setEventListeners();
};
