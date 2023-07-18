(function ($) {
    "user strict";
    $(window).on("load", () => {
        $("#landing-loader").fadeOut(1000);
        var img = $(".bg__img");
        img.css("background-image", function () {
            var bg = "url(" + $(this).data("img") + ")";
            var bg = `url(${$(this).data("img")})`;
            return bg;
        });
    });
    $(document).ready(function () {
        $(".accordion-title").on("click", function (e) {
            var element = $(this).parent(".accordion-item");
            if (element.hasClass("open")) {
                element.removeClass("open");
                element.find(".accordion-content").removeClass("open");
                element.find(".accordion-content").slideUp(200, "swing");
            } else {
                element.addClass("open");
                element.children(".accordion-content").slideDown(200, "swing");
                element
                    .siblings(".accordion-item")
                    .children(".accordion-content")
                    .slideUp(200, "swing");
                element.siblings(".accordion-item").removeClass("open");
                element
                    .siblings(".accordion-item")
                    .find(".accordion-title")
                    .removeClass("open");
                element
                    .siblings(".accordion-item")
                    .find(".accordion-content")
                    .slideUp(200, "swing");
            }
        });
        $(".nav-toggle").on("click", () => {
            $(".nav-toggle").toggleClass("active");
            $(".menu").toggleClass("active");
        });

        var header = $("header");
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 160) {
                header.addClass("active");
            } else {
                header.removeClass("active");
            }
        });
        if ($(".wow").length) {
            var wow = new WOW({
                boxClass: "wow",
                animateClass: "animated",
                offset: 0,
                mobile: true,
                live: true,
            });
            wow.init();
        }
    });
})(jQuery);
