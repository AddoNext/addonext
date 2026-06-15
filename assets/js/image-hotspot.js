(function () {
    'use strict';

    function bindClickTrigger(wrapper) {
        if (wrapper.getAttribute('data-click-bound') === '1') return;
        wrapper.setAttribute('data-click-bound', '1');

        var points = wrapper.querySelectorAll('.veloria-image-hotspot__point');

        function setActive(point) {
            points.forEach(function (p) {
                p.classList.toggle('is-active', p === point);
            });
        }

        function clearActive() {
            points.forEach(function (p) {
                p.classList.remove('is-active');
            });
        }

        function findPoint(el) {
            while (el && el !== wrapper) {
                if (el.classList && el.classList.contains('veloria-image-hotspot__point')) return el;
                el = el.parentElement;
            }
            return null;
        }

        function consumePointEvent(e) {
            var point = findPoint(e.target);
            if (point) {
                e.preventDefault();
                e.stopPropagation();
                if (e.type === 'click') setActive(point);
                return true;
            }
            return false;
        }

        wrapper.addEventListener('mousedown', consumePointEvent, true);
        wrapper.addEventListener('click', consumePointEvent, true);

        var doc = wrapper.ownerDocument || document;
        doc.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) clearActive();
        });
    }

    function scan(root) {
        root = root || document;
        var widgets = root.querySelectorAll('.veloria-image-hotspot[data-trigger="click"]');
        widgets.forEach(bindClickTrigger);
    }

    function runScan() {
        scan();
    }

    var isPreviewIframe = (typeof window !== 'undefined' && window.self !== window.top);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            runScan();
            if (isPreviewIframe) startPreviewPolling();
        });
    } else {
        runScan();
        if (isPreviewIframe) startPreviewPolling();
    }

    function startPreviewPolling() {
        var count = 0;
        var maxPolls = 25;
        var interval = setInterval(function () {
            scan();
            count += 1;
            if (count >= maxPolls) clearInterval(interval);
        }, 800);
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-image-hotspot.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) scan(el);
        });
    }
})();
