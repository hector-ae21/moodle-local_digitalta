import $ from "jquery";
import Template from "core/templates";
import Notification from "core/notification";
import { SELECTORS } from "./selectors";
import { saveComment, getComments } from "../repositories/reactions_repository";

/**
 * Send the comment to the server.
 * @return {void}
 */
export function sendComment() {
  const comment = $(SELECTORS.COMMENT_INPUT).val().trim();
  const instanceid = $(SELECTORS.ACTIONS.sendComment).data("id");
  const instancetype = $(SELECTORS.BUTTONS.comment).data("instance");

  if (comment) {
    saveComment({ instancetype, instanceid, comment })
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
  const instanceid = $(SELECTORS.ACTIONS.sendComment).data("id");
  const instancetype = $(SELECTORS.BUTTONS.comment).data("instance");
  if (!instanceid) {
    return;
  }

  try {
    const response = await getComments({ instanceid, instancetype });
    const comments = response.comments.map((comment) => ({
      comment: comment.comment,
      userfullname: comment.user.fullname,
      userprofileUrl: M.cfg.wwwroot + "/user/profile.php?id=" + comment.user.id,
    }));

    const html = await Template.render("local_dta/reactions/comments", { comments });
    $(SELECTORS.COMMENTS_LIST).html(html);

    $(SELECTORS.ACTIONS.viewComment + SELECTORS.DATA.id(instanceid) + " span").text(
      response.comments.length ? response.comments.length : ""
    );
  } catch (error) {
    Notification.exception(error);
  }
}
