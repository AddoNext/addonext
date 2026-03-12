(function () {
    'use strict';

    var PAD = 8;

    function positionTooltip(wrapper) {
        var trigger = wrapper.querySelector('.elementrify-tooltip__trigger');
        var content = wrapper.querySelector('.elementrify-tooltip__content');
        if (!trigger || !content) return;

        content.style.removeProperty('--tooltip-adjust-x');
        wrapper.classList.remove('elementrify-tooltip--below');

        var cr = content.getBoundingClientRect();
        var vw = window.innerWidth;

        if (cr.top < PAD) {
            wrapper.classList.add('elementrify-tooltip--below');
            cr = content.getBoundingClientRect();
        }

        var adjustX = 0;
        if (cr.left < PAD) {
            adjustX = PAD - cr.left;
        } else if (cr.right > vw - PAD) {
            adjustX = (vw - PAD) - cr.right;
        }
        content.style.setProperty('--tooltip-adjust-x', adjustX + 'px');
    }

    function clearPosition(wrapper) {
        var content = wrapper.querySelector('.elementrify-tooltip__content');
        if (content) content.style.removeProperty('--tooltip-adjust-x');
        wrapper.classList.remove('elementrify-tooltip--below');
    }

    function bind(wrapper) {
        var trigger = wrapper.querySelector('.elementrify-tooltip__trigger');
        if (!trigger) return;

        function onShow() {
            requestAnimationFrame(function () {
                positionTooltip(wrapper);
            });
        }

        function onHide() {
            clearPosition(wrapper);
        }

        wrapper.addEventListener('mouseenter', onShow);
        wrapper.addEventListener('mouseleave', onHide);
        trigger.addEventListener('focus', onShow);
        trigger.addEventListener('blur', onHide);
    }

    function scan(root) {
        root = root || document;
        var wrappers = root.querySelectorAll('.elementrify-tooltip');
        wrappers.forEach(function (el) {
            if (!el.getAttribute('data-tooltip-bound')) {
                el.setAttribute('data-tooltip-bound', '1');
                bind(el);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { scan(); });
    } else {
        scan();
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-tooltip.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) scan(el);
        });
    }
})();
