(function () {
  'use strict';

  var SELECTOR = '.veloria-product-carousel-shell';

  function initCarousel(shell) {
    if (!shell || typeof Swiper === 'undefined') return;
    var el = shell.querySelector('.veloria-product-carousel.swiper');
    if (!el) return;
    if (el.getAttribute('data-carousel-inited') === 'yes') return;
    if (el.swiper) return;
    if (!el.querySelector('.swiper-wrapper')) return;

    var desktop = parseInt(el.getAttribute('data-slides-desktop') || '4', 10);
    var tablet = parseInt(el.getAttribute('data-slides-tablet') || '2', 10);
    var mobile = parseInt(el.getAttribute('data-slides-mobile') || '1', 10);
    var spaceBetween = parseInt(el.getAttribute('data-space-between') || '16', 10);
    var showNav = el.getAttribute('data-show-nav') === 'yes';
    var showDots = el.getAttribute('data-show-dots') === 'yes';
    var loop = el.getAttribute('data-loop') === 'yes';

    var options = {
      loop: loop,
      slidesPerView: mobile > 0 ? mobile : 1,
      spaceBetween: isNaN(spaceBetween) ? 16 : spaceBetween,
      breakpoints: {
        768: {
          slidesPerView: tablet > 0 ? tablet : 2,
        },
        1025: {
          slidesPerView: desktop > 0 ? desktop : 4,
        },
      },
    };

    var dotsEl = shell.querySelector('.swiper-pagination');
    if (showDots && dotsEl) {
      options.pagination = {
        el: dotsEl,
        clickable: true,
      };
    }

    try {
      var swiper = new Swiper(el, options);
      el.setAttribute('data-carousel-inited', 'yes');

      var nextBtn = shell.querySelector('.veloria-product-carousel__nav-btn--next');
      var prevBtn = shell.querySelector('.veloria-product-carousel__nav-btn--prev');
      if (showNav && nextBtn && prevBtn) {
        if (!nextBtn.getAttribute('data-aa-bound')) {
          nextBtn.setAttribute('data-aa-bound', 'yes');
          nextBtn.addEventListener('click', function (evt) {
            evt.preventDefault();
            swiper.slideNext();
          });
        }
        if (!prevBtn.getAttribute('data-aa-bound')) {
          prevBtn.setAttribute('data-aa-bound', 'yes');
          prevBtn.addEventListener('click', function (evt) {
            evt.preventDefault();
            swiper.slidePrev();
          });
        }
      }
    } catch (e) {
      el.removeAttribute('data-carousel-inited');
    }
  }

  function scan(root) {
    var scope = root || document;
    var shells = scope.querySelectorAll(SELECTOR);
    shells.forEach(function (shell) {
      initCarousel(shell);
    });
  }

  function startObserver() {
    if (!document.body || typeof MutationObserver === 'undefined') return;
    var observer = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        mutation.addedNodes.forEach(function (node) {
          if (node.nodeType !== 1) return;
          if (node.matches && node.matches(SELECTOR)) {
            initCarousel(node);
          }
          if (node.querySelectorAll) {
            var list = node.querySelectorAll(SELECTOR);
            list.forEach(function (shell) { initCarousel(shell); });
          }
        });
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      scan(document);
      startObserver();
    });
  } else {
    scan(document);
    startObserver();
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/veloria-product-carousel.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) return;
      var shells = el.querySelectorAll(SELECTOR);
      shells.forEach(function (shell) {
        var carousel = shell.querySelector('.veloria-product-carousel.swiper');
        if (carousel) {
          carousel.removeAttribute('data-carousel-inited');
        }
        initCarousel(shell);
      });
    });
  }

  function showCartTooltip(buttonEl) {
    if (!buttonEl || !buttonEl.closest) return;
    var actions = buttonEl.closest('.veloria-product-carousel__actions');
    if (!actions) return;

    actions.classList.add('is-cart-tooltip-visible');
    window.clearTimeout(actions.__pcTooltipTimer);
    actions.__pcTooltipTimer = window.setTimeout(function () {
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

    window.clearTimeout(buttonEl.__pcSuccessTimer);
    buttonEl.__pcSuccessTimer = window.setTimeout(function () {
      buttonEl.classList.remove('is-added-success');
      if (icon) {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-shopping-cart');
      }
    }, 1800);
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
    var shell = link && link.closest ? link.closest('.veloria-product-carousel-shell') : null;
    if (shell && shell.getAttribute('data-cart-url')) {
      return shell.getAttribute('data-cart-url');
    }
    return '';
  }

  function bindCartTooltipOpeners(root) {
    var scope = root || document;
    var tooltipLinks = scope.querySelectorAll('.veloria-product-carousel__cart-tooltip');
    tooltipLinks.forEach(function (link) {
      if (link.getAttribute('data-pc-bound') === 'yes') return;
      link.setAttribute('data-pc-bound', 'yes');
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

  function clearCartButtonLoading(btn) {
    if (btn && btn.classList) {
      btn.classList.remove('veloria-product-carousel__cart-btn--pending');
    }
  }

  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('adding_to_cart', function (event, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (btn && btn.classList && btn.classList.contains('veloria-product-carousel__cart-btn')) {
        btn.classList.add('veloria-product-carousel__cart-btn--pending');
      }
    });
    jQuery(document.body).on('ajax_request_not_sent.adding_to_cart', function (event, a, b, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (!btn || !btn.classList) return;
      if (btn.classList.contains('veloria-product-carousel__cart-btn')) {
        clearCartButtonLoading(btn);
      }
    });
    jQuery(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
      var btn = $button && $button[0] ? $button[0] : null;
      if (btn && btn.classList && btn.classList.contains('veloria-product-carousel__cart-btn')) {
        clearCartButtonLoading(btn);
        showCartTooltip(btn);
        showAddSuccess(btn);
      }
    });
  }

  bindCartTooltipOpeners(document);
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      bindCartTooltipOpeners(document);
    });
  }
  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/veloria-product-carousel.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) return;
      bindCartTooltipOpeners(el);
    });
  }
})();
