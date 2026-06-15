(function () {
    'use strict';

    function parseLocalDateTime(dueStr) {
        if (!dueStr || typeof dueStr !== 'string') return null;
        var s = dueStr.trim();
        if (!s) return null;

        // Expect: YYYY-MM-DD HH:MM
        var parts = s.split(' ');
        if (parts.length < 2) {
            // fallback: let Date try
            var fallback = new Date(s.replace(' ', 'T'));
            return isNaN(fallback.getTime()) ? null : fallback;
        }
        var datePart = parts[0];
        var timePart = parts[1];

        var d = datePart.split('-');
        var t = timePart.split(':');
        if (d.length !== 3 || t.length < 2) return null;

        var year = parseInt(d[0], 10);
        var month = parseInt(d[1], 10);
        var day = parseInt(d[2], 10);
        var hour = parseInt(t[0], 10);
        var minute = parseInt(t[1], 10);

        if ([year, month, day, hour, minute].some(function (n) { return isNaN(n); })) return null;
        return new Date(year, month - 1, day, hour, minute, 0, 0);
    }

    function getRemaining(endDate) {
        var now = new Date();
        var diff = Math.max(0, Math.floor((endDate - now) / 1000));
        var days = Math.floor(diff / 86400);
        var hours = Math.floor((diff % 86400) / 3600);
        var minutes = Math.floor((diff % 3600) / 60);
        return { days: days, hours: hours, minutes: minutes, total: diff };
    }

    function pad2(num) {
        return num < 10 ? '0' + num : String(num);
    }

    function runPromoCountdown(counterEl) {
        if (!counterEl) return;
        var dueStr = counterEl.getAttribute('data-due') || '';
        var endDate = parseLocalDateTime(dueStr);
        if (!endDate) return;

        if (counterEl._promoInterval) {
            clearInterval(counterEl._promoInterval);
            counterEl._promoInterval = null;
        }

        var valueDays = counterEl.querySelector('.veloria-promo-box__counter-value[data-unit="days"]');
        var valueHours = counterEl.querySelector('.veloria-promo-box__counter-value[data-unit="hours"]');
        var valueMinutes = counterEl.querySelector('.veloria-promo-box__counter-value[data-unit="minutes"]');
        var expiredText = counterEl.getAttribute('data-expired-text') || '';

        function render(r) {
            if (valueDays) valueDays.textContent = pad2(r.days);
            if (valueHours) valueHours.textContent = pad2(r.hours);
            if (valueMinutes) valueMinutes.textContent = pad2(r.minutes);
        }

        function tick() {
            var r = getRemaining(endDate);
            render(r);
            if (r.total <= 0) {
                clearInterval(counterEl._promoInterval);
                counterEl._promoInterval = null;
                if (expiredText) {
                    counterEl.textContent = expiredText;
                }
            }
        }

        tick();
        counterEl._promoInterval = setInterval(tick, 1000);
    }

    function scan(root) {
        root = root || document;
        var counters = root.querySelectorAll('.veloria-promo-box__counter[data-due]');
        counters.forEach(function (el) {
            runPromoCountdown(el);
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
    setTimeout(init, 600);

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) return;
        function onWidgetReady($scope) {
            var node = ($scope && $scope[0]) ? $scope[0] : $scope;
            if (!node || !node.querySelectorAll) return;
            scan(node);
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-promo-box.default', onWidgetReady);
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-promo-box', onWidgetReady);
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

