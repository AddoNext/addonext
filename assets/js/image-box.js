(function () {
    function initTilt3d() {
        document.querySelectorAll('.veloria-image-box--hover-tilt-3d .veloria-image-box__image').forEach(function (el) {
            if (el.dataset.tilt3dInit) return;
            el.dataset.tilt3dInit = '1';
            var img = el.querySelector('img');
            if (!img) return;
            var maxTilt = 12;
            el.addEventListener('mousemove', function (e) {
                var rect = el.getBoundingClientRect();
                var x = (e.clientX - rect.left) / rect.width - 0.5;
                var y = (e.clientY - rect.top) / rect.height - 0.5;
                var rotateY = x * maxTilt;
                var rotateX = -y * maxTilt;
                img.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg)';
            });
            el.addEventListener('mouseleave', function () {
                img.style.transform = '';
            });
        });
    }

    function runTilt() {
        if (document.readyState === 'loading') return;
        initTilt3d();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runTilt);
    } else {
        runTilt();
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-image-box.default', function () {
            setTimeout(initTilt3d, 100);
        });
    }

    var observer = new MutationObserver(function (mutations) {
        var hasTilt = document.querySelector('.veloria-image-box--hover-tilt-3d .veloria-image-box__image:not([data-tilt3d-init])');
        if (hasTilt) setTimeout(initTilt3d, 50);
    });
    if (document.body) {
        observer.observe(document.body, { childList: true, subtree: true });
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            observer.observe(document.body, { childList: true, subtree: true });
        });
    }
})();
