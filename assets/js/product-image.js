(function () {
    'use strict';

    var lightbox;
    var imageEl;
    var closeEl;
    var prevEl;
    var nextEl;
    var currentItems = [];
    var currentIndex = -1;

    function i18n(key, fallback) {
        if (typeof window.veloriaProductImage !== 'undefined' && window.veloriaProductImage[key]) {
            return String(window.veloriaProductImage[key]);
        }
        return fallback;
    }

    function ensureLightbox() {
        if (lightbox) {
            return;
        }
        lightbox = document.createElement('div');
        lightbox.className = 'veloria-product-image-lightbox';
        lightbox.innerHTML =
            '<button type="button" class="veloria-product-image-lightbox__nav veloria-product-image-lightbox__nav--prev">&#8249;</button>' +
            '<button type="button" class="veloria-product-image-lightbox__nav veloria-product-image-lightbox__nav--next">&#8250;</button>' +
            '<button type="button" class="veloria-product-image-lightbox__close">&times;</button>' +
            '<img class="veloria-product-image-lightbox__image" alt="">';
        document.body.appendChild(lightbox);
        imageEl = lightbox.querySelector('.veloria-product-image-lightbox__image');
        closeEl = lightbox.querySelector('.veloria-product-image-lightbox__close');
        prevEl = lightbox.querySelector('.veloria-product-image-lightbox__nav--prev');
        nextEl = lightbox.querySelector('.veloria-product-image-lightbox__nav--next');

        prevEl.setAttribute('aria-label', i18n('prevLabel', 'Previous image'));
        nextEl.setAttribute('aria-label', i18n('nextLabel', 'Next image'));
        closeEl.setAttribute('aria-label', i18n('closeLabel', 'Close preview'));

        closeEl.addEventListener('click', function (event) {
            event.stopPropagation();
            closeLightbox();
        });
        prevEl.addEventListener('click', function (event) {
            event.stopPropagation();
            showAt(currentIndex - 1);
        });
        nextEl.addEventListener('click', function (event) {
            event.stopPropagation();
            showAt(currentIndex + 1);
        });
        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', onLightboxKeydown);
    }

    function onLightboxKeydown(event) {
        if (!lightbox || !lightbox.classList.contains('is-open')) {
            return;
        }
        if (event.key === 'Escape') {
            closeLightbox();
        } else if (event.key === 'ArrowLeft') {
            showAt(currentIndex - 1);
        } else if (event.key === 'ArrowRight') {
            showAt(currentIndex + 1);
        }
    }

    function onPanEscapeKeydown(event) {
        if (event.key !== 'Escape') {
            return;
        }
        var stages = document.querySelectorAll('.veloria-product-image .veloria-pi__stage.is-zoomed');
        for (var i = 0; i < stages.length; i += 1) {
            var st = stages[i];
            var root = st.closest('.veloria-product-image');
            if (root && typeof root.veloriaPiResetPan === 'function') {
                root.veloriaPiResetPan();
            }
        }
    }

    if (!document.veloriaPiEscapeBound) {
        document.veloriaPiEscapeBound = true;
        document.addEventListener('keydown', onPanEscapeKeydown);
    }

    function showAt(index) {
        if (!currentItems.length) {
            return;
        }
        if (index < 0) {
            index = currentItems.length - 1;
        } else if (index >= currentItems.length) {
            index = 0;
        }
        currentIndex = index;
        var item = currentItems[currentIndex];
        imageEl.src = item.full || '';
        imageEl.alt = item.alt || '';
    }

    function openLightbox(items, index) {
        if (!items || !items.length) {
            return;
        }
        ensureLightbox();
        currentItems = items.map(function (row) {
            return {
                full: row.full || '',
                alt: row.alt || '',
            };
        });
        if (currentItems.length === 1) {
            lightbox.classList.add('is-single');
        } else {
            lightbox.classList.remove('is-single');
        }
        showAt(typeof index === 'number' && !isNaN(index) ? index : 0);
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox) {
            return;
        }
        lightbox.classList.remove('is-open');
        imageEl.src = '';
        imageEl.alt = '';
        currentItems = [];
        currentIndex = -1;
        document.body.style.overflow = '';
    }

    function parseImages(root) {
        var raw = root.getAttribute('data-images');
        if (!raw) {
            return [];
        }
        try {
            var parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function getMainImg(root) {
        return root.querySelector('.veloria-pi__main-img');
    }

    function setActiveThumb(root, index) {
        var thumbs = root.querySelectorAll('.veloria-pi__thumb');
        for (var i = 0; i < thumbs.length; i += 1) {
            var t = thumbs[i];
            var isOn = parseInt(t.getAttribute('data-index'), 10) === index;
            t.classList.toggle('is-active', isOn);
            t.setAttribute('aria-pressed', isOn ? 'true' : 'false');
        }
        root.setAttribute('data-active-index', String(index));
    }

    function applyImageIndex(root, images, index) {
        if (!images[index]) {
            return;
        }
        if (typeof root.veloriaPiResetPan === 'function') {
            root.veloriaPiResetPan();
        }
        var main = getMainImg(root);
        if (!main) {
            return;
        }
        var row = images[index];
        main.src = row.main || row.full || '';
        main.alt = row.alt || '';
        setActiveThumb(root, index);
    }

    function bindPanZoom(root) {
        var mainEl = root.querySelector('.veloria-pi__main--pan-zoom');
        if (!mainEl) {
            return;
        }
        var stage = mainEl.querySelector('.veloria-pi__stage');
        var img = root.querySelector('.veloria-pi__main-img');
        if (!stage || !img) {
            return;
        }

        var scale = parseFloat(String(root.getAttribute('data-pan-scale') || '2.25'), 10);
        if (isNaN(scale) || scale < 1.25) {
            scale = 2.25;
        }
        if (scale > 4) {
            scale = 4;
        }

        var state = {
            zoomed: false,
            panX: 0,
            panY: 0,
            pointerDown: false,
            startX: 0,
            startY: 0,
            startPanX: 0,
            startPanY: 0,
            didDrag: false,
        };

        function clampPan() {
            if (!state.zoomed) {
                return;
            }
            var sw = stage.clientWidth;
            var sh = stage.clientHeight;
            var iw = img.offsetWidth;
            var ih = img.offsetHeight;
            if (iw < 1 || ih < 1) {
                return;
            }
            var scaledW = iw * scale;
            var scaledH = ih * scale;
            var maxPanX = Math.max(0, (scaledW - sw) / 2);
            var maxPanY = Math.max(0, (scaledH - sh) / 2);
            state.panX = Math.min(maxPanX, Math.max(-maxPanX, state.panX));
            state.panY = Math.min(maxPanY, Math.max(-maxPanY, state.panY));
        }

        function applyTransform() {
            if (!state.zoomed) {
                img.style.transform = '';
                stage.classList.remove('is-zoomed', 'is-dragging');
                return;
            }
            clampPan();
            img.style.transform = 'translate(' + state.panX + 'px,' + state.panY + 'px) scale(' + scale + ')';
            stage.classList.add('is-zoomed');
        }

        function resetPanZoom() {
            state.zoomed = false;
            state.panX = 0;
            state.panY = 0;
            state.pointerDown = false;
            state.didDrag = false;
            img.style.transform = '';
            stage.classList.remove('is-zoomed', 'is-dragging');
        }

        root.veloriaPiResetPan = resetPanZoom;

        function onMouseMove(ev) {
            if (!state.pointerDown || !state.zoomed) {
                return;
            }
            var dx = ev.clientX - state.startX;
            var dy = ev.clientY - state.startY;
            if (Math.abs(dx) + Math.abs(dy) > 5) {
                state.didDrag = true;
            }
            state.panX = state.startPanX + dx;
            state.panY = state.startPanY + dy;
            applyTransform();
        }

        function onMouseUp() {
            if (!state.pointerDown) {
                return;
            }
            state.pointerDown = false;
            stage.classList.remove('is-dragging');
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }

        stage.addEventListener('mousedown', function (e) {
            if (!state.zoomed) {
                return;
            }
            if (e.button !== 0) {
                return;
            }
            e.preventDefault();
            state.pointerDown = true;
            state.didDrag = false;
            state.startX = e.clientX;
            state.startY = e.clientY;
            state.startPanX = state.panX;
            state.startPanY = state.panY;
            stage.classList.add('is-dragging');
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        });

        var touchId = null;
        stage.addEventListener(
            'touchstart',
            function (e) {
                if (!state.zoomed || e.touches.length !== 1) {
                    return;
                }
                var t = e.touches[0];
                touchId = t.identifier;
                state.pointerDown = true;
                state.didDrag = false;
                state.startX = t.clientX;
                state.startY = t.clientY;
                state.startPanX = state.panX;
                state.startPanY = state.panY;
            },
            { passive: true }
        );

        stage.addEventListener(
            'touchmove',
            function (e) {
                if (!state.zoomed || !state.pointerDown) {
                    return;
                }
                var t = null;
                for (var i = 0; i < e.touches.length; i += 1) {
                    if (e.touches[i].identifier === touchId) {
                        t = e.touches[i];
                        break;
                    }
                }
                if (!t) {
                    return;
                }
                e.preventDefault();
                var dx = t.clientX - state.startX;
                var dy = t.clientY - state.startY;
                if (Math.abs(dx) + Math.abs(dy) > 5) {
                    state.didDrag = true;
                }
                state.panX = state.startPanX + dx;
                state.panY = state.startPanY + dy;
                applyTransform();
            },
            { passive: false }
        );

        function endTouchPan() {
            touchId = null;
            state.pointerDown = false;
        }

        stage.addEventListener('touchend', endTouchPan);
        stage.addEventListener('touchcancel', endTouchPan);

        stage.addEventListener('click', function () {
            if (state.didDrag) {
                state.didDrag = false;
                return;
            }
            state.zoomed = !state.zoomed;
            if (state.zoomed) {
                state.panX = 0;
                state.panY = 0;
            }
            applyTransform();
        });

        stage.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') {
                return;
            }
            e.preventDefault();
            state.zoomed = !state.zoomed;
            if (state.zoomed) {
                state.panX = 0;
                state.panY = 0;
            }
            applyTransform();
        });
    }

    function bindRoot(root) {
        if (!root || root.getAttribute('data-veloria-pi-bound') === '1') {
            return;
        }
        root.setAttribute('data-veloria-pi-bound', '1');

        var images = parseImages(root);
        var zoom = root.getAttribute('data-zoom') || 'lightbox';
        if (zoom === 'hover') {
            zoom = 'pan';
        }
        root.setAttribute('data-active-index', '0');

        var mainClickable = root.querySelector('.veloria-pi__main--clickable');
        if (mainClickable && zoom === 'lightbox') {
            mainClickable.addEventListener('click', function () {
                var idx = parseInt(root.getAttribute('data-active-index'), 10);
                if (isNaN(idx)) {
                    idx = 0;
                }
                var lbItems = images.map(function (row) {
                    return { full: row.full, alt: row.alt };
                });
                openLightbox(lbItems, idx);
            });
        }

        if (zoom === 'pan') {
            bindPanZoom(root);
        }

        root.addEventListener('click', function (event) {
            var thumb = event.target.closest('.veloria-pi__thumb');
            if (!thumb || !root.contains(thumb)) {
                return;
            }
            event.preventDefault();
            var idx = parseInt(thumb.getAttribute('data-index'), 10);
            if (isNaN(idx) || !images[idx]) {
                return;
            }
            applyImageIndex(root, images, idx);
        });
    }

    function scan(scope) {
        var roots = (scope || document).querySelectorAll('.veloria-product-image');
        for (var i = 0; i < roots.length; i += 1) {
            bindRoot(roots[i]);
        }
    }

    function bindUnboundProductImages() {
        var nodes = document.querySelectorAll('.veloria-product-image');
        for (var i = 0; i < nodes.length; i += 1) {
            if (nodes[i].getAttribute('data-veloria-pi-bound') !== '1') {
                bindRoot(nodes[i]);
            }
        }
    }

    var elementorHooksDone = false;

    /**
     * Registers Elementor element_ready hooks (default + legacy skin) and binds the widget scope.
     * Matches the pattern used in woo-dashboard.js so the editor preview iframe always attaches.
     */
    function registerElementReadyHooks() {
        if (elementorHooksDone) {
            return;
        }
        if (typeof window.elementorFrontend === 'undefined' || !window.elementorFrontend.hooks) {
            return;
        }
        elementorHooksDone = true;

        function onWidgetReady($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (!el || !el.querySelector) {
                return;
            }
            scan(el);
        }

        window.elementorFrontend.hooks.addAction('frontend/element_ready/veloria-product-image.default', onWidgetReady);
        window.elementorFrontend.hooks.addAction('frontend/element_ready/veloria-product-image', onWidgetReady);
    }

    var mutationDebounceTimer;

    function onDocumentMutations() {
        clearTimeout(mutationDebounceTimer);
        mutationDebounceTimer = setTimeout(bindUnboundProductImages, 80);
    }

    if (typeof window.MutationObserver !== 'undefined' && document.documentElement) {
        try {
            var piObserver = new MutationObserver(onDocumentMutations);
            piObserver.observe(document.documentElement, { childList: true, subtree: true });
        } catch (e) {
            /* ignore */
        }
    }

    function bootstrap() {
        registerElementReadyHooks();
        bindUnboundProductImages();
    }

    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('elementor/frontend/init', registerElementReadyHooks);
        jQuery(window).on('elementor/frontend/init', registerElementReadyHooks);
    }

    registerElementReadyHooks();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootstrap);
    } else {
        bootstrap();
    }

    setTimeout(bindUnboundProductImages, 200);
    setTimeout(bindUnboundProductImages, 800);
})();
