(function () {
    'use strict';

    function buildChart(container) {
        if (container.getAttribute('data-chart-built') === '1') return;
        container.setAttribute('data-chart-built', '1');

        var labelsRaw = (container.getAttribute('data-labels') || '').split('|');
        var valuesStr = (container.getAttribute('data-values') || '').split('|');
        var colorsStr = (container.getAttribute('data-colors') || '').split('|');
        var values = valuesStr.map(function (v) { return parseFloat(v, 10) || 0; });
        var total = values.reduce(function (a, b) { return a + b; }, 0);
        if (total <= 0) total = 1;
        var labels = [];
        var colors = [];
        var i;
        for (i = 0; i < values.length; i++) {
            labels.push(labelsRaw[i] !== undefined ? labelsRaw[i] : '');
            colors.push(colorsStr[i] && colorsStr[i].trim() ? colorsStr[i].trim() : '#2563eb');
        }

        if (values.length === 0) return;

        var canvas = container.querySelector('.veloria-pie-chart__canvas');
        var legendEl = container.querySelector('.veloria-pie-chart__legend');
        if (!canvas) return;

        var size = 280;
        var cx = size / 2;
        var cy = size / 2;
        var r = size / 2 - 8;

        function polarToCart(angleRad) {
            return { x: cx + r * Math.sin(angleRad), y: cy - r * Math.cos(angleRad) };
        }

        var ns = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(ns, 'svg');
        svg.setAttribute('viewBox', '0 0 ' + size + ' ' + size);
        svg.setAttribute('class', 'veloria-pie-chart__svg');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        var startAngle = -Math.PI / 2;
        var slices = document.createElementNS(ns, 'g');
        slices.setAttribute('class', 'veloria-pie-chart__slices');

        for (i = 0; i < values.length; i++) {
            var pct = values[i] / total;
            var endAngle = startAngle + pct * 2 * Math.PI;
            var p1 = polarToCart(startAngle);
            var p2 = polarToCart(endAngle);
            var large = pct > 0.5 ? 1 : 0;
            var d = 'M ' + cx + ' ' + cy + ' L ' + p1.x + ' ' + p1.y + ' A ' + r + ' ' + r + ' 0 ' + large + ' 1 ' + p2.x + ' ' + p2.y + ' Z';
            var path = document.createElementNS(ns, 'path');
            path.setAttribute('d', d);
            path.setAttribute('fill', colors[i]);
            slices.appendChild(path);

            var midAngle = (startAngle + endAngle) / 2;
            var labelDist = r * 0.65;
            var tx = cx + labelDist * Math.sin(midAngle);
            var ty = cy - labelDist * Math.cos(midAngle);
            var pctText = Math.round(values[i]) + '%';
            var text = document.createElementNS(ns, 'text');
            text.setAttribute('x', tx);
            text.setAttribute('y', ty);
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('dominant-baseline', 'middle');
            text.setAttribute('class', 'veloria-pie-chart__slice-label');
            text.setAttribute('fill', '#fff');
            text.setAttribute('font-size', '14');
            text.textContent = pctText;
            slices.appendChild(text);

            startAngle = endAngle;
        }
        svg.appendChild(slices);

        canvas.innerHTML = '';
        canvas.appendChild(svg);

        if (legendEl) {
            legendEl.innerHTML = '';
            for (i = 0; i < labels.length; i++) {
                var item = document.createElement('div');
                item.setAttribute('class', 'veloria-pie-chart__legend-item');
                var swatch = document.createElement('span');
                swatch.setAttribute('class', 'veloria-pie-chart__legend-swatch');
                swatch.style.backgroundColor = colors[i];
                var labelSpan = document.createElement('span');
                labelSpan.setAttribute('class', 'veloria-pie-chart__legend-text');
                labelSpan.textContent = labels[i];
                item.appendChild(swatch);
                item.appendChild(labelSpan);
                legendEl.appendChild(item);
            }
        }
    }

    function run(root) {
        root = root || document;
        var containers = root.querySelectorAll('.veloria-pie-chart');
        containers.forEach(function (el) {
            if (el.getAttribute('data-chart-built') === '1') return;
            var canvas = el.querySelector('.veloria-pie-chart__canvas');
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
                    if (node.classList && node.classList.contains('veloria-pie-chart')) {
                        containers.push(node);
                    }
                    if (node.querySelectorAll) {
                        var found = node.querySelectorAll('.veloria-pie-chart');
                        for (var i = 0; i < found.length; i++) containers.push(found[i]);
                    }
                    containers.forEach(function (container) {
                        var canvas = container.querySelector('.veloria-pie-chart__canvas');
                        var needsBuild = canvas && !container.querySelector('.veloria-pie-chart__svg');
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
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-pie-chart.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var containers = el.querySelectorAll('.veloria-pie-chart');
                containers.forEach(function (c) {
                    c.removeAttribute('data-chart-built');
                });
                run(el);
            }
        });
    }
})();
