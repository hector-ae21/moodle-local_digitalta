import $ from "jquery";
import {activateStep} from "./steps";
import {saveExperience, collapseAddSectionMenu, handleNewTag} from "./form";

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
      collapseAddSectionMenu();
    });

    // Change Section
    $(document).on("click", "#btn_what", function() {
      activateStep();
    });

    $(document).on("click", "#btn_so_what", function() {
      activateStep(2);
    });

    $(document).on("click", "#btn_now_what", function() {
      activateStep(3);
    });

    document.getElementById("autocomplete_tags").addEventListener("change", function(e) {
      handleNewTag(e.target.selectedOptions);
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