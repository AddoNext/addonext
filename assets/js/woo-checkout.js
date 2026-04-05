(function ($) {
  'use strict';

  var hooksRegistered = false;

  function maybeRefreshCheckout() {
    if (typeof $ === 'undefined' || !$.fn) return;
    $(document.body).trigger('update_checkout');
  }

  function registerHooks() {
    if (hooksRegistered || typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
    hooksRegistered = true;
    elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-woo-checkout.default', function () {
      maybeRefreshCheckout();
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-woo-checkout', function () {
      maybeRefreshCheckout();
    });
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
    registerHooks();
  } else {
    $(window).on('elementor/frontend/init', registerHooks);
  }
})(jQuery);
