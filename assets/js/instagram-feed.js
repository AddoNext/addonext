(function () {
    'use strict';

    var CAROUSEL_SEL = '.elementrify-instagram-feed__carousel.swiper';

    function initCarousel(el) {
        if (!el) return;
        if (el.getAttribute('data-carousel-inited') === 'yes') return;
        if (el.swiper) return;
        if (typeof Swiper === 'undefined') return;
        var wrapper = el.querySelector('.swiper-wrapper');
        if (!wrapper || wrapper.children.length === 0) return;

        el.setAttribute('data-carousel-inited', 'yes');

        var slides = parseInt(el.getAttribute('data-slides'), 10) || 3;
        var slidesTablet = parseInt(el.getAttribute('data-slides-tablet'), 10) || slides;
        var slidesMobile = parseInt(el.getAttribute('data-slides-mobile'), 10) || 1;
        var space = parseInt(el.getAttribute('data-space'), 10) || 10;
        var autoplay = el.getAttribute('data-autoplay') === '1';
        var autoplayDelay = parseInt(el.getAttribute('data-autoplay-delay'), 10) || 3000;
        var loop = el.getAttribute('data-loop') === '1';
        var speed = parseInt(el.getAttribute('data-speed'), 10) || 500;
        var nav = el.getAttribute('data-nav') === '1';
        var pagination = el.getAttribute('data-pagination') === '1';

        var nextEl = el.querySelector('.swiper-button-next');
        var prevEl = el.querySelector('.swiper-button-prev');
        var paginationEl = el.querySelector('.swiper-pagination');

        var options = {
            loop: loop,
            speed: speed,
            spaceBetween: space,
            slidesPerView: slides,
            breakpoints: {
                320: { slidesPerView: slidesMobile },
                768: { slidesPerView: slidesTablet },
                1024: { slidesPerView: slides },
            },
        };
        if (nav && nextEl && prevEl) {
            options.navigation = { nextEl: nextEl, prevEl: prevEl };
        }
        if (pagination && paginationEl) {
            options.pagination = { el: paginationEl, clickable: true };
        }
        if (autoplay) {
            options.autoplay = {
                delay: autoplayDelay,
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

    if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
        elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-instagram-feed.default', function ($scope) {
            var el = $scope && $scope[0] ? $scope[0] : $scope;
            if (el && el.querySelector) {
                var carousel = el.querySelector(CAROUSEL_SEL);
                if (carousel && typeof Swiper !== 'undefined') {
                    initCarousel(carousel);
                } else if (carousel) {
                    setTimeout(function () { initCarousel(carousel); }, 100);
                }
            }
        });
    }
})();
