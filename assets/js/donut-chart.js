(function () {
    'use strict';

    function clamp(val, min, max) {
        return Math.max(min, Math.min(max, val));
    }

    function formatNumber(value) {
        var number = Math.round((parseFloat(value, 10) || 0) * 100) / 100;
        return Number.isInteger(number) ? String(number) : String(number);
    }

    function buildChart(container) {
        if (container.getAttribute('data-chart-built') === '1') return;
        container.setAttribute('data-chart-built', '1');

        var labelsRaw = (container.getAttribute('data-labels') || '').split('|');
        var valuesStr = (container.getAttribute('data-values') || '').split('|');
        var colorsStr = (container.getAttribute('data-colors') || '').split('|');
        var centerPrefix = container.getAttribute('data-center-prefix') || '';
        var centerSuffix = container.getAttribute('data-center-suffix') || '';
        var centerLabel = container.getAttribute('data-center-label') || '';
        var showLabels = container.getAttribute('data-show-labels') !== 'no';
        var showLegend = container.getAttribute('data-show-legend') !== 'no';
        var cutoutRaw = parseFloat(container.getAttribute('data-inner-cutout'), 10);
        var cutoutPercent = isNaN(cutoutRaw) ? 50 : clamp(cutoutRaw, 35, 80);

        var values = valuesStr.map(function (v) { return Math.max(0, parseFloat(v, 10) || 0); });
        var total = values.reduce(function (a, b) { return a + b; }, 0);
        if (total <= 0) total = 1;

        var labels = [];
        var colors = [];
        var i;
        for (i = 0; i < values.length; i++) {
            labels.push(labelsRaw[i] !== undefined ? labelsRaw[i] : '');
            colors.push(colorsStr[i] && colorsStr[i].trim() ? colorsStr[i].trim() : '#4285F4');
        }

        if (values.length === 0) return;

        var canvas = container.querySelector('.veloria-donut-chart__canvas');
        var legendEl = container.querySelector('.veloria-donut-chart__legend');
        if (!canvas) return;

        var size = 360;
        var cx = size / 2;
        var cy = size / 2;
        var outerR = size / 2 - 8;
        var innerR = outerR * (cutoutPercent / 100);

        function polar(r, angleRad) {
            return { x: cx + r * Math.sin(angleRad), y: cy - r * Math.cos(angleRad) };
        }

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + size + ' ' + size);
        svg.setAttribute('class', 'veloria-donut-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var slices = document.createElementNS(ns, 'g');
        slices.setAttribute('class', 'veloria-donut-chart__slices');

        var startAngle = -Math.PI / 2;

        for (i = 0; i < values.length; i++) {
            var pct = values[i] / total;
            var endAngle = startAngle + pct * 2 * Math.PI;
            var outerStart = polar(outerR, startAngle);
            var outerEnd = polar(outerR, endAngle);
            var innerStart = polar(innerR, startAngle);
            var innerEnd = polar(innerR, endAngle);
            var largeArc = pct > 0.5 ? 1 : 0;

            var d = [
                'M', outerStart.x, outerStart.y,
                'A', outerR, outerR, 0, largeArc, 1, outerEnd.x, outerEnd.y,
                'L', innerEnd.x, innerEnd.y,
                'A', innerR, innerR, 0, largeArc, 0, innerStart.x, innerStart.y,
                'Z'
            ].join(' ');

            var path = document.createElementNS(ns, 'path');
            path.setAttribute('d', d);
            path.setAttribute('fill', colors[i]);
            slices.appendChild(path);

            if (showLabels) {
                var mid = (startAngle + endAngle) / 2;
                var labelDist = innerR + (outerR - innerR) * 0.5;
                var tx = cx + labelDist * Math.sin(mid);
                var ty = cy - labelDist * Math.cos(mid);
                var label = document.createElementNS(ns, 'text');
                label.setAttribute('x', tx);
                label.setAttribute('y', ty);
                label.setAttribute('text-anchor', 'middle');
                label.setAttribute('dominant-baseline', 'middle');
                label.setAttribute('class', 'veloria-donut-chart__slice-label');
                label.textContent = Math.round(pct * 100) + '%';
                slices.appendChild(label);
            }

            startAngle = endAngle;
        }

        svg.appendChild(slices);

        var center = document.createElementNS(ns, 'g');
        center.setAttribute('class', 'veloria-donut-chart__center');

        var centerValue = document.createElementNS(ns, 'text');
        centerValue.setAttribute('x', cx);
        centerValue.setAttribute('y', cy - 6);
        centerValue.setAttribute('text-anchor', 'middle');
        centerValue.setAttribute('class', 'veloria-donut-chart__center-value');
        centerValue.textContent = centerPrefix + formatNumber(total) + centerSuffix;
        center.appendChild(centerValue);

        if (centerLabel) {
            var centerCaption = document.createElementNS(ns, 'text');
            centerCaption.setAttribute('x', cx);
            centerCaption.setAttribute('y', cy + 22);
            centerCaption.setAttribute('text-anchor', 'middle');
            centerCaption.setAttribute('class', 'veloria-donut-chart__center-label');
            centerCaption.textContent = centerLabel;
            center.appendChild(centerCaption);
        }

        svg.appendChild(center);

        canvas.innerHTML = '';
        canvas.appendChild(svg);

        if (legendEl) {
            if (!showLegend) {
                legendEl.innerHTML = '';
                legendEl.style.display = 'none';
            } else {
                legendEl.style.display = '';
                legendEl.innerHTML = '';
                for (i = 0; i < labels.length; i++) {
                    var item = document.createElement('div');
                    item.setAttribute('class', 'veloria-donut-chart__legend-item');

                    var swatch = document.createElement('span');
                    swatch.setAttribute('class', 'veloria-donut-chart__legend-swatch');
                    swatch.style.backgroundColor = colors[i];

                    var labelText = document.createElement('span');
                    labelText.setAttribute('class', 'veloria-donut-chart__legend-text');
                    labelText.textContent = labels[i];

                    item.appendChild(swatch);
                    item.appendChild(labelText);
                    legendEl.appendChild(item);
                }
            }
        }
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.veloria-donut-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.veloria-donut-chart__canvas');
            var valuesStr = (el.getAttribute('data-values') || '').split('|');
            var hasData = valuesStr.length > 0 && valuesStr.some(function (v) { return v !== '' && !isNaN(parseFloat(v, 10)); });
            if (canvas && hasData) {
                buildChart(el);
            }
        });
    }

    function runAndSchedule() {
        run();
        if (typeof window !== 'undefined' && window.self !== window.top) {
            var count = 0;
            var interval = setInterval(function () {
                run();
                count++;
                if (count >= 24) clearInterval(interval);
            }, 500);
            startPreviewObserver();
        }
    }

    function startPreviewObserver() {
        if (!document.body || typeof MutationObserver === 'undefined') return;
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    var containers = [];
                    if (node.classList && node.classList.contains('veloria-donut-chart')) {
                        containers.push(node);
                    }
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.veloria-donut-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        var canvas = container.querySelector('.veloria-donut-chart__canvas');
                        var needsBuild = canvas && !container.querySelector('.veloria-donut-chart__svg');
                        if (needsBuild) {
                            container.removeAttribute('data-chart-built');
                            buildChart(container);
                        }
                    });
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runAndSchedule);
    } else {
        runAndSchedule();
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-donut-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.veloria-donut-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
