(function () {
  'use strict';

  var SWIPER_SEL = '.addonext-pc__swiper.swiper';

  function initPcSlider(el) {
    if (!el || typeof Swiper === 'undefined') {
      return;
    }
    if (el.getAttribute('data-pc-swiper-inited') === 'yes') {
      return;
    }
    if (el.swiper) {
      return;
    }
    var wrap = el.querySelector('.swiper-wrapper');
    if (!wrap || !wrap.children.length) {
      return;
    }

    var desktop = parseInt(el.getAttribute('data-slides-desktop') || '4', 10);
    var tablet = parseInt(el.getAttribute('data-slides-tablet') || '2', 10);
    var mobile = parseInt(el.getAttribute('data-slides-mobile') || '1', 10);
    var spaceBetween = parseInt(el.getAttribute('data-space-between') || '20', 10);
    var showNav = el.getAttribute('data-show-nav') === 'yes';
    var showDots = el.getAttribute('data-show-dots') === 'yes';
    var loop = el.getAttribute('data-loop') === 'yes';

    var slideCount = wrap.children.length;
    if (slideCount < 2) {
      loop = false;
    }

    var shell = el.closest('.addonext-pc__slider-shell');
    var nextBtn = shell ? shell.querySelector('.addonext-pc__nav--next') : null;
    var prevBtn = shell ? shell.querySelector('.addonext-pc__nav--prev') : null;
    var dotsEl = el.querySelector('.swiper-pagination');

    var options = {
      loop: loop,
      slidesPerView: mobile > 0 ? mobile : 1,
      spaceBetween: isNaN(spaceBetween) ? 20 : spaceBetween,
      watchOverflow: true,
      breakpoints: {
        768: {
          slidesPerView: tablet > 0 ? tablet : 2,
        },
        1025: {
          slidesPerView: desktop > 0 ? desktop : 4,
        },
      },
    };

    if (showDots && dotsEl) {
      options.pagination = {
        el: dotsEl,
        clickable: true,
      };
    }

    try {
      var swiper = new Swiper(el, options);
      el.setAttribute('data-pc-swiper-inited', 'yes');

      if (showNav && nextBtn && prevBtn) {
        nextBtn.addEventListener('click', function (evt) {
          evt.preventDefault();
          swiper.slideNext();
        });
        prevBtn.addEventListener('click', function (evt) {
          evt.preventDefault();
          swiper.slidePrev();
        });
      }
    } catch (e) {
      el.removeAttribute('data-pc-swiper-inited');
    }
  }

  function scan(root) {
    var scope = root || document;
    var nodes = scope.querySelectorAll(SWIPER_SEL);
    for (var i = 0; i < nodes.length; i += 1) {
      initPcSlider(nodes[i]);
    }
  }

  function runInit() {
    scan(document);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runInit);
  } else {
    runInit();
  }
  setTimeout(runInit, 400);

  if (typeof MutationObserver !== 'undefined' && document.body) {
    var observer = new MutationObserver(function () {
      runInit();
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
    elementorFrontend.hooks.addAction('frontend/element_ready/addonext-product-categories.default', function ($scope) {
      scan($scope && $scope[0] ? $scope[0] : document);
    });
  }
})();
