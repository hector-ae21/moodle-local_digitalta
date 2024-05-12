define([
    "jquery",
    "https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js",
], function ($) {
    return {
        init: function (selector, options) {
            var elem = $(selector);
            elem.slick(options);
        },
    };
});
