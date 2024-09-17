import $ from "jquery";
import {
    showManageResourcesModal
} from "local_digitalta/resources/modals";
import SELECTORS from "local_digitalta/resources/selectors";

export const setEventListeners = () => {
    $(document).on("click", SELECTORS.BUTTONS.add, () => {
        window.console.log("HOLAAAAA");
        showManageResourcesModal();
    });
};
