import * as Str from "core/str";
import { displayManageModal } from "local_dta/experiences/modals";

export const handleAction = async (action) => {
    const actionType = action.action;
    const actionValue = action.value;
    switch (actionType) {
        case "modal_ask": {
            const string_keys = [
                { key: "teacheracademy:actions:ask", component: "local_dta" },
                { key: "teacheracademy:actions:ask:description", component: "local_dta" },
                { key: "teacheracademy:actions:ask:title", component: "local_dta" },
                { key: "teacheracademy:actions:ask:title:placeholder", component: "local_dta" },
                { key: "teacheracademy:actions:ask:visibility", component: "local_dta" },
                { key: "teacheracademy:actions:ask:language", component: "local_dta" },
                { key: "teacheracademy:actions:ask:themes", component: "local_dta" },
                { key: "teacheracademy:actions:ask:tags", component: "local_dta" },
                { key: "teacheracademy:actions:ask:picture", component: "local_dta" },
                { key: "visibility:public", component: "local_dta" },
                { key: "visibility:private", component: "local_dta" }
            ];
            const strings = Str.get_strings(string_keys);
            Promise.all([strings])
                .then(([strings]) => displayManageModal(strings, null));
            break;
        }
        case "modal_share": {
            const string_keys = [
                { key: "teacheracademy:actions:share", component: "local_dta" },
                { key: "teacheracademy:actions:share:description", component: "local_dta" },
                { key: "teacheracademy:actions:share:title", component: "local_dta" },
                { key: "teacheracademy:actions:share:title:placeholder", component: "local_dta" },
                { key: "teacheracademy:actions:share:visibility", component: "local_dta" },
                { key: "teacheracademy:actions:share:language", component: "local_dta" },
                { key: "teacheracademy:actions:share:themes", component: "local_dta" },
                { key: "teacheracademy:actions:share:tags", component: "local_dta" },
                { key: "teacheracademy:actions:share:picture", component: "local_dta" },
                { key: "visibility:public", component: "local_dta" },
                { key: "visibility:private", component: "local_dta" }
            ];
            const strings = Str.get_strings(string_keys);
            Promise.all([strings])
                .then(([strings]) => displayManageModal(strings, null));
            break;
        }
        case "open_url":
            location.href = actionValue;
            break;
        default:
    }
};

export const init = () => {
    document.querySelectorAll(".action").forEach((action => {
        action.addEventListener("click", (() => handleAction(action.dataset)));
    }));
};
