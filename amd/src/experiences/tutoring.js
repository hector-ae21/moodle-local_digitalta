import { searchMentors } from "local_dta/repositories/tutoring_repository";
import Templates from "core/templates";
import { SELECTORS } from "./main";
import $ from "jquery";

export const getMentors = async (searchText) => {
  if (!searchText.trim()) {
    $(SELECTORS.SECTIONS.searchMentorsResults).empty();
    return;
  }
  const mentors = await searchMentors({ searchText: searchText });
  await addMentorsResults(mentors);
};

/**
 * Add mentors to the results div
 * @param {object} mentors
 * @return {void}
 */
export function addMentorsResults(mentors) {
  const formattedMentors = mentors.map((mentor) => {
    return {
      name: mentor.firstname + " " + mentor.lastname,
      university: "",
      profileimage: "",
      id: mentor.id,
    };
  });
  Templates.render("local_dta/test/menu_mentor/item-search", {
    mentors: formattedMentors,
  }).then((html) => {
    $(SELECTORS.SECTIONS.searchMentorsResults).html(html);
    return;
  });
}
