'use strict';

function addonextHeroSliderRunTyping(scope) {
  if (!scope || !scope.querySelector) {
    return;
  }
  var typing = scope.querySelector('.addonext-hero-slider__title-typing');
  var wrap = scope.querySelector('.addonext-hero-slider__title-typing-wrap');
  if (!typing || !wrap) {
    return;
  }
  var full = typing.getAttribute('data-text') || '';
  if (!full) {
    return;
  }
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    typing.textContent = full;
    wrap.classList.add('is-done');
    return;
  }
  var ms = parseInt(scope.getAttribute('data-typing-ms'), 10);
  if (isNaN(ms) || ms < 20) {
    ms = 55;
  }
  if (ms > 200) {
    ms = 200;
  }
  var loop = scope.getAttribute('data-typing-loop') === '1';
  var pauseAfter = 2200;
  var pauseClear = 500;
  var i = 0;

  function tick() {
    if (i > full.length) {
      if (!loop) {
        wrap.classList.add('is-done');
        return;
      }
      window.setTimeout(function () {
        wrap.classList.remove('is-done');
        i = 0;
        typing.textContent = '';
        window.setTimeout(tick, pauseClear);
      }, pauseAfter);
      return;
    }
    typing.textContent = full.slice(0, i);
    i += 1;
    window.setTimeout(tick, ms);
  }

  wrap.classList.remove('is-done');
  typing.textContent = '';
  tick();
}

function addonextHeroSliderTypingTarget(root) {
  var mode = root.getAttribute('data-mode') || 'both';
  if (mode === 'background') {
    return root;
  }
  var active = root.querySelector('.swiper-slide.swiper-slide-active');
  return active || root;
}

function addonextHeroSliderInitTyping(root) {
  addonextHeroSliderRunTyping(addonextHeroSliderTypingTarget(root));
}

function addonextHeroSliderDestroySwiper(root) {
  var swEl = root.querySelector('.addonext-hero-slider__bg-swiper, .addonext-hero-slider__content-swiper, .addonext-hero-slider__main-swiper');
  if (swEl && swEl.swiper) {
    try {
      swEl.swiper.destroy(false, false);
    } catch (e) {
      /* ignore */
    }
  }
  root.removeAttribute('data-hero-slider-inited');
}

function addonextHeroSliderInitRoot(root) {
  if (!root) {
    return;
  }
  if (typeof Swiper === 'undefined') {
    return;
  }
  addonextHeroSliderDestroySwiper(root);
  var swEl = root.querySelector('.addonext-hero-slider__bg-swiper, .addonext-hero-slider__content-swiper, .addonext-hero-slider__main-swiper');
  if (!swEl || !swEl.querySelector('.swiper-wrapper')) {
    return;
  }
  var slides = swEl.querySelectorAll('.swiper-slide');
  if (!slides.length) {
    return;
  }

  var autoplay = root.getAttribute('data-autoplay') === '1';
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    autoplay = false;
  }
  var loopAttr = root.getAttribute('data-loop') === '1';
  var loop = loopAttr && slides.length > 1;
  var speed = parseInt(root.getAttribute('data-speed'), 10);
  if (isNaN(speed) || speed < 200) {
    speed = 600;
  }
  var nav = root.getAttribute('data-navigation') || 'both';
  var nextEl = root.querySelector('.swiper-button-next');
  var prevEl = root.querySelector('.swiper-button-prev');
  var paginationEl = root.querySelector('.swiper-pagination');

  var options = {
    loop: loop,
    speed: speed,
    slidesPerView: 1,
    spaceBetween: 0,
    watchOverflow: true,
    observer: true,
    observeParents: true,
  };

  if (nav === 'both' || nav === 'arrows') {
    if (nextEl && prevEl) {
      options.navigation = { nextEl: nextEl, prevEl: prevEl };
    }
  }
  if (nav === 'both' || nav === 'dots') {
    if (paginationEl) {
      options.pagination = {
        el: paginationEl,
        clickable: true,
      };
    }
  }

  if (autoplay && slides.length > 0) {
    var delaySec = parseInt(root.getAttribute('data-autoplay-delay'), 10);
    var delayMs = (isNaN(delaySec) || delaySec < 1) ? 5000 : delaySec * 1000;
    options.autoplay = {
      delay: delayMs,
      disableOnInteraction: false,
    };
  }

  try {
    var swiper = new Swiper(swEl, options);
    root.setAttribute('data-hero-slider-inited', 'yes');
    swiper.on('slideChangeTransitionEnd', function () {
      addonextHeroSliderInitTyping(root);
    });
    addonextHeroSliderInitTyping(root);
  } catch (err) {
    root.removeAttribute('data-hero-slider-inited');
  }
}

function addonextHeroSliderScan(root) {
  root = root || document;
  root.querySelectorAll('.addonext-hero-slider').forEach(function (el) {
    if (el.getAttribute('data-hero-slider-inited') !== 'yes') {
      addonextHeroSliderInitRoot(el);
    }
  });
}

function addonextHeroSliderInitScope($scope) {
  var wrap = $scope && $scope[0] ? $scope[0] : null;
  if (!wrap || !wrap.querySelector) {
    return;
  }
  var root = wrap.querySelector('.addonext-hero-slider');
  if (root) {
    addonextHeroSliderDestroySwiper(root);
    addonextHeroSliderInitRoot(root);
  }
}

jQuery(window).on('elementor/frontend/init', function () {
  if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
    return;
  }
  elementorFrontend.hooks.addAction('frontend/element_ready/addonext-hero-slider.default', addonextHeroSliderInitScope);
  elementorFrontend.hooks.addAction('frontend/element_ready/addonext-hero-slider', addonextHeroSliderInitScope);
});

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function () {
    addonextHeroSliderScan(document);
  });
} else {
  addonextHeroSliderScan(document);
}
setTimeout(function () {
  document.querySelectorAll('.addonext-hero-slider').forEach(function (el) {
    if (el.getAttribute('data-hero-slider-inited') !== 'yes') {
      addonextHeroSliderInitRoot(el);
    }
  });
}, 400);
