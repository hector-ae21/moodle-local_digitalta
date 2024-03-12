define(["jquery", "https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"], function ($, masonry) {
  return {
    init: function (selector, options) {
      var elem = document.querySelector(selector);
      new masonry(elem, {
        ...options,
      });
    },
  };
});
