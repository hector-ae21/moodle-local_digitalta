import $ from "jquery";
import { deleteRelatedContext } from "./main";
import SELECTORS from "./selectors";
import { showChangeStatusModal, toggleExperienceStatus } from "./modals";
import { showManageModal, displaylinkResourcesModal, displaylinkCasesModal } from "./modals";
import { getMentors, handlerAddMentorRequest, handlerRemoveMentorRequest } from "./tutoring";
import setChat from "local_dta/chat/main";

export const setEventListeners = () => {
  const experienceid = $(SELECTORS.INPUTS.experienceid).val();

  $(document).on("click", SELECTORS.BUTTONS.edit, () => {
    showManageModal(experienceid);
  });

  $(document).on("click", `${SELECTORS.BUTTONS.block}, ${SELECTORS.BUTTONS.unblock}`, () => {
    showChangeStatusModal(experienceid);
  });

  $(document).on("click", SELECTORS.BUTTONS.confirmBlockModal, () => {
    toggleExperienceStatus(experienceid);
  });

  $(document).on("click", SELECTORS.BUTTONS.addResourceBtn, () => {
    displaylinkResourcesModal(true);
  });

  $(document).on("click", SELECTORS.BUTTONS.addCasesBtn, () => {
    displaylinkCasesModal();
  });

  $(document).on("click", SELECTORS.BUTTONS.removeContextButton, (event) => {
    deleteRelatedContext(event.currentTarget.dataset.contextid);
  });

  $(document).on("click", SELECTORS.BUTTONS.sendMentorRequest, (event) => {
    const mentorid = event.currentTarget.dataset.mentorid;
    handlerAddMentorRequest(mentorid, experienceid);
  });

  $(document).on("click", SELECTORS.BUTTONS.removeMentorRequest, (event) => {
    const mentorid = event.currentTarget.dataset.mentorid;
    handlerRemoveMentorRequest(mentorid, experienceid);
  });

  $(document).on("click", SELECTORS.BUTTONS.openChat, () => {
    setChat(SELECTORS.SECTIONS.mentoringSection, experienceid);
  });

  $(document).on("input", SELECTORS.INPUTS.mentorsSearch, async(event) => {
    getMentors(event.currentTarget.value);
  });
};
