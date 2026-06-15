(function () {
    'use strict';

    function parseDue(dueStr) {
        if (!dueStr || typeof dueStr !== 'string') return null;
        var s = dueStr.trim();
        if (s.indexOf(' ') !== -1) s = s.replace(' ', 'T');
        var date = new Date(s);
        return isNaN(date.getTime()) ? null : date;
    }

    function getRemaining(endDate) {
        var now = new Date();
        var diff = Math.max(0, Math.floor((endDate - now) / 1000));
        var days = Math.floor(diff / 86400);
        var hours = Math.floor((diff % 86400) / 3600);
        var minutes = Math.floor((diff % 3600) / 60);
        var seconds = diff % 60;
        return { days: days, hours: hours, minutes: minutes, seconds: seconds };
    }

    function pad(num) {
        return num < 10 ? '0' + num : String(num);
    }

    function runCountdown(el) {
        var dueStr = el.getAttribute('data-due');
        var endDate = parseDue(dueStr);
        if (!endDate) return;

        if (el._countdownInterval) {
            clearInterval(el._countdownInterval);
            el._countdownInterval = null;
        }

        var segments = el.querySelectorAll('.veloria-countdown__segment');
        var valueEls = {};
        var shutterValueEls = {};
        var segmentByUnit = {};
        segments.forEach(function (seg) {
            var unit = seg.getAttribute('data-unit');
            var val = seg.querySelector('.veloria-countdown__value');
            var shutterVal = seg.querySelector('.veloria-countdown__shutter-value');
            var shutterEl = seg.querySelector('.veloria-countdown__shutter');
            if (unit && val) {
                valueEls[unit] = val;
                segmentByUnit[unit] = seg;
                if (shutterEl && shutterVal) {
                    shutterValueEls[unit] = { el: shutterEl, value: shutterVal };
                }
            }
        });

        var shutterFlip = el.getAttribute('data-shutter-flip') === '1';
        var prev = { days: null, hours: null, minutes: null, seconds: null };

        function updateUnit(unit, text) {
            if (valueEls[unit]) valueEls[unit].textContent = text;
        }

        function tick() {
            var r = getRemaining(endDate);
            if (shutterFlip && Object.keys(shutterValueEls).length > 0) {
                var units = ['days', 'hours', 'minutes', 'seconds'];
                var texts = [String(r.days), pad(r.hours), pad(r.minutes), pad(r.seconds)];
                units.forEach(function (unit, i) {
                    var newVal = texts[i];
                    var prevVal = prev[unit];
                    var shutterData = shutterValueEls[unit];
                    if (shutterData && prevVal !== null && String(prevVal) !== newVal) {
                        updateUnit(unit, newVal);
                        shutterData.value.textContent = String(prevVal);
                        shutterData.el.classList.add('shutter-flip');
                        setTimeout(function () {
                            shutterData.el.classList.remove('shutter-flip');
                            shutterData.value.textContent = newVal;
                        }, 450);
                    } else {
                        updateUnit(unit, newVal);
                        if (shutterData) shutterData.value.textContent = newVal;
                    }
                    prev[unit] = unit === 'days' ? r.days : (unit === 'hours' ? r.hours : (unit === 'minutes' ? r.minutes : r.seconds));
                });
            } else {
                if (valueEls.days) valueEls.days.textContent = String(r.days);
                if (valueEls.hours) valueEls.hours.textContent = pad(r.hours);
                if (valueEls.minutes) valueEls.minutes.textContent = pad(r.minutes);
                if (valueEls.seconds) valueEls.seconds.textContent = pad(r.seconds);
            }
        }

        tick();
        el._countdownInterval = setInterval(function () {
            var r = getRemaining(endDate);
            if (r.days === 0 && r.hours === 0 && r.minutes === 0 && r.seconds === 0) {
                clearInterval(el._countdownInterval);
                el._countdownInterval = null;
            }
            tick();
        }, 1000);
    }

    function scan(root) {
        root = root || document;
        var widgets = root.querySelectorAll('.veloria-countdown[data-due]');
        widgets.forEach(runCountdown);
    }

    function initAddedNode(node) {
        if (node.nodeType !== 1) return;
        if (node.matches && node.matches('.veloria-countdown[data-due]')) {
            runCountdown(node);
        }
        if (node.querySelectorAll) {
            var list = node.querySelectorAll('.veloria-countdown[data-due]');
            list.forEach(runCountdown);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            scan();
            setTimeout(scan, 500);
        });
    } else {
        scan();
        setTimeout(scan, 500);
    }

    if (typeof MutationObserver !== 'undefined' && document.body) {
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.addedNodes && m.addedNodes.length) {
                    for (var i = 0; i < m.addedNodes.length; i++) {
                        initAddedNode(m.addedNodes[i]);
                    }
                }
            });
        });
        if (document.body) {
            observer.observe(document.body, { childList: true, subtree: true });
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                observer.observe(document.body, { childList: true, subtree: true });
            });
        }
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-countdown-timer.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) scan(el);
        });
    }
})();
