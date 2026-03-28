(function () {
    'use strict';

    function parseSettings(raw) {
        try {
            return JSON.parse(raw || '{}');
        } catch (e) {
            return {};
        }
    }

    function truthy(val) {
        return val === 'yes' || val === true || val === 'true' || val === '1';
    }

    function throttleRaf(fn) {
        var scheduled = false;
        return function () {
            if (scheduled) {
                return;
            }
            scheduled = true;
            requestAnimationFrame(function () {
                scheduled = false;
                fn();
            });
        };
    }

    function updateViewportScroll(animation, container, settings) {
        if (!animation || !container) {
            return;
        }
        var rect = container.getBoundingClientRect();
        var vh = document.documentElement.clientHeight || window.innerHeight || 1;
        var topPct = (rect.top / vh) * 100;
        var bottomPct = (rect.bottom / vh) * 100;
        var start = parseFloat(settings.scroll_start);
        var end = parseFloat(settings.scroll_end);
        if (isNaN(start)) {
            start = 0;
        }
        if (isNaN(end)) {
            end = 100;
        }
        var inBand = bottomPct > start && topPct < end;

        if (settings.trigger === 'viewport') {
            if (inBand) {
                animation.play();
            } else {
                animation.pause();
            }
            return;
        }

        if (settings.trigger === 'scroll') {
            if (!inBand) {
                animation.pause();
                return;
            }
            var doc = document.documentElement;
            var scrollTop = window.scrollY != null ? window.scrollY : doc.scrollTop;
            var maxScroll = Math.max(1, doc.scrollHeight - window.innerHeight);
            var pct = scrollTop / maxScroll;
            var total = animation.totalFrames;
            if (!total || total < 1) {
                total = 1;
            }
            var frame = pct * total;
            frame = Math.max(0, Math.min(total - 1, frame));
            animation.goToAndStop(frame, true);
        }
    }

    function initContainer(container) {
        if (!container || !window.lottie) {
            return;
        }
        if (container.getAttribute('data-lottie-init') === '1') {
            return;
        }

        var jsonUrl = container.getAttribute('data-json-url');
        if (!jsonUrl) {
            return;
        }

        var settings = parseSettings(container.getAttribute('data-settings'));
        var trigger = settings.trigger || 'none';
        var autoplay =
            truthy(settings.autoplay) && (trigger === 'none' || trigger === 'hover');

        var anim = window.lottie.loadAnimation({
            container: container,
            path: jsonUrl,
            renderer: settings.lottie_renderer === 'canvas' ? 'canvas' : 'svg',
            loop: truthy(settings.loop),
            autoplay: autoplay,
        });

        var speed = parseFloat(settings.speed);
        if (!isNaN(speed) && speed > 0) {
            anim.setSpeed(speed);
        }

        if (truthy(settings.reverse)) {
            anim.setDirection(-1);
        }

        var scrollHandler;
        var boundTick;

        function teardown() {
            if (scrollHandler) {
                window.removeEventListener('scroll', scrollHandler, true);
                window.removeEventListener('resize', scrollHandler, true);
                scrollHandler = null;
            }
            if (container._elementrifyLottieHoverEnter) {
                container.removeEventListener('mouseenter', container._elementrifyLottieHoverEnter);
                delete container._elementrifyLottieHoverEnter;
            }
            if (container._elementrifyLottieHoverLeave) {
                container.removeEventListener('mouseleave', container._elementrifyLottieHoverLeave);
                delete container._elementrifyLottieHoverLeave;
            }
            if (anim && typeof anim.destroy === 'function') {
                anim.destroy();
            }
            container.removeAttribute('data-lottie-init');
        }

        container._elementrifyLottieTeardown = teardown;

        anim.addEventListener('DOMLoaded', function () {
            if (trigger === 'hover') {
                anim.pause();
                var onEnter = function () {
                    anim.play();
                };
                var onLeave = function () {
                    anim.pause();
                };
                container._elementrifyLottieHoverEnter = onEnter;
                container._elementrifyLottieHoverLeave = onLeave;
                container.addEventListener('mouseenter', onEnter);
                container.addEventListener('mouseleave', onLeave);
                return;
            }

            if (trigger === 'viewport' || trigger === 'scroll') {
                boundTick = throttleRaf(function () {
                    updateViewportScroll(anim, container, settings);
                });
                scrollHandler = boundTick;
                window.addEventListener('scroll', scrollHandler, true);
                window.addEventListener('resize', scrollHandler, true);
                boundTick();
            }
        });

        container.setAttribute('data-lottie-init', '1');
    }

    function destroyContainer(container) {
        if (container && typeof container._elementrifyLottieTeardown === 'function') {
            container._elementrifyLottieTeardown();
            delete container._elementrifyLottieTeardown;
        }
    }

    function scan(scope) {
        var root = scope || document;
        var nodes = root.querySelectorAll('.elementrify-lottie-animation');
        for (var i = 0; i < nodes.length; i += 1) {
            initContainer(nodes[i]);
        }
    }

    function prepareScope(scope) {
        var nodes = scope.querySelectorAll('.elementrify-lottie-animation');
        for (var i = 0; i < nodes.length; i += 1) {
            destroyContainer(nodes[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            scan(document);
        });
    } else {
        scan(document);
    }

    function bindElementor() {
        if (
            typeof elementorFrontend === 'undefined' ||
            !elementorFrontend.hooks ||
            !elementorFrontend.hooks.addAction
        ) {
            return;
        }
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/elementrify-lottie-animation.default',
            function ($scope) {
                var el = $scope && $scope[0] ? $scope[0] : null;
                if (!el) {
                    return;
                }
                prepareScope(el);
                scan(el);
            }
        );
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
        bindElementor();
    } else {
        window.addEventListener('load', function () {
            setTimeout(bindElementor, 0);
        });
    }
})();
