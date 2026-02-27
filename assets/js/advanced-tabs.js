(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.elementrify-advanced-tabs').forEach(function (wrapper) {
            var headers = wrapper.querySelectorAll('.elementrify-advanced-tabs__header');
            var panels = wrapper.querySelectorAll('.elementrify-advanced-tabs__panel');
            headers.forEach(function (header, index) {
                header.addEventListener('click', function () {
                    headers.forEach(function (h) { h.classList.remove('is-active'); });
                    panels.forEach(function (p) { p.classList.remove('is-active'); });
                    header.classList.add('is-active');
                    if (panels[index]) panels[index].classList.add('is-active');
                });
            });
        });
    });
})();
