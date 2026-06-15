(function () {
    'use strict';

    function initFlipBoxClick() {
        document.querySelectorAll('.veloria-flip-box--trigger-click').forEach(function (box) {
            if (box.dataset.flipBoxClickInit) return;
            box.dataset.flipBoxClickInit = '1';
            box.addEventListener('click', function (e) {
                if (e.target.closest('a')) return;
                e.preventDefault();
                this.classList.toggle('veloria-flip-box--active');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFlipBoxClick);
    } else {
        initFlipBoxClick();
    }

    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-flip-box.default', function () {
            initFlipBoxClick();
        });
    }
})();
