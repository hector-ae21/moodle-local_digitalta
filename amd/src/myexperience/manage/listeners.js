import $ from "jquery";
import {activateStep} from "./steps";
import {saveExperience} from "./form";

/**
 * Set event listeners for the module.
 * @return {void}
 * */
export function setEventListeners() {
    // Save section
    $(document).on("click", "#experience_submit", function() {
      saveExperience();
    });

    // Add-Section-Menu Collapse
    $(document).on("click", "#add_button", function() {
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
    // $(document).on("click", ".import_button", function () {
    //   const buttonId = $(this).attr("id");

    //   switch (buttonId) {
    //     case "import_cases":
    //       showImportCase();
    //       break;
    //     case "import_resources":
    //       //eslint-disable-next-line no-console
    //       console.log("Import Resources");
    //       break;
    //   }
    // });

  }