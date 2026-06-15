(function () {
    'use strict';

    var FORM_SEL = '.veloria-subscribe-form__form';

    function getMessageEl(form) {
        return form.closest('.veloria-subscribe-form').querySelector('.veloria-subscribe-form__message');
    }

    function showMessage(form, text, isError) {
        var el = getMessageEl(form);
        if (!el) return;
        el.textContent = text;
        el.classList.remove('veloria-subscribe-form__message--success', 'veloria-subscribe-form__message--error');
        el.classList.add(isError ? 'veloria-subscribe-form__message--error' : 'veloria-subscribe-form__message--success');
        el.setAttribute('role', 'alert');
    }

    function setSubmitting(form, submitting) {
        var btn = form.querySelector('.veloria-subscribe-form__submit');
        if (btn) {
            btn.disabled = submitting;
            if (submitting) {
                btn.classList.add('veloria-subscribe-form__submit--loading');
            } else {
                btn.classList.remove('veloria-subscribe-form__submit--loading');
            }
        }
    }

    function initForm(form) {
        if (form.getAttribute('data-subscribe-form-inited') === 'yes') return;
        form.setAttribute('data-subscribe-form-inited', 'yes');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var messageEl = getMessageEl(form);
            if (messageEl) messageEl.textContent = '';
            setSubmitting(form, true);

            var ajaxUrl = form.getAttribute('data-ajax-url');
            var listId = form.getAttribute('data-list-id');
            var formData = new FormData(form);
            formData.set('action', 'veloria_subscribe_form');
            formData.set('list_id', listId || '');

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
                .then(function (res) {
                    return res.text().then(function (text) {
                        try {
                            return { ok: res.ok, json: text ? JSON.parse(text) : {} };
                        } catch (e) {
                            throw new Error(res.ok ? 'Invalid response' : (res.statusText || 'Request failed'));
                        }
                    });
                })
                .then(function (result) {
                    setSubmitting(form, false);
                    var json = result.json;
                    var success = json && json.success;
                    var msg = (json && json.data && json.data.message) || (json && json.message) || form.getAttribute(success ? 'data-success-msg' : 'data-error-msg');
                    showMessage(form, msg || (success ? 'Subscribed.' : 'Error.'), !success);
                    if (success) form.reset();
                })
                .catch(function () {
                    setSubmitting(form, false);
                    showMessage(form, form.getAttribute('data-error-msg') || 'Something went wrong. Please try again.', true);
                });
        });
    }

    function run() {
        document.querySelectorAll(FORM_SEL).forEach(initForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/veloria-subscribe-form.default', function ($scope) {
                $scope.find(FORM_SEL).each(function () { initForm(this); });
            });
        });
    }
})();
