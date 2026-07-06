(function () {
  'use strict';

  var escBound = false;

  function bindEscapeClose() {
    if (escBound) return;
    escBound = true;
    document.addEventListener('keydown', function (evt) {
      if (!evt || evt.key !== 'Escape') return;
      var open = document.querySelector('.addonext-woo-dashboard__modal:not([hidden])');
      if (!open) return;
      open.setAttribute('hidden', '');
      document.body.classList.remove('addonext-wd-modal-open');
    });
  }

  /**
   * @param {HTMLElement} shell
   */
  function initShell(shell) {
    if (!shell || shell.getAttribute('data-wd-init') === 'yes') return;
    shell.setAttribute('data-wd-init', 'yes');

    function switchPanel(panel) {
      var key = String(panel || '');
      if (!key) return;

      var panels = shell.querySelectorAll('.addonext-woo-dashboard__panel');
      var navButtons = shell.querySelectorAll('.addonext-woo-dashboard__nav-item[data-wd-panel]');

      var hasTarget = false;
      panels.forEach(function (p) {
        if (p.getAttribute('data-wd-panel-id') === key) {
          hasTarget = true;
        }
      });
      if (!hasTarget) {
        key = 'dashboard';
      }

      panels.forEach(function (p) {
        var match = p.getAttribute('data-wd-panel-id') === key;
        if (match) {
          p.removeAttribute('hidden');
        } else {
          p.setAttribute('hidden', '');
        }
      });

      navButtons.forEach(function (btn) {
        var isMatch = btn.getAttribute('data-wd-panel') === key;
        if (isMatch) {
          btn.classList.add('is-active');
          btn.setAttribute('aria-current', 'page');
        } else {
          btn.classList.remove('is-active');
          btn.removeAttribute('aria-current');
        }
      });
    }

    function openModal() {
      var modal = shell.querySelector('.addonext-woo-dashboard__modal');
      if (!modal) return;
      modal.removeAttribute('hidden');
      document.body.classList.add('addonext-wd-modal-open');
    }

    function closeModal() {
      var modal = shell.querySelector('.addonext-woo-dashboard__modal');
      if (!modal) return;
      modal.setAttribute('hidden', '');
      document.body.classList.remove('addonext-wd-modal-open');
    }

    shell.addEventListener(
      'click',
      function (evt) {
        var t = evt.target;
        if (!t || !t.closest) return;

        var panelTrigger = t.closest('[data-wd-panel]');
        if (panelTrigger && shell.contains(panelTrigger)) {
          evt.preventDefault();
          evt.stopPropagation();
          switchPanel(panelTrigger.getAttribute('data-wd-panel'));
          return;
        }

        var logoutBtn = t.closest('[data-wd-action="logout"]');
        if (logoutBtn && shell.contains(logoutBtn)) {
          evt.preventDefault();
          evt.stopPropagation();
          openModal();
          return;
        }

        var dismiss = t.closest('[data-wd-action="logout-dismiss"]');
        if (dismiss && shell.contains(dismiss)) {
          evt.preventDefault();
          evt.stopPropagation();
          closeModal();
        }
      },
      true
    );

    bindEscapeClose();
    initAccountForm(shell);
    initAddressEditors(shell);
  }

  /**
   * @param {HTMLElement} shell
   */
  function initAddressEditors(shell) {
    var cfg = window.addonextWooDashboard || {};
    var ajaxUrl = cfg.ajaxUrl || shell.getAttribute('data-ajax-url') || '';
    var nonceAddress = cfg.nonceAddress || shell.getAttribute('data-address-nonce') || '';
    var i18n = cfg.i18n || {};

    shell.querySelectorAll('.addonext-wd-addresses__card').forEach(function (card) {
      if (card.getAttribute('data-wd-address-bound') === 'yes') return;
      card.setAttribute('data-wd-address-bound', 'yes');

      var view = card.querySelector('.addonext-wd-addresses__view');
      var editor = card.querySelector('.addonext-wd-addresses__editor');
      var msgEl = card.querySelector('.addonext-wd-addresses__form-message');
      var title = card.getAttribute('data-address-title') || '';

      function openEditor() {
        card.classList.add('is-editing');
        if (view) view.setAttribute('hidden', '');
        if (editor) editor.removeAttribute('hidden');
        if (msgEl) {
          msgEl.hidden = true;
          msgEl.textContent = '';
          msgEl.classList.remove('is-success', 'is-error');
        }
      }

      function closeEditor() {
        card.classList.remove('is-editing');
        if (view) view.removeAttribute('hidden');
        if (editor) editor.setAttribute('hidden', '');
      }

      var toggle = card.querySelector('.addonext-wd-addresses__toggle-edit');
      if (toggle) {
        toggle.addEventListener('click', function () {
          openEditor();
        });
      }

      var cancel = card.querySelector('.addonext-wd-addresses__cancel-edit');
      if (cancel) {
        cancel.addEventListener('click', function () {
          closeEditor();
        });
      }

      if (!ajaxUrl) return;

      card.addEventListener('submit', function (evt) {
        var activeForm = evt.target;
        if (
          !activeForm ||
          !activeForm.classList ||
          !activeForm.classList.contains('addonext-wd-addresses__wc-form')
        ) {
          return;
        }
        evt.preventDefault();

        var submitBtn = activeForm.querySelector('button[type="submit"]');

        function setLoading(on) {
          if (!submitBtn) return;
          submitBtn.disabled = !!on;
          submitBtn.classList.toggle('is-loading', !!on);
        }

        function setMsg(text, kind) {
          if (!msgEl) return;
          msgEl.hidden = !text;
          msgEl.textContent = text || '';
          msgEl.classList.remove('is-success', 'is-error');
          if (kind) {
            msgEl.classList.add(kind === 'success' ? 'is-success' : 'is-error');
          }
        }

        setMsg('', '');
        setLoading(true);

        var fd = new FormData(activeForm);
        fd.delete('action');
        fd.append('action', 'addonext_woo_dashboard_save_address');
        fd.append('address_nonce', nonceAddress);
        fd.append('address_type', card.getAttribute('data-address-key') || '');

        fetch(ajaxUrl, {
          method: 'POST',
          body: fd,
          credentials: 'same-origin',
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data && data.success && data.data) {
              var viewBody = card.querySelector('.addonext-wd-addresses__view-body');
              var hasAddr = !!data.data.hasAddress;
              if (viewBody) {
                if (hasAddr && data.data.formatted) {
                  viewBody.innerHTML =
                    '<div class="addonext-wd-addresses__text">' + data.data.formatted + '</div>';
                } else {
                  viewBody.innerHTML =
                    '<p class="addonext-wd-addresses__empty">' +
                    (i18n.addressEmpty || '') +
                    '</p>';
                }
              }
              var labelSpan = card.querySelector('.addonext-wd-addresses__btn-label');
              if (labelSpan && title) {
                var editT = (i18n.editAddress || 'Edit %s').replace('%s', title);
                var addT = (i18n.addAddress || 'Add %s').replace('%s', title);
                labelSpan.textContent = hasAddr ? editT : addT;
              }

              function closeAndFlash() {
                closeEditor();
                if (view && data.data.message) {
                  var flash = document.createElement('p');
                  flash.className = 'addonext-wd-addresses__flash';
                  flash.textContent = data.data.message;
                  view.insertBefore(flash, view.firstChild);
                  window.setTimeout(function () {
                    if (flash.parentNode) {
                      flash.parentNode.removeChild(flash);
                    }
                  }, 5000);
                }
              }

              var refreshFd = new FormData();
              refreshFd.append('action', 'addonext_woo_dashboard_get_address_form');
              refreshFd.append('address_nonce', nonceAddress);
              refreshFd.append('address_type', card.getAttribute('data-address-key') || '');

              return fetch(ajaxUrl, {
                method: 'POST',
                body: refreshFd,
                credentials: 'same-origin',
              })
                .then(function (r) {
                  return r.json();
                })
                .then(function (resp) {
                  if (resp && resp.success && resp.data && resp.data.html && editor) {
                    var wrap = document.createElement('div');
                    wrap.innerHTML = resp.data.html.trim();
                    var newForm =
                      wrap.querySelector('form.addonext-wd-addresses__wc-form') ||
                      wrap.querySelector('form');
                    var oldForm = editor.querySelector('form.addonext-wd-addresses__wc-form');
                    if (newForm && oldForm && oldForm.parentNode) {
                      oldForm.parentNode.replaceChild(newForm, oldForm);
                    }
                    if (typeof jQuery !== 'undefined') {
                      jQuery(document.body).trigger('wc_address_i18n_ready');
                    }
                  }
                })
                .catch(function () {})
                .then(function () {
                  closeAndFlash();
                });
            }
            var payload = data && data.data ? data.data : null;
            var msgs = [];
            if (payload && Array.isArray(payload.messages)) {
              msgs = payload.messages;
            } else if (payload && typeof payload.messages === 'string') {
              msgs = [payload.messages];
            } else if (typeof payload === 'string') {
              msgs = [payload];
            }
            if (!msgs.length) {
              msgs = ['Request failed.'];
            }
            setMsg(msgs.join(' '), 'error');
          })
          .catch(function () {
            setMsg('Request failed.', 'error');
          })
          .then(function () {
            var endBtn = card.querySelector(
              'form.addonext-wd-addresses__wc-form button[type="submit"]'
            );
            if (endBtn) {
              endBtn.disabled = false;
              endBtn.classList.remove('is-loading');
            }
          });
      });
    });
  }

  /**
   * @param {HTMLElement} shell
   */
  function initAccountForm(shell) {
    var form = shell.querySelector('.addonext-wd-account__form');
    if (!form || form.getAttribute('data-wd-account-bound') === 'yes') return;
    form.setAttribute('data-wd-account-bound', 'yes');

    var cfg = window.addonextWooDashboard || {};
    var ajaxUrl = cfg.ajaxUrl || shell.getAttribute('data-ajax-url') || '';
    var nonce = cfg.nonce || shell.getAttribute('data-account-nonce') || '';

    form.addEventListener('submit', function (evt) {
      evt.preventDefault();
      if (!ajaxUrl) return;

      var msgEl = form.querySelector('.addonext-wd-account__message');
      var submitBtn = form.querySelector('.addonext-wd-account__submit');

      function setMessage(text, kind) {
        if (!msgEl) return;
        msgEl.hidden = !text;
        msgEl.textContent = text || '';
        msgEl.classList.remove('is-success', 'is-error');
        if (kind) {
          msgEl.classList.add(kind === 'success' ? 'is-success' : 'is-error');
        }
      }

      function setLoading(on) {
        if (!submitBtn) return;
        if (on) {
          submitBtn.disabled = true;
          submitBtn.classList.add('is-loading');
        } else {
          submitBtn.disabled = false;
          submitBtn.classList.remove('is-loading');
        }
      }

      setMessage('', '');
      setLoading(true);

      var fd = new FormData(form);
      fd.append('action', 'addonext_woo_dashboard_save_account');
      fd.append('nonce', nonce);

      fetch(ajaxUrl, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (data) {
          if (data && data.success && data.data) {
            setMessage(data.data.message || '', 'success');
            form.querySelectorAll('input[type="password"]').forEach(function (input) {
              input.value = '';
            });
            if (data.data.displayName) {
              var nameNode = shell.querySelector('.addonext-woo-dashboard__name');
              if (nameNode) {
                nameNode.textContent = data.data.displayName;
              }
            }
            return;
          }
          var payload = data && data.data ? data.data : null;
          var msgs = [];
          if (payload && Array.isArray(payload.messages)) {
            msgs = payload.messages;
          } else if (payload && typeof payload.messages === 'string') {
            msgs = [payload.messages];
          } else if (typeof payload === 'string') {
            msgs = [payload];
          }
          if (!msgs.length) {
            msgs = ['Request failed.'];
          }
          setMessage(msgs.join(' '), 'error');
        })
        .catch(function () {
          setMessage('Request failed.', 'error');
        })
        .then(function () {
          setLoading(false);
        });
    });
  }

  function boot(scope) {
    var root = scope && scope.querySelector ? scope : document;
    var shells = root.querySelectorAll('.addonext-woo-dashboard__shell');
    shells.forEach(initShell);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      boot(document);
    });
  } else {
    boot(document);
  }

  var elementorWooDashboardHooksDone = false;

  function registerElementorWooDashboard() {
    if (elementorWooDashboardHooksDone) return;
    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks || !elementorFrontend.hooks.addAction) {
      return;
    }
    elementorWooDashboardHooksDone = true;
    function onWidgetReady($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelector) return;
      boot(el);
    }
    elementorFrontend.hooks.addAction('frontend/element_ready/addonext-woo-dashboard.default', onWidgetReady);
    elementorFrontend.hooks.addAction('frontend/element_ready/addonext-woo-dashboard', onWidgetReady);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
    registerElementorWooDashboard();
  } else if (typeof jQuery !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', registerElementorWooDashboard);
  }
})();
