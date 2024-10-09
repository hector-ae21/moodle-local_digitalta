import * as Cfg from "core/config";

export const setEventListeners = () => {
  // eslint-disable-next-line promise/always-return, promise/catch-or-return
  waitForElm(".help-video-icon").then(() => {
    const videohelpers = document.querySelectorAll(".help-video-icon");
    videohelpers.forEach((videohelper) => {
      const staticLocation = Cfg.wwwroot + "/local/digitalta/statics/";
      const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
      width=600,height=300,left=100,top=100`;
      videohelper.addEventListener("click", () => {
        const videoName = videohelper.dataset.video;
        open(staticLocation + videoName + ".mp4", "test", params);
      });
    });
  });
};

/**
 * Waits for an element to appear in the DOM.
 * @param {string} selector - The CSS selector of the element to wait for.
 * @returns {Promise<Element>} A promise that resolves with the element once it appears in the DOM.
 */
function waitForElm(selector) {
  return new Promise((resolve) => {
    if (document.querySelector(selector)) {
      return resolve(document.querySelector(selector));
    }

    const observer = new MutationObserver(() => {
      if (document.querySelector(selector)) {
        observer.disconnect();
        resolve(document.querySelector(selector));
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true,
    });
  });
}
