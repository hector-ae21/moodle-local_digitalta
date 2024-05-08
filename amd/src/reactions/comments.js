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
    const comment = $(SELECTORS.COMMENT_INPUT).val().trim();

    if (comment) {
        addComment({ component, componentinstance, comment })
            .then((response) => {
                if (response.result) {
                    return updateUI();
                }
                return Notification.exception(response);
            })
            .fail(Notification.exception);
        $(SELECTORS.COMMENT_INPUT).val("");
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
        const comments = response.comments.map((comment) => ({
            comment: comment.comment,
            userfullname: comment.user.fullname,
            userprofileurl: M.cfg.wwwroot + "/user/profile.php?id=" + comment.user.id,
        }));

        const html = await Template.render("local_dta/reactions/comments", { comments });
        $(SELECTORS.COMMENTS_LIST).html(html);

        $(SELECTORS.ACTIONS.viewComment + SELECTORS.DATA.id(componentinstance) + " span").text(
            response.comments.length ? response.comments.length : ""
        );
    } catch (error) {
        Notification.exception(error);
    }
}
