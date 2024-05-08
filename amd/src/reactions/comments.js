import $ from "jquery";
import Template from "core/templates";
import Notification from "core/notification";
import { SELECTORS } from "./selectors";
import { addComment, getComments } from "../repositories/reactions_repository";

/**
 * Send the comment to the server.
 * @return {void}
 */
export function sendComment() {
    const component = $(SELECTORS.BUTTONS.comment).data("component");
    const componentinstance = $(SELECTORS.ACTIONS.sendComment).data("id");
    const comment = $(SELECTORS.COMMENTS.input).val().trim();

    if (comment) {
        addComment({ component, componentinstance, comment })
            .then((response) => {
                if (response.result) {
                    return updateUI();
                }
                return Notification.exception(response);
            })
            .fail(Notification.exception);
        $(SELECTORS.COMMENTS.input).val("");
    }
}

/**
 * Update the comments in the UI.
 * @return {void}
 */
export async function updateUI() {
    const component = $(SELECTORS.BUTTONS.comment).data("component");
    const componentinstance = $(SELECTORS.ACTIONS.sendComment).data("id");
    if (!componentinstance) {
        return;
    }
    try {
        const response = await getComments({ component, componentinstance });
        let comments = [];
        if (response.result && response.comments) {
            comments = response.comments.map((comment) => ({
                comment: comment.comment,
                userfullname: comment.user.fullname,
                userprofileurl: M.cfg.wwwroot + "/user/profile.php?id=" + comment.user.id,
            }));
        }
        const html = await Template.render("local_dta/reactions/comments", { comments });
        $(SELECTORS.COMMENTS.list).html(html);
        const button = $(SELECTORS.ACTIONS.viewComment + SELECTORS.DATA.id(componentinstance));
              button.toggleClass("active", button.hasClass("show"));
        $(SELECTORS.ACTIONS.viewComment + SELECTORS.DATA.id(componentinstance) + " span").text(
            comments.length ? comments.length : ""
        );
    } catch (error) {
        Notification.exception(error);
    }
}
