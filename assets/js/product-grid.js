(function () {
  'use strict';

  function isLikelyVisible(el) {
    if (!el || typeof el.getBoundingClientRect !== 'function') return false;
    var rect = el.getBoundingClientRect();
    if (rect.width < 2 || rect.height < 2) return false;
    if (typeof window.getComputedStyle === 'function') {
      var st = window.getComputedStyle(el);
      if (st.display === 'none' || st.visibility === 'hidden' || Number(st.opacity) === 0) return false;
    }
    return true;
  }

  function tryOpenMiniCart() {
    var selectors = [
      '.wc-block-mini-cart__button',
      '.wpr-mini-cart-toggle-btn',
      '.elementor-widget-premium-mini-cart .pa-woo-mc__inner-container',
      '.elementor-widget-woocommerce-menu-cart button.elementor-menu-cart__toggle',
      '.elementor-menu-cart__toggle_button',
      '.elementor-widget-woocommerce-menu-cart .elementor-menu-cart__toggle_button',
      'header .elementor-menu-cart__toggle_button',
      '.site-header-cart .cart-toggle',
      '.site-header .cart-contents',
      '.header-cart .toggle-cart',
      '.mini-cart .cart-trigger',
      '.cart-contents'
    ];

    for (var i = 0; i < selectors.length; i += 1) {
      var nodes = document.querySelectorAll(selectors[i]);
      for (var j = 0; j < nodes.length; j += 1) {
        var trigger = nodes[j];
        if (!isLikelyVisible(trigger)) continue;
        if (typeof trigger.click === 'function') {
          trigger.click();
        } else {
          trigger.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
        }
        return true;
      }
    }
    return false;
  }

  function resolveFallbackCartUrl(link) {
    if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.cart_url) {
      return wc_add_to_cart_params.cart_url;
    }
    var shell = link && link.closest ? link.closest('.elementrify-product-grid-shell') : null;
    if (shell && shell.getAttribute('data-cart-url')) {
      return shell.getAttribute('data-cart-url');
    }
    return '';
  }

  function showCartTooltip(buttonEl) {
    if (!buttonEl || !buttonEl.closest) return;
    var actions = buttonEl.closest('.elementrify-product-grid__actions');
    if (!actions) return;
    actions.classList.add('is-cart-tooltip-visible');
    window.clearTimeout(actions.__pgTooltipTimer);
    actions.__pgTooltipTimer = window.setTimeout(function () {
      actions.classList.remove('is-cart-tooltip-visible');
    }, 1800);
  }

  function showAddSuccess(buttonEl) {
    if (!buttonEl || !buttonEl.classList) return;
    var icon = buttonEl.querySelector('i');

    buttonEl.classList.add('is-added-success');
    if (icon) {
      icon.classList.remove('fa-shopping-cart');
      icon.classList.add('fa-check');
    }

    window.clearTimeout(buttonEl.__pgSuccessTimer);
    buttonEl.__pgSuccessTimer = window.setTimeout(function () {
      buttonEl.classList.remove('is-added-success');
      if (icon) {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-shopping-cart');
      }
    }, 1800);
  }

  function clearCartButtonLoading(btn) {
    if (!btn || !btn.classList) return;
    btn.classList.remove('elementrify-product-grid__cart-btn--pending');
  }

  function bindCartTooltipOpeners(root) {
    var scope = root || document;
    var tooltipLinks = scope.querySelectorAll('.elementrify-product-grid__cart-tooltip');
    tooltipLinks.forEach(function (link) {
      if (link.getAttribute('data-pg-bound') === 'yes') return;
      link.setAttribute('data-pg-bound', 'yes');
      link.addEventListener('click', function (evt) {
        evt.preventDefault();
        if (tryOpenMiniCart()) return;
        var target = resolveFallbackCartUrl(link);
        if (target) {
          window.location.href = target;
        }
      });
    });
  }

  function scan(root) {
    bindCartTooltipOpeners(root || document);
  }

  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('adding_to_cart', function (event, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (btn && btn.classList && btn.classList.contains('elementrify-product-grid__cart-btn')) {
        btn.classList.add('elementrify-product-grid__cart-btn--pending');
      }
    });
    jQuery(document.body).on('ajax_request_not_sent.adding_to_cart', function (event, a, b, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList) return;
      if (btn.classList.contains('elementrify-product-grid__cart-btn')) {
        clearCartButtonLoading(btn);
      }
    });
    jQuery(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (btn && btn.classList && btn.classList.contains('elementrify-product-grid__cart-btn')) {
        clearCartButtonLoading(btn);
        showCartTooltip(btn);
        showAddSuccess(btn);
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      scan(document);
    });
  } else {
    scan(document);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-product-grid.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) return;
      scan(el);
    });
  }
})();
