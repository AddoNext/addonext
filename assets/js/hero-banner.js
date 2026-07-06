'use strict';

function addonextHeroBannerRunTyping(banner) {
  if (!banner || !banner.querySelector) {
    return;
  }
  var typing = banner.querySelector('.addonext-hero-banner__title-typing');
  var wrap = banner.querySelector('.addonext-hero-banner__title-typing-wrap');
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
  var ms = parseInt(banner.getAttribute('data-typing-ms'), 10);
  if (isNaN(ms) || ms < 20) {
    ms = 55;
  }
  if (ms > 200) {
    ms = 200;
  }
  var loop = banner.getAttribute('data-typing-loop') === '1';
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

function addonextHeroBannerInitScope($scope) {
  var root = $scope && $scope[0] ? $scope[0] : null;
  var banner = root ? root.querySelector('.addonext-hero-banner') : null;
  if (banner) {
    addonextHeroBannerRunTyping(banner);
  }
}

jQuery(window).on('elementor/frontend/init', function () {
  if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
    return;
  }
  elementorFrontend.hooks.addAction('frontend/element_ready/addonext-hero-banner.default', addonextHeroBannerInitScope);
  elementorFrontend.hooks.addAction('frontend/element_ready/addonext-hero-banner', addonextHeroBannerInitScope);
});
