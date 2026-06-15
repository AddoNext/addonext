(function () {
    document.addEventListener("click", function (e) {
        var header = e.target.closest(".veloria-advanced-tabs__header");
        if (!header) return;
        e.preventDefault();
        e.stopPropagation();
        var wrapper = header.closest(".veloria-advanced-tabs");
        if (!wrapper) return;
        var headers = wrapper.querySelectorAll(".veloria-advanced-tabs__header");
        var panels = wrapper.querySelectorAll(".veloria-advanced-tabs__panel");
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
