(function () {
    'use strict';

    var SELECTOR_WRAP = '.veloria-asvg';
    var CLASS_DRAW = 'veloria-asvg--anim-draw';
    var ATTR_MARK = 'data-veloria-draw';
    var ATTR_OLD_FILL = 'data-veloria-draw-old-fill';
    var ATTR_OLD_STROKE = 'data-veloria-draw-old-stroke';
    var ATTR_OLD_SW = 'data-veloria-draw-old-stroke-width';
    var drawObserver = null;

    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function parseCssSeconds(raw, fallback) {
        if (raw === null || raw === undefined) {
            return fallback;
        }
        var val = String(raw).trim();
        if (val === '') {
            return fallback;
        }
        var m = val.match(/^([\d.]+)s$/i);
        if (m) {
            var n = parseFloat(m[1]);
            return isNaN(n) ? fallback : n;
        }
        var p = parseFloat(val);
        return isNaN(p) ? fallback : p;
    }

    function sanitizeEaseName(raw) {
        var s = String(raw || 'ease-in-out').trim() || 'ease-in-out';
        var allowed = { ease: 1, 'ease-in': 1, 'ease-out': 1, 'ease-in-out': 1, linear: 1 };
        return allowed[s] ? s : 'ease-in-out';
    }

    function readWrapTiming(wrap) {
        var rawDur = wrap.getAttribute('data-asvg-dur');
        if (rawDur !== null && rawDur !== '') {
            var iterRaw = String(wrap.getAttribute('data-asvg-iter') || '1').trim();
            var easeRaw = sanitizeEaseName(wrap.getAttribute('data-asvg-ease'));
            return {
                delay: Math.max(0, parseFloat(wrap.getAttribute('data-asvg-delay')) || 0),
                dur: Math.max(0.05, parseFloat(rawDur) || 1.6),
                fillDur: Math.max(0, parseFloat(wrap.getAttribute('data-asvg-fill-dur')) || 0),
                stagger: Math.max(0, parseFloat(wrap.getAttribute('data-asvg-stagger')) || 0),
                iter: iterRaw,
                ease: easeRaw,
            };
        }
        var root = wrap.closest('.elementor-element') || wrap;
        var cs = window.getComputedStyle(root);
        return {
            delay: parseCssSeconds(cs.getPropertyValue('--e-asvg-delay'), 0),
            dur: parseCssSeconds(cs.getPropertyValue('--e-asvg-dur'), 1.6),
            fillDur: parseCssSeconds(cs.getPropertyValue('--e-asvg-draw-fill-dur'), 0.75),
            stagger: parseCssSeconds(cs.getPropertyValue('--e-asvg-draw-stagger'), 0.12),
            iter: String(cs.getPropertyValue('--e-asvg-iter') || '1').trim(),
            ease: sanitizeEaseName(cs.getPropertyValue('--e-asvg-ease')),
        };
    }

    function strokeIterationsFromTiming(timing) {
        if (timing.iter === 'infinite') {
            return Infinity;
        }
        var n = parseInt(timing.iter, 10);
        return isNaN(n) || n < 1 ? 1 : n;
    }

    function hasVisibleFill(cs) {
        var f = cs.fill;
        if (!f || f === 'none') {
            return false;
        }
        var m = f.match(/rgba\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+\s*,\s*([\d.]+)\s*\)/i);
        if (m && parseFloat(m[1]) === 0) {
            return false;
        }
        return true;
    }

    function cancelElementAnimations(el) {
        if (typeof el.getAnimations !== 'function') {
            return;
        }
        var list = el.getAnimations();
        for (var i = 0; i < list.length; i += 1) {
            try {
                list[i].cancel();
            } catch (e) {
                /* ignore */
            }
        }
    }

    function restoreDrawMarkedElement(el) {
        if (!el) {
            return;
        }
        el.style.removeProperty('stroke-dasharray');
        el.style.removeProperty('stroke-dashoffset');
        el.style.removeProperty('--draw-stroke-delay');
        el.style.removeProperty('--draw-fill-delay');
        el.style.removeProperty('fill-opacity');
        el.style.removeProperty('vector-effect');
        el.style.removeProperty('stroke-linecap');
        el.style.removeProperty('stroke-linejoin');

        var oldFill = el.getAttribute(ATTR_OLD_FILL);
        el.removeAttribute(ATTR_OLD_FILL);
        el.style.removeProperty('fill');
        if (oldFill === null || oldFill === '') {
            el.removeAttribute('fill');
        } else {
            el.setAttribute('fill', oldFill);
        }

        var oldStroke = el.getAttribute(ATTR_OLD_STROKE);
        el.removeAttribute(ATTR_OLD_STROKE);
        el.style.removeProperty('stroke');
        if (oldStroke === null || oldStroke === '') {
            el.removeAttribute('stroke');
        } else {
            el.setAttribute('stroke', oldStroke);
        }

        var oldSw = el.getAttribute(ATTR_OLD_SW);
        el.removeAttribute(ATTR_OLD_SW);
        el.style.removeProperty('stroke-width');
        if (oldSw === null || oldSw === '') {
            el.removeAttribute('stroke-width');
        } else {
            el.setAttribute('stroke-width', oldSw);
        }

        el.removeAttribute(ATTR_MARK);
    }

    function finalizeDrawShapeNode(node, strokeAnim, fillAnim) {
        if (!node || node.getAttribute(ATTR_MARK) !== '1') {
            return;
        }
        if (strokeAnim) {
            try {
                if (typeof strokeAnim.commitStyles === 'function') {
                    strokeAnim.commitStyles();
                }
            } catch (e) {
                /* ignore */
            }
            try {
                strokeAnim.cancel();
            } catch (e2) {
                /* ignore */
            }
        }
        if (fillAnim) {
            try {
                if (typeof fillAnim.commitStyles === 'function') {
                    fillAnim.commitStyles();
                }
            } catch (e) {
                /* ignore */
            }
            try {
                fillAnim.cancel();
            } catch (e2) {
                /* ignore */
            }
        }
        restoreDrawMarkedElement(node);
    }

    function resetDrawWidget(wrap) {
        if (!wrap) {
            return;
        }
        var marked = wrap.querySelectorAll('[' + ATTR_MARK + '="1"]');
        for (var i = 0; i < marked.length; i += 1) {
            cancelElementAnimations(marked[i]);
            restoreDrawMarkedElement(marked[i]);
        }
    }

    function isElementMostlyVisible(el) {
        if (!el || typeof el.getBoundingClientRect !== 'function') {
            return false;
        }
        var rect = el.getBoundingClientRect();
        var viewH = window.innerHeight || document.documentElement.clientHeight || 0;
        var visibleHeight = Math.min(rect.bottom, viewH) - Math.max(rect.top, 0);
        var ratio = rect.height > 0 ? visibleHeight / rect.height : 0;
        return ratio >= 0.2;
    }

    function ensureDrawObserver() {
        if (drawObserver || typeof IntersectionObserver === 'undefined') {
            return drawObserver;
        }
        drawObserver = new IntersectionObserver(
            function (entries) {
                for (var i = 0; i < entries.length; i += 1) {
                    var entry = entries[i];
                    if (!entry || !entry.target) {
                        continue;
                    }
                    var target = entry.target;
                    if (entry.isIntersecting || entry.intersectionRatio > 0.2) {
                        try {
                            drawObserver.unobserve(target);
                        } catch (e) {
                            /* ignore */
                        }
                        initDrawWidget(target);
                    }
                }
            },
            { threshold: [0.2], root: null, rootMargin: '0px 0px -5% 0px' }
        );
        return drawObserver;
    }

    function startDrawWhenVisible(wrap) {
        if (!wrap || !wrap.classList.contains(CLASS_DRAW)) {
            return;
        }
        resetDrawWidget(wrap);
        if (isElementMostlyVisible(wrap) || typeof IntersectionObserver === 'undefined') {
            initDrawWidget(wrap);
            return;
        }
        var observer = ensureDrawObserver();
        if (!observer) {
            initDrawWidget(wrap);
            return;
        }
        try {
            observer.unobserve(wrap);
        } catch (e) {
            /* ignore */
        }
        observer.observe(wrap);
    }

    function initDrawWidget(wrap) {
        if (!wrap || !wrap.classList.contains(CLASS_DRAW)) {
            return;
        }
        var inner = wrap.querySelector('.veloria-asvg__inner');
        if (!inner) {
            return;
        }
        var svg = inner.querySelector('svg');
        if (!svg) {
            return;
        }

        resetDrawWidget(wrap);

        var timing = readWrapTiming(wrap);
        var strokeIter = strokeIterationsFromTiming(timing);
        var shapes = svg.querySelectorAll('path,circle,rect,ellipse,line,polyline,polygon');
        var order = 0;
        var drawn = 0;

        for (var i = 0; i < shapes.length; i += 1) {
            var el = shapes[i];
            if (el.closest && el.closest('defs')) {
                continue;
            }
            var len = 0;
            try {
                if (typeof el.getTotalLength === 'function') {
                    len = el.getTotalLength();
                }
            } catch (e) {
                len = 0;
            }
            if (!len || len < 2) {
                continue;
            }

            var cs = window.getComputedStyle(el);
            var fillAttr = el.getAttribute('fill');
            var strokeAttr = el.getAttribute('stroke');
            var strokeWAttr = el.getAttribute('stroke-width');

            el.setAttribute(ATTR_OLD_FILL, fillAttr === null ? '' : fillAttr);
            el.setAttribute(ATTR_OLD_STROKE, strokeAttr === null ? '' : strokeAttr);
            el.setAttribute(ATTR_OLD_SW, strokeWAttr === null ? '' : strokeWAttr);

            var fillVisible = hasVisibleFill(cs);
            var strokeW = parseFloat(cs.strokeWidth);
            var hasStroke = cs.stroke && cs.stroke !== 'none' && !isNaN(strokeW) && strokeW > 0;
            var strokeDraw = hasStroke ? cs.stroke : null;
            if (!strokeDraw || strokeDraw === 'none') {
                strokeDraw = fillVisible ? cs.fill : '#333333';
            }

            el.style.stroke = strokeDraw;
            var sw = hasStroke && !isNaN(strokeW) && strokeW > 0 ? cs.strokeWidth : '1.75px';
            el.style.strokeWidth = sw;
            el.style.vectorEffect = 'non-scaling-stroke';
            el.style.strokeLinecap = 'round';
            el.style.strokeLinejoin = 'round';

            el.style.strokeDasharray = String(len);
            el.style.strokeDashoffset = String(len);

            if (fillVisible) {
                el.style.fill = cs.fill;
                el.style.fillOpacity = '0';
            }

            el.setAttribute(ATTR_MARK, '1');
            order += 1;
            drawn += 1;
        }

        if (drawn === 0) {
            return;
        }

        if (prefersReducedMotion()) {
            for (var j = 0; j < shapes.length; j += 1) {
                var s = shapes[j];
                if (s.getAttribute(ATTR_MARK) === '1') {
                    restoreDrawMarkedElement(s);
                }
            }
            return;
        }

        if (typeof svg.animate !== 'function') {
            for (var k = 0; k < shapes.length; k += 1) {
                var sk = shapes[k];
                if (sk.getAttribute(ATTR_MARK) === '1') {
                    restoreDrawMarkedElement(sk);
                }
            }
            return;
        }

        function runStrokeFillAnimations() {
        var strokeCycles = strokeIter === Infinity ? 1 : Math.max(1, strokeIter);
        order = 0;
        for (var n = 0; n < shapes.length; n += 1) {
            var node = shapes[n];
            if (node.getAttribute(ATTR_MARK) !== '1') {
                continue;
            }
            var pathLen = 0;
            try {
                if (typeof node.getTotalLength === 'function') {
                    pathLen = node.getTotalLength();
                }
            } catch (e4) {
                pathLen = 0;
            }
            if (!pathLen || pathLen < 2) {
                continue;
            }

            var delayMs = (timing.delay + order * timing.stagger) * 1000;
            var durMs = timing.dur * 1000;
            var fillDurMs = Math.max(0, timing.fillDur) * 1000;
            var csNode = window.getComputedStyle(node);
            var nodeHasFill = hasVisibleFill(csNode);
            var fillDelayMs = delayMs + durMs * strokeCycles;

            var strokeAnim = null;
            var fillAnim = null;

            try {
                strokeAnim = node.animate(
                    [{ strokeDashoffset: String(pathLen) }, { strokeDashoffset: '0' }],
                    {
                        duration: durMs,
                        delay: delayMs,
                        easing: timing.ease,
                        fill: 'forwards',
                        iterations: strokeIter,
                    }
                );
            } catch (e1) {
                try {
                    strokeAnim = node.animate(
                        [{ strokeDashoffset: String(pathLen) }, { strokeDashoffset: '0' }],
                        {
                            duration: durMs,
                            delay: delayMs,
                            easing: 'ease-in-out',
                            fill: 'forwards',
                            iterations: strokeIter,
                        }
                    );
                } catch (e2) {
                    node.style.strokeDashoffset = '0';
                }
            }

            if (!strokeAnim) {
                if (nodeHasFill) {
                    node.style.fillOpacity = '1';
                }
                if (strokeIter !== Infinity) {
                    restoreDrawMarkedElement(node);
                }
                order += 1;
                continue;
            }

            if (nodeHasFill && fillDurMs > 0) {
                try {
                    fillAnim = node.animate(
                        [{ fillOpacity: 0 }, { fillOpacity: 1 }],
                        {
                            duration: fillDurMs,
                            delay: fillDelayMs,
                            easing: 'ease-out',
                            fill: 'forwards',
                            iterations: 1,
                        }
                    );
                } catch (e3) {
                    node.style.fillOpacity = '1';
                }
            } else if (nodeHasFill) {
                node.style.fillOpacity = '1';
            }

            if (strokeIter !== Infinity) {
                var strokeFin = strokeAnim.finished;
                var fillFin =
                    fillAnim && fillAnim.finished && typeof fillAnim.finished.then === 'function'
                        ? fillAnim.finished
                        : Promise.resolve();
                var both =
                    strokeFin && typeof strokeFin.then === 'function'
                        ? Promise.all([strokeFin, fillFin])
                        : new Promise(function (resolve) {
                            window.setTimeout(
                                resolve,
                                Math.ceil(delayMs + durMs * strokeCycles + (fillAnim ? fillDurMs : 0) + 100)
                            );
                        });
                both.then(
                    function () {
                        finalizeDrawShapeNode(node, strokeAnim, fillAnim);
                    },
                    function () {
                        finalizeDrawShapeNode(node, strokeAnim, fillAnim);
                    }
                );
            }

            order += 1;
        }
        }

        requestAnimationFrame(function () {
            requestAnimationFrame(runStrokeFillAnimations);
        });
    }

    function onWidgetReady($scope) {
        var el = $scope && $scope[0] ? $scope[0] : null;
        if (!el) {
            return;
        }
        var list = el.querySelectorAll(SELECTOR_WRAP);
        for (var i = 0; i < list.length; i += 1) {
            var wrap = list[i];
            if (wrap.classList.contains(CLASS_DRAW)) {
                startDrawWhenVisible(wrap);
            } else {
                resetDrawWidget(wrap);
            }
        }
    }

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
            return;
        }
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-animated-svg.default', onWidgetReady);
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-animated-svg', onWidgetReady);
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        bindElementor();
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(bindElementor, 0);
        });
        window.addEventListener('load', function () {
            setTimeout(bindElementor, 0);
        });
    }
})();
