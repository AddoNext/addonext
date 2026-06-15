(function () {
    'use strict';

    var CAROUSEL_SEL = '.veloria-image-carousel.swiper';

    function initCarousel(el) {
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
        var nextEl = el.querySelector('.swiper-button-next');
        var prevEl = el.querySelector('.swiper-button-prev');
        var paginationEl = el.querySelector('.swiper-pagination');

        var options = {
            loop: loop,
            slidesPerView: 1,
            spaceBetween: 0,
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
            var delayMs = (isNaN(delaySec) || delaySec < 1) ? 5000 : delaySec * 1000;
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
        var carousels = root.querySelectorAll(CAROUSEL_SEL);
        carousels.forEach(function (el) {
            initCarousel(el);
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

    function startObserver() {
        if (typeof MutationObserver === 'undefined' || !document.body) return;
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.addedNodes && m.addedNodes.length) {
                    for (var i = 0; i < m.addedNodes.length; i++) {
                        var node = m.addedNodes[i];
                        if (node.nodeType !== 1) continue;
                        if (node.matches && node.matches(CAROUSEL_SEL)) {
                            initCarousel(node);
                        }
                        if (node.querySelectorAll) {
                            var list = node.querySelectorAll(CAROUSEL_SEL);
                            list.forEach(function (carousel) { initCarousel(carousel); });
                        }
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }
    if (document.body) {
        startObserver();
    } else {
        document.addEventListener('DOMContentLoaded', startObserver);
    }

    function bindElementor() {
        if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) return;
        elementorFrontend.hooks.addAction('frontend/element_ready/veloria-image-carousel.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var carousel = el.querySelector(CAROUSEL_SEL);
                if (carousel) {
                    if (typeof Swiper !== 'undefined') {
                        initCarousel(carousel);
                    } else {
                        setTimeout(function () { initCarousel(carousel); }, 100);
                    }
                }
            }
        });
    }

    if (typeof elementorFrontend !== 'undefined') {
        bindElementor();
    } else {
        document.addEventListener('DOMContentLoaded', function () { setTimeout(bindElementor, 100); });
    }
})();
