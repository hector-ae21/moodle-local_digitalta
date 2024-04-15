import $ from "jquery";

/**
 * Activate the step.
 * @param {number} stepNum - The step number to activate.
 * @return {void}
 * */
export function activateStep(stepNum = 1) {
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

