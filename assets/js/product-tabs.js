(function () {
  'use strict';

  var TABS_ROOT = '[data-addonext-product-tabs]';

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

    var si;
    for (si = 0; si < selectors.length; si += 1) {
      var nodes = document.querySelectorAll(selectors[si]);
      var sj;
      for (sj = 0; sj < nodes.length; sj += 1) {
        var trigger = nodes[sj];
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

  function resolveTabsCartUrl(link) {
    if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.cart_url) {
      return wc_add_to_cart_params.cart_url;
    }
    var shell = link && link.closest ? link.closest('.addonext-product-tabs__grid-shell') : null;
    if (shell && shell.getAttribute('data-cart-url')) {
      return shell.getAttribute('data-cart-url');
    }
    return '';
  }

  function showTabsCartTooltip(buttonEl) {
    if (!buttonEl || !buttonEl.closest) return;
    var actions = buttonEl.closest('.addonext-product-grid__actions');
    if (!actions) return;
    actions.classList.add('is-cart-tooltip-visible');
    window.clearTimeout(actions.__eptTooltipTimer);
    actions.__eptTooltipTimer = window.setTimeout(function () {
      actions.classList.remove('is-cart-tooltip-visible');
    }, 1800);
  }

  function showTabsAddSuccess(buttonEl) {
    if (!buttonEl || !buttonEl.classList) return;
    var icon = buttonEl.querySelector('i');
    buttonEl.classList.add('is-added-success');
    if (icon) {
      icon.classList.remove('fa-shopping-cart');
      icon.classList.add('fa-check');
    }
    window.clearTimeout(buttonEl.__eptSuccessTimer);
    buttonEl.__eptSuccessTimer = window.setTimeout(function () {
      buttonEl.classList.remove('is-added-success');
      if (icon) {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-shopping-cart');
      }
    }, 1800);
  }

  function clearTabsCartLoading(btn) {
    if (!btn || !btn.classList) return;
    btn.classList.remove('addonext-product-grid__cart-btn--pending');
  }

  function bindTabsCartTooltips(root) {
    var scope = root || document;
    var tooltipLinks = scope.querySelectorAll(TABS_ROOT + ' .addonext-product-grid__cart-tooltip');
    tooltipLinks.forEach(function (link) {
      if (link.getAttribute('data-ept-pg-bound') === 'yes') return;
      link.setAttribute('data-ept-pg-bound', 'yes');
      link.addEventListener('click', function (evt) {
        evt.preventDefault();
        if (tryOpenMiniCart()) return;
        var target = resolveTabsCartUrl(link);
        if (target) {
          window.location.href = target;
        }
      });
    });
  }

  function dispatchResize() {
    if (typeof window.dispatchEvent === 'function') {
      window.dispatchEvent(new Event('resize'));
    }
  }

  function activateTab(root, tab) {
    var tabs = root.querySelectorAll('.addonext-product-tabs__tab[role="tab"]');
    var panels = root.querySelectorAll('.addonext-product-tabs__panel[role="tabpanel"]');
    var controlsId = tab.getAttribute('aria-controls');
    var i;
    for (i = 0; i < tabs.length; i += 1) {
      tabs[i].classList.remove('is-active');
      tabs[i].setAttribute('aria-selected', 'false');
      tabs[i].setAttribute('tabindex', '-1');
    }
    for (i = 0; i < panels.length; i += 1) {
      panels[i].classList.remove('is-active');
      panels[i].setAttribute('hidden', '');
    }
    tab.classList.add('is-active');
    tab.setAttribute('aria-selected', 'true');
    tab.setAttribute('tabindex', '0');
    if (controlsId) {
      var panel = root.querySelector('#' + controlsId);
      if (panel) {
        panel.classList.add('is-active');
        panel.removeAttribute('hidden');
      }
    }
    dispatchResize();
  }

  function onDocumentClick(ev) {
    var tab = ev.target.closest('.addonext-product-tabs__tab[role="tab"]');
    if (!tab) return;
    var root = tab.closest(TABS_ROOT);
    if (!root) return;
    if (tab.classList.contains('is-active')) return;
    ev.preventDefault();
    ev.stopPropagation();
    activateTab(root, tab);
  }

  document.addEventListener('click', onDocumentClick, true);

  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('adding_to_cart', function (event, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList || !btn.classList.contains('addonext-product-grid__cart-btn')) return;
      if (!btn.closest(TABS_ROOT)) return;
      btn.classList.add('addonext-product-grid__cart-btn--pending');
    });
    jQuery(document.body).on('ajax_request_not_sent.adding_to_cart', function (event, a, b, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList || !btn.classList.contains('addonext-product-grid__cart-btn')) return;
      if (!btn.closest(TABS_ROOT)) return;
      clearTabsCartLoading(btn);
    });
    jQuery(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList || !btn.classList.contains('addonext-product-grid__cart-btn')) return;
      if (!btn.closest(TABS_ROOT)) return;
      clearTabsCartLoading(btn);
      showTabsCartTooltip(btn);
      showTabsAddSuccess(btn);
    });
  }

  function initTabsWidgetScope(el) {
    if (el && el.querySelectorAll) {
      bindTabsCartTooltips(el);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      bindTabsCartTooltips(document);
    });
  } else {
    bindTabsCartTooltips(document);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/addonext-product-tabs.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      initTabsWidgetScope(el);
    });
  }
})();
