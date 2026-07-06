(function () {
    'use strict';

    function clamp(n, min, max) {
        return Math.max(min, Math.min(max, n));
    }

    function isVertical(root) {
        return root.getAttribute('data-ba-orientation') === 'vertical';
    }

    function parseSplit(root) {
        var raw = root.getAttribute('data-initial-split');
        var n = parseFloat(raw);
        if (isNaN(n)) {
            return 50;
        }
        return clamp(n, 0, 100);
    }

    function setSplit(root, afterLayer, divider, handle, pct, state) {
        var p = clamp(pct, 0, 100);
        var vertical = isVertical(root);
        state.last = p;
        root.style.setProperty('--ba-split', p + '%');
        root.style.setProperty('--ba-split-num', String(p));
        if (afterLayer) {
            afterLayer.style.clipPath = vertical
                ? 'inset(' + p + '% 0 0 0)'
                : 'inset(0 0 0 ' + p + '%)';
        }
        if (divider) {
            if (vertical) {
                divider.style.left = '';
                divider.style.right = '';
                divider.style.top = p + '%';
            } else {
                divider.style.top = '';
                divider.style.left = p + '%';
            }
        }
        if (handle) {
            handle.setAttribute('aria-valuenow', String(Math.round(p)));
            handle.setAttribute('aria-orientation', vertical ? 'vertical' : 'horizontal');
        }
    }

    function pctFromPointer(root, frame, clientX, clientY, state) {
        var rect = frame.getBoundingClientRect();
        if (isVertical(root)) {
            if (rect.height <= 0) {
                return state.last;
            }
            return clamp(((clientY - rect.top) / rect.height) * 100, 0, 100);
        }
        if (rect.width <= 0) {
            return state.last;
        }
        return clamp(((clientX - rect.left) / rect.width) * 100, 0, 100);
    }

    function init(root) {
        if (!root || root.getAttribute('data-ba-init') === '1') {
            return;
        }
        root.setAttribute('data-ba-init', '1');

        var frame = root.querySelector('.addonext-before-after__frame');
        var afterLayer = root.querySelector('.addonext-before-after__after');
        var divider = root.querySelector('.addonext-before-after__divider');
        var handle = root.querySelector('.addonext-before-after__handle');

        if (!frame || !afterLayer || !divider || !handle) {
            return;
        }

        var state = { last: parseSplit(root), dragging: false };
        setSplit(root, afterLayer, divider, handle, state.last, state);

        function onHandlePointerDown(ev) {
            if (ev.button !== undefined && ev.button !== 0) {
                return;
            }
            state.dragging = true;
            frame.classList.add('addonext-before-after__frame--dragging');
            try {
                handle.setPointerCapture(ev.pointerId);
            } catch (e) {
                /* ignore */
            }
            setSplit(
                root,
                afterLayer,
                divider,
                handle,
                pctFromPointer(root, frame, ev.clientX, ev.clientY, state),
                state
            );
        }

        function onHandlePointerMove(ev) {
            if (!state.dragging) {
                return;
            }
            setSplit(
                root,
                afterLayer,
                divider,
                handle,
                pctFromPointer(root, frame, ev.clientX, ev.clientY, state),
                state
            );
        }

        function onHandlePointerUp(ev) {
            if (!state.dragging) {
                return;
            }
            state.dragging = false;
            frame.classList.remove('addonext-before-after__frame--dragging');
            try {
                handle.releasePointerCapture(ev.pointerId);
            } catch (e) {
                /* ignore */
            }
        }

        function onFramePointerDown(ev) {
            if (ev.target.closest('.addonext-before-after__handle')) {
                return;
            }
            setSplit(
                root,
                afterLayer,
                divider,
                handle,
                pctFromPointer(root, frame, ev.clientX, ev.clientY, state),
                state
            );
        }

        handle.addEventListener('pointerdown', onHandlePointerDown);
        handle.addEventListener('pointermove', onHandlePointerMove);
        handle.addEventListener('pointerup', onHandlePointerUp);
        handle.addEventListener('pointercancel', onHandlePointerUp);
        frame.addEventListener('pointerdown', onFramePointerDown);

        handle.addEventListener('keydown', function (ev) {
            var step = ev.shiftKey ? 10 : 2;
            var current = state.last;
            var vertical = isVertical(root);
            if (vertical) {
                if (ev.key === 'ArrowUp' || ev.key === 'ArrowLeft') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, current - step, state);
                } else if (ev.key === 'ArrowDown' || ev.key === 'ArrowRight') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, current + step, state);
                } else if (ev.key === 'Home') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, 0, state);
                } else if (ev.key === 'End') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, 100, state);
                }
            } else {
                if (ev.key === 'ArrowLeft' || ev.key === 'ArrowDown') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, current - step, state);
                } else if (ev.key === 'ArrowRight' || ev.key === 'ArrowUp') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, current + step, state);
                } else if (ev.key === 'Home') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, 0, state);
                } else if (ev.key === 'End') {
                    ev.preventDefault();
                    setSplit(root, afterLayer, divider, handle, 100, state);
                }
            }
        });
    }

    function scan(scope) {
        var docRoot = scope || document;
        var nodes = docRoot.querySelectorAll('.addonext-before-after');
        for (var i = 0; i < nodes.length; i += 1) {
            init(nodes[i]);
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
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
            return;
        }
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/addonext-before-after-slider.default',
            function ($scope) {
                var el = $scope && $scope[0] ? $scope[0] : null;
                if (!el) {
                    return;
                }
                var list = el.querySelectorAll('.addonext-before-after');
                for (var i = 0; i < list.length; i += 1) {
                    list[i].removeAttribute('data-ba-init');
                    init(list[i]);
                }
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
