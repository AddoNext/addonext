(function () {
    'use strict';

    var SLIDER_SEL = '.elementrify-logo-showcase--slider.swiper';

    function initSlider(el) {
        if (!el) return;
        if (el.getAttribute('data-carousel-inited') === 'yes') return;
        if (el.swiper) return;
        if (typeof Swiper === 'undefined') return;
        var wrapper = el.querySelector('.swiper-wrapper');
        if (!wrapper || wrapper.children.length === 0) return;

        el.setAttribute('data-carousel-inited', 'yes');

        var autoplay = el.getAttribute('data-autoplay') === '1';
        var loop = el.getAttribute('data-loop') === '1';
        var paginationType = el.getAttribute('data-pagination-type') || 'bullets';
        var slidesPerView = parseInt(el.getAttribute('data-slides-per-view'), 10);
        if (isNaN(slidesPerView) || slidesPerView < 1) slidesPerView = 4;
        if (slidesPerView > 6) slidesPerView = 6;
        var spaceBetween = parseInt(el.getAttribute('data-space-between'), 10);
        if (isNaN(spaceBetween) || spaceBetween < 0) spaceBetween = 24;
        var nextEl = el.querySelector('.swiper-button-next');
        var prevEl = el.querySelector('.swiper-button-prev');
        var paginationEl = el.querySelector('.swiper-pagination');

        var options = {
            loop: loop,
            slidesPerView: slidesPerView,
            spaceBetween: spaceBetween,
            autoHeight: true,
        };
        if (nextEl && prevEl) {
            options.navigation = { nextEl: nextEl, prevEl: prevEl };
        }
        if (paginationEl) {
            var paginationSwiperType = (paginationType === 'square' || paginationType === 'progressbar') ? 'bullets' : paginationType;
            options.pagination = {
                el: paginationEl,
                clickable: true,
                type: paginationSwiperType,
            };
        }
        if (autoplay) {
            var delaySec = parseInt(el.getAttribute('data-autoplay-delay'), 10);
            var delayMs = (isNaN(delaySec) || delaySec < 1) ? 3000 : delaySec * 1000;
            options.autoplay = {
                delay: delayMs,
                disableOnInteraction: false,
            };
        }

        try {
            new Swiper(el, options);
        } catch (err) {
            el.removeAttribute('data-carousel-inited');
        }
    }

    function scan(root) {
        root = root || document;
        var sliders = root.querySelectorAll(SLIDER_SEL);
        sliders.forEach(function (el) {
            initSlider(el);
        });
    }

    function runInit() {
        scan(document);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }
    setTimeout(runInit, 500);

    if (typeof MutationObserver !== 'undefined' && document.body) {
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.addedNodes && m.addedNodes.length) {
                    for (var i = 0; i < m.addedNodes.length; i++) {
                        var node = m.addedNodes[i];
                        if (node.nodeType !== 1) continue;
                        if (node.matches && node.matches(SLIDER_SEL)) {
                            initSlider(node);
                        }
                        if (node.querySelectorAll) {
                            var list = node.querySelectorAll(SLIDER_SEL);
                            list.forEach(function (slider) { initSlider(slider); });
                        }
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-logo-showcase.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var slider = el.querySelector(SLIDER_SEL);
                if (slider && typeof Swiper !== 'undefined') {
                    initSlider(slider);
                } else if (slider) {
                    setTimeout(function () { initSlider(slider); }, 100);
                }
            }
        });
    }
})();
