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
        var values = valuesStr.map(function (v) { return parseFloat(v, 10) || 0; });
        var labels = [];
        for (var l = 0; l < values.length; l++) {
            labels.push(labelsRaw[l] !== undefined ? labelsRaw[l] : '');
        }
        var ySuffix = container.getAttribute('data-y-suffix') || 'k';
        var yMin = parseNum(container.getAttribute('data-y-min'));
        var yMax = parseNum(container.getAttribute('data-y-max'));

        if (values.length === 0) return;

        var dataMin = Math.min.apply(null, values);
        var dataMax = Math.max.apply(null, values);
        var min = yMin != null ? yMin : (Math.floor(dataMin / 5) * 5);
        var max = yMax != null ? yMax : (Math.ceil(dataMax / 5) * 5);
        if (min >= max) max = min + 10;

        var range = max - min;
        var step = range <= 10 ? 5 : (range <= 25 ? 5 : (range <= 50 ? 10 : Math.ceil(range / 8 / 5) * 5));
        var yTicks = [];
        var y = min;
        while (y <= max) {
            yTicks.push(Math.round(y * 10) / 10);
            y += step;
        }
        if (yTicks.indexOf(max) === -1) yTicks.push(max);

        var canvas = container.querySelector('.veloria-line-chart__canvas');
        if (!canvas) return;

        var paddingTop = 16;
        var paddingRight = 24;
        var paddingBottom = 32;
        var paddingLeft = 44;
        var width = 600;
        var height = 320;
        var chartWidth = width - paddingLeft - paddingRight;
        var chartHeight = height - paddingTop - paddingBottom;

        var yScale = function (v) {
            return paddingTop + chartHeight - ((v - min) / (max - min)) * chartHeight;
        };
        var xScale = function (i) {
            var n = labels.length;
            if (n <= 1) return paddingLeft + chartWidth / 2;
            return paddingLeft + (i / (n - 1)) * chartWidth;
        };

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + width + ' ' + height);
        svg.setAttribute('class', 'veloria-line-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var grid = document.createElementNS(ns, 'g');
        grid.setAttribute('class', 'veloria-line-chart__grid');

        var i;
        for (i = 0; i < yTicks.length; i++) {
            var y = yScale(yTicks[i]);
            var line = document.createElementNS(ns, 'line');
            line.setAttribute('x1', paddingLeft);
            line.setAttribute('y1', y);
            line.setAttribute('x2', width - paddingRight);
            line.setAttribute('y2', y);
            grid.appendChild(line);
        }
        for (i = 0; i < labels.length; i++) {
            var x = xScale(i);
            var vline = document.createElementNS(ns, 'line');
            vline.setAttribute('x1', x);
            vline.setAttribute('y1', paddingTop);
            vline.setAttribute('x2', x);
            vline.setAttribute('y2', height - paddingBottom);
            grid.appendChild(vline);
        }
        svg.appendChild(grid);

        var yLabels = document.createElementNS(ns, 'g');
        yLabels.setAttribute('class', 'veloria-line-chart__axis-labels veloria-line-chart__axis-label');
        for (i = 0; i < yTicks.length; i++) {
            var text = document.createElementNS(ns, 'text');
            text.setAttribute('x', paddingLeft - 8);
            text.setAttribute('y', yScale(yTicks[i]) + 4);
            text.setAttribute('text-anchor', 'end');
            text.textContent = yTicks[i] + ySuffix;
            yLabels.appendChild(text);
        }
        svg.appendChild(yLabels);

        var xLabels = document.createElementNS(ns, 'g');
        xLabels.setAttribute('class', 'veloria-line-chart__axis-labels veloria-line-chart__axis-label');
        for (i = 0; i < labels.length; i++) {
            var xtext = document.createElementNS(ns, 'text');
            xtext.setAttribute('x', xScale(i));
            xtext.setAttribute('y', height - paddingBottom + 20);
            xtext.setAttribute('text-anchor', 'middle');
            xtext.textContent = labels[i];
            xLabels.appendChild(xtext);
        }
        svg.appendChild(xLabels);

        var pathD = values.map(function (v, i) {
            return (i === 0 ? 'M' : 'L') + xScale(i) + ',' + yScale(v);
        }).join(' ');
        var path = document.createElementNS(ns, 'path');
        path.setAttribute('class', 'veloria-line-chart__line');
        path.setAttribute('d', pathD);
        path.setAttribute('fill', 'none');
        path.setAttribute('stroke-width', '2');
        path.setAttribute('stroke-linecap', 'round');
        path.setAttribute('stroke-linejoin', 'round');
        svg.appendChild(path);

        var points = document.createElementNS(ns, 'g');
        points.setAttribute('class', 'veloria-line-chart__points');
        for (i = 0; i < values.length; i++) {
            var circle = document.createElementNS(ns, 'circle');
            circle.setAttribute('class', 'veloria-line-chart__point');
            circle.setAttribute('cx', xScale(i));
            circle.setAttribute('cy', yScale(values[i]));
            circle.setAttribute('r', '4');
            circle.setAttribute('stroke-width', '2');
            points.appendChild(circle);
        }
        svg.appendChild(points);

        canvas.innerHTML = '';
        canvas.appendChild(svg);
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.veloria-line-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.veloria-line-chart__canvas');
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
                    if (node.classList && node.classList.contains('veloria-line-chart')) {
                        containers.push(node);
                    }
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.veloria-line-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        var canvas = container.querySelector('.veloria-line-chart__canvas');
                        var needsBuild = canvas && !container.querySelector('.veloria-line-chart__svg');
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
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-line-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.veloria-line-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
