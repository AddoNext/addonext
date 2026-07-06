(function () {
    'use strict';

    function parseNum(val) {
        var n = parseFloat(val, 10);
        return isNaN(n) ? null : n;
    }

    function buildChart(container) {
        if (container.getAttribute('data-chart-built') === '1') return;
        container.setAttribute('data-chart-built', '1');

        var labelsRaw = (container.getAttribute('data-labels') || '').split('|');
        var valuesStr = (container.getAttribute('data-values') || '').split('|');
        var colorsStr = (container.getAttribute('data-colors') || '').split('|');
        var values = valuesStr.map(function (v) { return parseFloat(v, 10) || 0; });
        var labels = [];
        for (var l = 0; l < values.length; l++) {
            labels.push(labelsRaw[l] !== undefined ? labelsRaw[l] : '');
        }
        var colors = [];
        for (var c = 0; c < values.length; c++) {
            colors.push(colorsStr[c] && colorsStr[c].trim() ? colorsStr[c].trim() : '#2563eb');
        }
        var ySuffix = container.getAttribute('data-y-suffix') || 'k';
        var yMin = parseNum(container.getAttribute('data-y-min'));
        var yMax = parseNum(container.getAttribute('data-y-max'));

        if (values.length === 0) return;

        var dataMax = Math.max.apply(null, values);
        var min = yMin != null ? yMin : 0;
        var max = yMax != null ? yMax : (Math.ceil(dataMax / 20) * 20);
        if (max <= min) max = min + 100;

        var step = 20;
        if (max - min <= 50) step = 10;
        if (max - min <= 20) step = 5;
        var yTicks = [];
        var y = min;
        while (y <= max) {
            yTicks.push(y);
            y += step;
        }
        if (yTicks.indexOf(max) === -1) yTicks.push(max);

        var canvas = container.querySelector('.addonext-bar-chart__canvas');
        if (!canvas) return;

        var paddingTop = 16;
        var paddingRight = 24;
        var paddingBottom = 36;
        var paddingLeft = 44;
        var width = 600;
        var height = 320;
        var chartWidth = width - paddingLeft - paddingRight;
        var chartHeight = height - paddingTop - paddingBottom;

        var yScale = function (v) {
            return paddingTop + chartHeight - ((v - min) / (max - min)) * chartHeight;
        };
        var n = labels.length;
        var barGap = 0.2;
        var barWidth = n > 0 ? (chartWidth / n) * (1 - barGap) : 0;
        var barStep = chartWidth / n;
        var barX = function (i) {
            return paddingLeft + i * barStep + (barStep - barWidth) / 2;
        };

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + width + ' ' + height);
        svg.setAttribute('class', 'addonext-bar-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var grid = document.createElementNS(ns, 'g');
        grid.setAttribute('class', 'addonext-bar-chart__grid');
        var i;
        for (i = 0; i < yTicks.length; i++) {
            var gy = yScale(yTicks[i]);
            var line = document.createElementNS(ns, 'line');
            line.setAttribute('x1', paddingLeft);
            line.setAttribute('y1', gy);
            line.setAttribute('x2', width - paddingRight);
            line.setAttribute('y2', gy);
            grid.appendChild(line);
        }
        svg.appendChild(grid);

        var yLabels = document.createElementNS(ns, 'g');
        yLabels.setAttribute('class', 'addonext-bar-chart__axis-labels addonext-bar-chart__axis-label');
        for (i = 0; i < yTicks.length; i++) {
            var text = document.createElementNS(ns, 'text');
            text.setAttribute('x', paddingLeft - 8);
            text.setAttribute('y', yScale(yTicks[i]) + 4);
            text.setAttribute('text-anchor', 'end');
            text.textContent = yTicks[i] + ySuffix;
            yLabels.appendChild(text);
        }
        svg.appendChild(yLabels);

        var bars = document.createElementNS(ns, 'g');
        bars.setAttribute('class', 'addonext-bar-chart__bars');
        var chartBottom = height - paddingBottom;
        for (i = 0; i < values.length; i++) {
            var rect = document.createElementNS(ns, 'rect');
            var barTop = yScale(values[i]);
            rect.setAttribute('x', barX(i));
            rect.setAttribute('y', barTop);
            rect.setAttribute('width', barWidth);
            rect.setAttribute('height', Math.max(0, chartBottom - barTop));
            rect.setAttribute('fill', colors[i]);
            bars.appendChild(rect);
        }
        svg.appendChild(bars);

        var xLabels = document.createElementNS(ns, 'g');
        xLabels.setAttribute('class', 'addonext-bar-chart__axis-labels addonext-bar-chart__axis-label');
        for (i = 0; i < labels.length; i++) {
            var xtext = document.createElementNS(ns, 'text');
            xtext.setAttribute('x', barX(i) + barWidth / 2);
            xtext.setAttribute('y', height - paddingBottom + 20);
            xtext.setAttribute('text-anchor', 'middle');
            xtext.textContent = labels[i];
            xLabels.appendChild(xtext);
        }
        svg.appendChild(xLabels);

        canvas.innerHTML = '';
        canvas.appendChild(svg);
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.addonext-bar-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.addonext-bar-chart__canvas');
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
                    if (node.classList && node.classList.contains('addonext-bar-chart')) {
                        containers.push(node);
                    }
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.addonext-bar-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        var canvas = container.querySelector('.addonext-bar-chart__canvas');
                        var needsBuild = canvas && !container.querySelector('.addonext-bar-chart__svg');
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
        elementorFrontend.hooks.addAction('frontend/element_ready/addonext-bar-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.addonext-bar-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
