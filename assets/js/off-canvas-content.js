(function () {
    'use strict';

    function setOpenState(wrapper, isOpen) {
        if (!wrapper) return;
        var trigger = wrapper.querySelector('.addonext-off-canvas__trigger');
        var panel = wrapper.querySelector('.addonext-off-canvas__panel');
        wrapper.classList.toggle('is-open', isOpen);
        if (trigger) {
            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
        if (panel) {
            panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        }
    }

    document.addEventListener('click', function (evt) {
        var trigger = evt.target.closest('.addonext-off-canvas__trigger');
        if (trigger) {
            var wrapper = trigger.closest('.addonext-off-canvas');
            if (!wrapper) return;
            setOpenState(wrapper, !wrapper.classList.contains('is-open'));
            return;
        }

        var closeBtn = evt.target.closest('.addonext-off-canvas__close');
        if (closeBtn) {
            var closeWrapper = closeBtn.closest('.addonext-off-canvas');
            setOpenState(closeWrapper, false);
            return;
        }

        var overlay = evt.target.closest('.addonext-off-canvas__overlay');
        if (overlay) {
            var overlayWrapper = overlay.closest('.addonext-off-canvas');
            if (!overlayWrapper) return;
            if (overlayWrapper.getAttribute('data-close-on-overlay') === 'yes') {
                setOpenState(overlayWrapper, false);
            }
        }
    });

    document.addEventListener('keydown', function (evt) {
        if (evt.key !== 'Escape') return;
        var opened = document.querySelectorAll('.addonext-off-canvas.is-open');
        opened.forEach(function (wrapper) {
            setOpenState(wrapper, false);
        });
    });

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-off-canvas-content.default', function ($scope) {
            var container = $scope && $scope[0] !== undefined ? $scope[0] : $scope;
            if (!container || !container.querySelectorAll) return;
            var wrappers = container.querySelectorAll('.addonext-off-canvas');
            wrappers.forEach(function (wrapper) {
                setOpenState(wrapper, false);
                var panel = wrapper.querySelector('.addonext-off-canvas__panel');
                if (panel && !panel.id) {
                    panel.id = 'addonext-off-canvas-panel-' + Math.random().toString(36).slice(2, 9);
                }
                var trigger = wrapper.querySelector('.addonext-off-canvas__trigger');
                if (trigger && panel && panel.id) {
                    trigger.setAttribute('aria-controls', panel.id);
                }
                if (panel) {
                    panel.setAttribute('aria-modal', 'true');
                    panel.setAttribute('role', 'dialog');
                }
            });
        });
    }

    if (document.readyState !== 'loading') {
        var initialWrappers = document.querySelectorAll('.addonext-off-canvas');
        initialWrappers.forEach(function (wrapper) {
            setOpenState(wrapper, false);
            var panel = wrapper.querySelector('.addonext-off-canvas__panel');
            if (panel && !panel.id) {
                panel.id = 'addonext-off-canvas-panel-' + Math.random().toString(36).slice(2, 9);
            }
            var trigger = wrapper.querySelector('.addonext-off-canvas__trigger');
            if (trigger && panel && panel.id) {
                trigger.setAttribute('aria-controls', panel.id);
            }
        });
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            var wrappers = document.querySelectorAll('.addonext-off-canvas');
            wrappers.forEach(function (wrapper) {
                setOpenState(wrapper, false);
            });
        });
    }
})();

