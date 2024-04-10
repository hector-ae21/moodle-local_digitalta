import $ from "jquery";
import { waitForElm } from "../utils";

const cleanTinyBottomBox = () => {
  waitForElm(".tox-statusbar").then(() => {
    $(".tox-statusbar").css("border-top", "none");
  });
  waitForElm(".tox-statusbar__text-container").then(() => {
    $(".tox-statusbar__text-container").remove();
  });
};

//TODO: Make this shit work
const cleanTinyMenus = () => {
  waitForElm(".tox-menubar").then(() => {
    const $menus = $(".tox-menubar .tox-menubar__primary-menu .tox-menubar__menu-item");
    $menus.each((index, menu) => {
      const menuPosition = index + 1;
      if (![1, 4, 5].includes(menuPosition)) {
        $(menu).remove();
      }
    });
  });
};

export const init = () => {
  cleanTinyBottomBox();
  cleanTinyMenus();
};
