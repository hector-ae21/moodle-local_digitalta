import { searchMentors } from "local_dta/repositories/tutoring_repository";
import Templates from "core/templates";
import Notification from "core/notification";
import { SELECTORS } from "./main";
import $ from "jquery";

export const getMentors = async (searchText) => {
  if (!searchText.trim()) {
    $(SELECTORS.SECTIONS.searchMentorsResults).empty();
    return;
  }
  const experienceid = $("#experience-id").val();
  const mentors = await searchMentors({ searchText: searchText, experienceid });
  await addMentorsResults(mentors);
};

/**
 * Add mentors to the results div
 * @param {object} mentorsData
 * @return {void}
 */
export function addMentorsResults(mentorsData) {
  Templates.render("local_dta/test/menu_mentor/item-search", {
    mentors: mentorsData,
  }).then((html) => {
    $(SELECTORS.SECTIONS.searchMentorsResults).html(html);
    return;
  }).catch((error) => {
    Notification.exception(error);
  });
}
