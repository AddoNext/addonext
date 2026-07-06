(function () {
  'use strict';

  var SHELL = '.addonext-add-to-cart-shell';

  function parseIntSafe(v, fallback) {
    var n = parseInt(v, 10);
    return isNaN(n) ? fallback : n;
  }

  function clampQty(value, min, max, step) {
    var v = value;
    if (v < min) v = min;
    if (max > 0 && v > max) v = max;
    if (step > 1) {
      v = Math.round((v - min) / step) * step + min;
      if (v < min) v = min;
      if (max > 0 && v > max) v = max;
    }
    return v;
  }

  function getQtyInput(shell) {
    return shell ? shell.querySelector('.addonext-add-to-cart__qty-input') : null;
  }

  function getCartButton(shell) {
    return shell ? shell.querySelector('.addonext-add-to-cart__cart-btn') : null;
  }

  function readMaxFromInput(input) {
    if (!input) return 0;
    var raw = input.getAttribute('max');
    if (raw === null || raw === '') return 0;
    return parseIntSafe(raw, 0);
  }

  function syncQuantityToButton(shell) {
    var input = getQtyInput(shell);
    var btn = getCartButton(shell);
    if (!input || !btn) return;
    var min = parseIntSafe(input.getAttribute('min'), 1);
    var max = readMaxFromInput(input);
    var step = parseIntSafe(input.getAttribute('step'), 1);
    var raw = parseIntSafe(input.value, min);
    var qty = clampQty(raw, min, max, step);
    input.value = String(qty);
    btn.setAttribute('data-quantity', String(qty));
  }

  function adjustQuantity(shell, direction) {
    var input = getQtyInput(shell);
    if (!input) return;
    var min = parseIntSafe(input.getAttribute('min'), 1);
    var max = readMaxFromInput(input);
    var step = parseIntSafe(input.getAttribute('step'), 1);
    var current = parseIntSafe(input.value, min);
    var next = current + direction * step;
    var qty = clampQty(next, min, max, step);
    input.value = String(qty);
    syncQuantityToButton(shell);
  }

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
    var shell = link && link.closest ? link.closest(SHELL) : null;
    if (shell && shell.getAttribute('data-cart-url')) {
      return shell.getAttribute('data-cart-url');
    }
    return '';
  }

  function getButtonParts(buttonEl) {
    if (!buttonEl) return null;
    return {
      iconEl: buttonEl.querySelector('.addonext-add-to-cart__btn-icon i'),
      labelEl: buttonEl.querySelector('.addonext-add-to-cart__btn-label')
    };
  }

  function setButtonVisual(buttonEl, mode) {
    if (!buttonEl || !buttonEl.classList) return;
    var parts = getButtonParts(buttonEl);
    if (!parts || !parts.iconEl || !parts.labelEl) return;

    var defaultLabel = buttonEl.getAttribute('data-atc-label-default') || 'Add to Cart';
    var addedLabel = buttonEl.getAttribute('data-atc-label-added') || 'Added to cart';
    var viewCartLabel = buttonEl.getAttribute('data-atc-label-view-cart') || 'View cart';

    buttonEl.classList.remove('is-added-success', 'is-view-cart');
    parts.iconEl.className = '';
    parts.iconEl.classList.add('fas');

    if (mode === 'added') {
      buttonEl.classList.add('is-added-success');
      parts.iconEl.classList.add('fa-check');
      parts.labelEl.textContent = addedLabel;
      return;
    }

    if (mode === 'view_cart') {
      buttonEl.classList.add('is-view-cart');
      parts.iconEl.classList.remove('fas');
      parts.iconEl.classList.add('far', 'fa-eye');
      parts.labelEl.textContent = viewCartLabel;
      return;
    }

    parts.iconEl.classList.add('fa-cart-plus');
    parts.labelEl.textContent = defaultLabel;
  }

  function clearCartButtonLoading(btn) {
    if (btn && btn.classList) {
      btn.classList.remove('addonext-add-to-cart__cart-btn--pending');
      btn.classList.remove('loading');
    }
  }

  function fireAddedToCartEvent(btn, response) {
    if (typeof jQuery === 'undefined') return;
    var $btn = jQuery(btn);
    var fragments = response && response.fragments ? response.fragments : null;
    var cartHash = response && response.cart_hash ? response.cart_hash : '';
    jQuery(document.body).trigger('added_to_cart', [fragments, cartHash, $btn]);
  }

  function fireAddToCartRequest(btn) {
    if (!btn || typeof jQuery === 'undefined') return false;
    if (typeof wc_add_to_cart_params === 'undefined' || !wc_add_to_cart_params.wc_ajax_url) return false;
    if (!btn.classList.contains('ajax_add_to_cart')) return false;

    var data = {};
    var dataset = btn.dataset || {};
    Object.keys(dataset).forEach(function (key) {
      data[key] = dataset[key];
    });
    if (!data.product_id && btn.getAttribute('data-product_id')) {
      data.product_id = btn.getAttribute('data-product_id');
    }
    if (!data.quantity && btn.getAttribute('data-quantity')) {
      data.quantity = btn.getAttribute('data-quantity');
    }
    if (!data.product_id) return false;

    btn.classList.add('loading');
    btn.classList.add('addonext-add-to-cart__cart-btn--pending');

    jQuery.ajax({
      type: 'POST',
      url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
      data: data,
      dataType: 'json'
    }).done(function (response) {
      if (!response) {
        clearCartButtonLoading(btn);
        return;
      }
      if (response.error && response.product_url) {
        window.location.href = response.product_url;
        return;
      }
      fireAddedToCartEvent(btn, response);
    }).fail(function () {
      clearCartButtonLoading(btn);
    });

    return true;
  }

  function bindShell(shell) {
    if (!shell || shell.getAttribute('data-atc-inited') === 'yes') return;
    shell.setAttribute('data-atc-inited', 'yes');

    shell.addEventListener('click', function (evt) {
      var t = evt.target;
      if (!t || !t.closest) return;
      var minus = t.closest('.addonext-add-to-cart__qty-btn--minus');
      var plus = t.closest('.addonext-add-to-cart__qty-btn--plus');
      var cartBtn = t.closest('.addonext-add-to-cart__cart-btn');
      if (minus) {
        evt.preventDefault();
        adjustQuantity(shell, -1);
      } else if (plus) {
        evt.preventDefault();
        adjustQuantity(shell, 1);
      } else if (cartBtn && cartBtn.classList.contains('is-view-cart')) {
        evt.preventDefault();
        evt.stopPropagation();
        if (tryOpenMiniCart()) return;
        var target = resolveFallbackCartUrl(cartBtn);
        if (target) {
          window.location.href = target;
        }
      } else if (cartBtn) {
        if (fireAddToCartRequest(cartBtn)) {
          evt.preventDefault();
          evt.stopPropagation();
        }
      }
    });

    var btn = getCartButton(shell);
    if (btn) {
      setButtonVisual(btn, 'default');
      btn.addEventListener('mouseleave', function () {
        if (btn.classList.contains('is-added-success') || btn.classList.contains('is-view-cart')) {
          window.clearTimeout(btn.__atcModeTimer);
          setButtonVisual(btn, 'default');
        }
      });
    }

    var input = getQtyInput(shell);
    if (input) {
      input.addEventListener('change', function () {
        syncQuantityToButton(shell);
      });
      input.addEventListener('input', function () {
        syncQuantityToButton(shell);
      });
    }
    syncQuantityToButton(shell);
  }

  function scan(root) {
    var scope = root || document;
    var shells = scope.querySelectorAll(SHELL);
    shells.forEach(function (shell) {
      bindShell(shell);
    });
  }

  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('adding_to_cart', function (event, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (btn && btn.classList && btn.classList.contains('addonext-add-to-cart__cart-btn')) {
        btn.classList.add('addonext-add-to-cart__cart-btn--pending');
      }
    });
    jQuery(document.body).on('ajax_request_not_sent.adding_to_cart', function (event, a, b, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList) return;
      if (btn.classList.contains('addonext-add-to-cart__cart-btn')) {
        clearCartButtonLoading(btn);
      }
    });
    jQuery(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList || !btn.classList.contains('addonext-add-to-cart__cart-btn')) return;
      clearCartButtonLoading(btn);
      setButtonVisual(btn, 'added');
      window.clearTimeout(btn.__atcModeTimer);
      btn.__atcModeTimer = window.setTimeout(function () {
        setButtonVisual(btn, 'view_cart');
      }, 1000);
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
    elementorFrontend.hooks.addAction('frontend/element_ready/addonext-add-to-cart.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) return;
      scan(el);
    });
  }
})();
