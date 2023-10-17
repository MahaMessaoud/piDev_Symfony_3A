document.addEventListener("DOMContentLoaded", function() {
    var popup = document.querySelector(".popup");
    var openButton = document.querySelector("#open-popup");
    var closeButton = document.querySelector("#close-popup");

    openButton.addEventListener("click", function() {
        popup.classList.add("visible");
    });

    closeButton.addEventListener("click", function() {
        popup.classList.remove("visible");
    });
});