import $ from "jquery";

const cleanBottomBox = () => {
  const observer = new MutationObserver((mutations, obs) => {
    const statusBar = $(".tox-statusbar");
    const textContainer = $(".tox-statusbar__text-container");

    if (statusBar.length && textContainer.length) {
      statusBar.css("border-top", "none");
      textContainer.remove();

      obs.disconnect();
    }
  });
  const config = { childList: true, subtree: true };
  observer.observe(document.body, config);
};

/**
 * Clean the menus of the editor.
 * @return {void}
 */
function cleanMenus() {
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.addedNodes.length) {
        const positionToRemove = [ 1, 3];
        $(".tox-menubar").each(function () {
          $(this).find("button").each(function (i, el) {
            if (positionToRemove.includes(i)) {
              $(el).hide();
            }
          } );
        });
      }
    });
  });

  const config = { childList: true, subtree: true };
  observer.observe(document.body, config);
}

/**
 * Initialize the module.
 * @return {void}
 * */
export function clean() {
  cleanBottomBox();
  cleanMenus();
}
