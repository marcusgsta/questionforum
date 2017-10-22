"use strict";
$(function() {
    // define variables from elements
    let toggleLink = document.getElementById("show-hide-form");

    // create event listener on clicks
    if (toggleLink !== null) {
        toggleLink.addEventListener("click", function(event) {
            event.preventDefault();
            if ($(".comment-form-question").hasClass("hidden")) {
                $(".comment-form-question").removeClass("hidden");
            } else {
                $(".comment-form-question").addClass("hidden");
            }
        });
    }
});
