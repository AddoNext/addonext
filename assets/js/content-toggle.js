(function () {
    document.addEventListener("click", function (e) {
        var label = e.target.closest(".veloria-content-toggle__label");
        if (!label) return;
        e.preventDefault();
        e.stopPropagation();
        var wrapper = label.closest(".veloria-content-toggle");
        if (!wrapper) return;
        var labels = wrapper.querySelectorAll(".veloria-content-toggle__label");
        var panels = wrapper.querySelectorAll(".veloria-content-toggle__panel");
        var index = Array.prototype.indexOf.call(labels, label);
        labels.forEach(function (l) {
            l.classList.remove("is-active");
        });
        panels.forEach(function (p) {
            p.classList.remove("is-active");
        });
        label.classList.add("is-active");
        if (panels[index]) {
            panels[index].classList.add("is-active");
        }
    }, true);
})();
