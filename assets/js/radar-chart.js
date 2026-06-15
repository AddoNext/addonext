(function () {
    'use strict';

    function clamp(v, min, max) {
        return Math.max(min, Math.min(max, v));
    }

    function parseValues(raw) {
        return (raw || '').split('|').map(function (v) {
            var n = parseFloat(v, 10);
            return isNaN(n) ? 0 : n;
        });
    }

    function parseLabels(raw) {
        return (raw || '').split('|').map(function (v) {
            return v || '';
        });
    }

    function pointAt(cx, cy, radius, angle) {
        return {
            x: cx + radius * Math.sin(angle),
            y: cy - radius * Math.cos(angle)
        };
    }

    function pointsToPath(points) {
        if (!points.length) return '';
        var d = 'M ' + points[0].x + ' ' + points[0].y;
        for (var i = 1; i < points.length; i++) {
            d += ' L ' + points[i].x + ' ' + points[i].y;
        }
        return d + ' Z';
    }

    function buildChart(container) {
        if (container.getAttribute('data-chart-built') === '1') return;
        container.setAttribute('data-chart-built', '1');

        var labels = parseLabels(container.getAttribute('data-labels'));
        var valuesA = parseValues(container.getAttribute('data-values-a'));
        var valuesB = parseValues(container.getAttribute('data-values-b'));

        var n = Math.max(labels.length, valuesA.length, valuesB.length);
        if (n < 3) return;

        while (labels.length < n) labels.push('');
        while (valuesA.length < n) valuesA.push(0);
        while (valuesB.length < n) valuesB.push(0);

        var maxValueAttr = parseFloat(container.getAttribute('data-max-value'), 10);
        var autoMax = Math.max.apply(null, valuesA.concat(valuesB, [100]));
        var maxValue = isNaN(maxValueAttr) || maxValueAttr <= 0 ? autoMax : maxValueAttr;
        if (maxValue <= 0) maxValue = 100;

        var seriesAName = container.getAttribute('data-series-a-name') || 'Series A';
        var seriesBName = container.getAttribute('data-series-b-name') || 'Series B';
        var colorA = container.getAttribute('data-color-a') || '#4285F4';
        var colorB = container.getAttribute('data-color-b') || '#24B47E';

        var canvas = container.querySelector('.veloria-radar-chart__canvas');
        var legend = container.querySelector('.veloria-radar-chart__legend');
        if (!canvas) return;

        var size = 420;
        var cx = 190;
        var cy = 205;
        var radius = 135;
        var steps = 5;

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + size + ' ' + size);
        svg.setAttribute('class', 'veloria-radar-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var grid = document.createElementNS(ns, 'g');
        grid.setAttribute('class', 'veloria-radar-chart__grid');

        var axisLines = document.createElementNS(ns, 'g');
        axisLines.setAttribute('class', 'veloria-radar-chart__axes');

        var labelsG = document.createElementNS(ns, 'g');
        labelsG.setAttribute('class', 'veloria-radar-chart__axis-label');

        var valueLabelsG = document.createElementNS(ns, 'g');
        valueLabelsG.setAttribute('class', 'veloria-radar-chart__tick-label');

        var i;
        for (i = 1; i <= steps; i++) {
            var r = radius * (i / steps);
            var ringPoints = [];
            for (var j = 0; j < n; j++) {
                var angle = (Math.PI * 2 * j) / n;
                ringPoints.push(pointAt(cx, cy, r, angle));
            }
            var ring = document.createElementNS(ns, 'polygon');
            ring.setAttribute('points', ringPoints.map(function (p) { return p.x + ',' + p.y; }).join(' '));
            grid.appendChild(ring);

            var tick = document.createElementNS(ns, 'text');
            tick.setAttribute('x', cx + 6);
            tick.setAttribute('y', cy - r + 4);
            tick.textContent = String(Math.round((maxValue * i) / steps));
            valueLabelsG.appendChild(tick);
        }

        for (i = 0; i < n; i++) {
            var a = (Math.PI * 2 * i) / n;
            var outer = pointAt(cx, cy, radius, a);

            var axis = document.createElementNS(ns, 'line');
            axis.setAttribute('x1', cx);
            axis.setAttribute('y1', cy);
            axis.setAttribute('x2', outer.x);
            axis.setAttribute('y2', outer.y);
            axisLines.appendChild(axis);

            var lbl = document.createElementNS(ns, 'text');
            var labelOffset = 16;
            var lp = pointAt(cx, cy, radius + labelOffset, a);
            lbl.setAttribute('x', lp.x);
            lbl.setAttribute('y', lp.y);
            lbl.textContent = labels[i];

            if (Math.abs(Math.sin(a)) < 0.2) {
                lbl.setAttribute('text-anchor', 'middle');
            } else if (Math.sin(a) > 0) {
                lbl.setAttribute('text-anchor', 'start');
            } else {
                lbl.setAttribute('text-anchor', 'end');
            }
            labelsG.appendChild(lbl);
        }

        svg.appendChild(grid);
        svg.appendChild(axisLines);
        svg.appendChild(valueLabelsG);

        function seriesPoints(values) {
            var pts = [];
            for (var k = 0; k < n; k++) {
                var val = clamp(values[k], 0, maxValue);
                var rr = radius * (val / maxValue);
                var ang = (Math.PI * 2 * k) / n;
                pts.push(pointAt(cx, cy, rr, ang));
            }
            return pts;
        }

        var aPts = seriesPoints(valuesA);
        var bPts = seriesPoints(valuesB);

        var seriesGroup = document.createElementNS(ns, 'g');
        seriesGroup.setAttribute('class', 'veloria-radar-chart__series');

        function drawSeries(points, color, cls) {
            var poly = document.createElementNS(ns, 'path');
            poly.setAttribute('class', cls + ' veloria-radar-chart__series-fill');
            poly.setAttribute('d', pointsToPath(points));
            poly.setAttribute('fill', color);
            poly.setAttribute('fill-opacity', '0.28');
            poly.setAttribute('stroke', color);
            poly.setAttribute('stroke-width', '2');
            seriesGroup.appendChild(poly);

            for (var p = 0; p < points.length; p++) {
                var c = document.createElementNS(ns, 'circle');
                c.setAttribute('class', cls + ' veloria-radar-chart__point');
                c.setAttribute('cx', points[p].x);
                c.setAttribute('cy', points[p].y);
                c.setAttribute('r', '4');
                c.setAttribute('fill', color);
                c.setAttribute('stroke', color);
                c.setAttribute('stroke-width', '1.5');
                seriesGroup.appendChild(c);
            }
        }

        drawSeries(aPts, colorA, 'veloria-radar-chart__series-a');
        drawSeries(bPts, colorB, 'veloria-radar-chart__series-b');

        svg.appendChild(seriesGroup);
        svg.appendChild(labelsG);

        canvas.innerHTML = '';
        canvas.appendChild(svg);

        if (legend) {
            legend.innerHTML = '';
            var items = [
                { label: seriesAName, color: colorA },
                { label: seriesBName, color: colorB }
            ];
            items.forEach(function (item) {
                var el = document.createElement('div');
                el.setAttribute('class', 'veloria-radar-chart__legend-item');

                var swatch = document.createElement('span');
                swatch.setAttribute('class', 'veloria-radar-chart__legend-line');
                swatch.style.setProperty('--er-radar-legend-color', item.color);

                var txt = document.createElement('span');
                txt.setAttribute('class', 'veloria-radar-chart__legend-text');
                txt.textContent = item.label;

                el.appendChild(swatch);
                el.appendChild(txt);
                legend.appendChild(el);
            });
        }
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.veloria-radar-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.veloria-radar-chart__canvas');
            if (canvas) buildChart(el);
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
                    if (node.classList && node.classList.contains('veloria-radar-chart')) containers.push(node);
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.veloria-radar-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        container.removeAttribute('data-chart-built');
                        buildChart(container);
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
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-radar-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.veloria-radar-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
