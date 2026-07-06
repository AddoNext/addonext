(function () {
    'use strict';

    function runCountUp(el) {
        var to = parseInt(el.getAttribute('data-to'), 10);
        var prefix = el.getAttribute('data-prefix') || '';
        var suffix = el.getAttribute('data-suffix') || '';
        if (isNaN(to)) return;
        if (el.getAttribute('data-animated') === 'yes') return;
        el.setAttribute('data-animated', 'yes');
        var duration = 2000;
        var startTime = null;
        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var elapsed = timestamp - startTime;
            var progress = Math.min(elapsed / duration, 1);
            var easeOut = 1 - Math.pow(1 - progress, 3);
            var current = Math.round(to * easeOut);
            el.textContent = prefix + current + suffix;
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = prefix + to + suffix;
            }
        }
        requestAnimationFrame(step);
    }

    function animateWidget(widget) {
        if (!widget || widget.getAttribute('data-counter-animated') === 'yes') return;
        var numbers = widget.querySelectorAll('.addonext-animated-counter__number[data-to]');
        if (!numbers.length) return;
        widget.setAttribute('data-counter-animated', 'yes');
        numbers.forEach(function (el) {
            runCountUp(el);
        });
    }

    function isInView(el) {
        if (!el || !el.getBoundingClientRect) return false;
        var rect = el.getBoundingClientRect();
        var win = el.ownerDocument && el.ownerDocument.defaultView ? el.ownerDocument.defaultView : window;
        return rect.top < win.innerHeight && rect.bottom > 0;
    }

    function setupObserver(target, widget) {
        if (!target || !widget) return;
        if (widget.getAttribute('data-counter-animated') === 'yes') return;
        if (typeof IntersectionObserver === 'undefined') {
            animateWidget(widget);
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var w = entry.target.closest('.addonext-animated-counter');
                if (w) animateWidget(w);
                observer.unobserve(entry.target);
            });
        }, {
            root: null,
            rootMargin: '50px 0px 50px 0px',
            threshold: 0.01
        });
        observer.observe(target);
        if (isInView(target)) {
            var raf = window.requestAnimationFrame || function (cb) { setTimeout(cb, 50); };
            raf(function () {
                if (widget.getAttribute('data-counter-animated') !== 'yes') {
                    animateWidget(widget);
                }
            });
        }
    }

    function scan(root) {
        root = root || document;
        var widgets = root.querySelectorAll('.addonext-animated-counter');
        widgets.forEach(function (widget) {
            if (widget.getAttribute('data-counter-setup') === 'yes') return;
            widget.setAttribute('data-counter-setup', 'yes');
            var target = widget.querySelector('.addonext-animated-counter__grid') || widget.querySelector('.addonext-animated-counter__inner') || widget;
            setupObserver(target, widget);
        });
    }

    function init() {
        scan(document);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', init);
    setTimeout(init, 800);

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) return;
        function onWidgetReady($scope) {
            var widget = (typeof $scope !== 'undefined' && $scope && $scope[0]) ? $scope[0] : (typeof $scope !== 'undefined' && $scope && $scope.nodeType) ? $scope : null;
            if (!widget || !widget.querySelector) return;
            var inner = widget.querySelector('.addonext-animated-counter');
            var root = inner || widget;
            if (root.getAttribute('data-counter-setup') === 'yes') return;
            root.setAttribute('data-counter-setup', 'yes');
            var target = root.querySelector('.addonext-animated-counter__grid') || root.querySelector('.addonext-animated-counter__inner') || root;
            setupObserver(target, root);
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-animated-counter.default', onWidgetReady);
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-animated-counter', onWidgetReady);
    }

    if (typeof elementorFrontend !== 'undefined') {
        bindElementor();
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(bindElementor, 100);
        });
        window.addEventListener('load', function () {
            setTimeout(bindElementor, 500);
        });
    }
})();
