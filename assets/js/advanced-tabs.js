(function () {
    document.addEventListener("click", function (e) {
        var header = e.target.closest(".elementrify-advanced-tabs__header");
        if (!header) return;
        e.preventDefault();
        e.stopPropagation();
        var wrapper = header.closest(".elementrify-advanced-tabs");
        if (!wrapper) return;
        var headers = wrapper.querySelectorAll(".elementrify-advanced-tabs__header");
        var panels = wrapper.querySelectorAll(".elementrify-advanced-tabs__panel");
        var index = Array.prototype.indexOf.call(headers, header);
        headers.forEach(function (h) {
            h.classList.remove("is-active");
        });
        panels.forEach(function (p) {
            p.classList.remove("is-active");
        });
        header.classList.add("is-active");
        if (panels[index]) {
            panels[index].classList.add("is-active");
        }
    }, true);
})();
