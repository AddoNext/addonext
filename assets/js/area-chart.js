(function () {
    'use strict';

    function parseNum(val) {
        var n = parseFloat(val, 10);
        return isNaN(n) ? null : n;
    }

    function buildLinePathLinear(xScale, yScale, values) {
        return values.map(function (v, i) {
            return (i === 0 ? 'M' : 'L') + xScale(i) + ',' + yScale(v);
        }).join(' ');
    }

    function buildLinePathSmooth(xScale, yScale, values) {
        var n = values.length;
        if (n < 2) {
            return 'M' + xScale(0) + ',' + yScale(values[0]);
        }
        var points = [];
        var i;
        for (i = 0; i < n; i++) {
            points.push({ x: xScale(i), y: yScale(values[i]) });
        }
        var d = 'M' + points[0].x + ',' + points[0].y;
        for (i = 0; i < n - 1; i++) {
            var p0 = i > 0 ? points[i - 1] : points[0];
            var p1 = points[i];
            var p2 = points[i + 1];
            var p3 = i < n - 2 ? points[i + 2] : points[n - 1];
            var cp1x = p1.x + (p2.x - p0.x) / 6;
            var cp1y = p1.y + (p2.y - p0.y) / 6;
            var cp2x = p2.x - (p3.x - p1.x) / 6;
            var cp2y = p2.y - (p3.y - p1.y) / 6;
            d += ' C ' + cp1x + ',' + cp1y + ' ' + cp2x + ',' + cp2y + ' ' + p2.x + ',' + p2.y;
        }
        return d;
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
        var smooth = container.getAttribute('data-smooth') !== 'no';
        var lineColor = container.getAttribute('data-line-color') || '#5b9bd5';
        var areaTopOpacity = parseNum(container.getAttribute('data-area-opacity-top'));
        if (areaTopOpacity == null) areaTopOpacity = 0.4;
        var areaBottomOpacity = parseNum(container.getAttribute('data-area-opacity-bottom'));
        if (areaBottomOpacity == null) areaBottomOpacity = 0.03;

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

        var canvas = container.querySelector('.veloria-area-chart__canvas');
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

        var baselineY = paddingTop + chartHeight;

        var linePathD = smooth
            ? buildLinePathSmooth(xScale, yScale, values)
            : buildLinePathLinear(xScale, yScale, values);

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + width + ' ' + height);
        svg.setAttribute('class', 'veloria-area-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var gradId = 'er-ac-' + Math.random().toString(36).slice(2, 11);
        var defs = document.createElementNS(ns, 'defs');
        var lg = document.createElementNS(ns, 'linearGradient');
        lg.setAttribute('id', gradId);
        lg.setAttribute('x1', '0');
        lg.setAttribute('y1', '0');
        lg.setAttribute('x2', '0');
        lg.setAttribute('y2', '1');
        var stop0 = document.createElementNS(ns, 'stop');
        stop0.setAttribute('offset', '0%');
        stop0.setAttribute('stop-color', lineColor);
        stop0.setAttribute('stop-opacity', String(areaTopOpacity));
        var stop1 = document.createElementNS(ns, 'stop');
        stop1.setAttribute('offset', '100%');
        stop1.setAttribute('stop-color', lineColor);
        stop1.setAttribute('stop-opacity', String(areaBottomOpacity));
        lg.appendChild(stop0);
        lg.appendChild(stop1);
        defs.appendChild(lg);
        svg.appendChild(defs);

        var grid = document.createElementNS(ns, 'g');
        grid.setAttribute('class', 'veloria-area-chart__grid');

        var i;
        for (i = 0; i < yTicks.length; i++) {
            var yy = yScale(yTicks[i]);
            var line = document.createElementNS(ns, 'line');
            line.setAttribute('x1', paddingLeft);
            line.setAttribute('y1', yy);
            line.setAttribute('x2', width - paddingRight);
            line.setAttribute('y2', yy);
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
        yLabels.setAttribute('class', 'veloria-area-chart__axis-labels veloria-area-chart__axis-label');
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
        xLabels.setAttribute('class', 'veloria-area-chart__axis-labels veloria-area-chart__axis-label');
        for (i = 0; i < labels.length; i++) {
            var xtext = document.createElementNS(ns, 'text');
            xtext.setAttribute('x', xScale(i));
            xtext.setAttribute('y', height - paddingBottom + 20);
            xtext.setAttribute('text-anchor', 'middle');
            xtext.textContent = labels[i];
            xLabels.appendChild(xtext);
        }
        svg.appendChild(xLabels);

        var xFirst = xScale(0);
        var xLast = xScale(values.length - 1);
        var areaD = linePathD + ' L ' + xLast + ',' + baselineY + ' L ' + xFirst + ',' + baselineY + ' Z';
        var areaPath = document.createElementNS(ns, 'path');
        areaPath.setAttribute('class', 'veloria-area-chart__area');
        areaPath.setAttribute('d', areaD);
        areaPath.setAttribute('fill', 'url(#' + gradId + ')');
        areaPath.setAttribute('stroke', 'none');
        svg.appendChild(areaPath);

        var linePath = document.createElementNS(ns, 'path');
        linePath.setAttribute('class', 'veloria-area-chart__line');
        linePath.setAttribute('d', linePathD);
        linePath.setAttribute('fill', 'none');
        linePath.setAttribute('stroke', lineColor);
        linePath.setAttribute('stroke-width', '2');
        linePath.setAttribute('stroke-linecap', 'round');
        linePath.setAttribute('stroke-linejoin', 'round');
        svg.appendChild(linePath);

        canvas.innerHTML = '';
        canvas.appendChild(svg);
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.veloria-area-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.veloria-area-chart__canvas');
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
                    if (node.classList && node.classList.contains('veloria-area-chart')) {
                        containers.push(node);
                    }
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.veloria-area-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        var canvas = container.querySelector('.veloria-area-chart__canvas');
                        var needsBuild = canvas && !container.querySelector('.veloria-area-chart__svg');
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
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-area-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.veloria-area-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
