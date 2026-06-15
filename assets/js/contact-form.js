(function () {
    'use strict';

    var FORM_SEL = '.veloria-contact-form';

    function getMessageEl(form) {
        return form.querySelector('.veloria-contact-form__message');
    }

    function showMessage(form, text, isError) {
        var el = getMessageEl(form);
        if (!el) return;
        el.textContent = text;
        el.classList.remove('veloria-contact-form__message--success', 'veloria-contact-form__message--error');
        el.classList.add(isError ? 'veloria-contact-form__message--error' : 'veloria-contact-form__message--success');
        el.setAttribute('role', 'alert');
    }

    function setSubmitting(form, submitting) {
        var btn = form.querySelector('.veloria-contact-form__submit');
        if (btn) {
            btn.disabled = submitting;
            if (submitting) {
                btn.classList.add('veloria-contact-form__submit--loading');
            } else {
                btn.classList.remove('veloria-contact-form__submit--loading');
            }
        }
    }

    function loadRecaptchaScript(siteKey, callback) {
        if (window.grecaptcha && window.grecaptcha.render) {
            callback();
            return;
        }
        var script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js?onload=veloriaRecaptchaOnload&render=explicit';
        script.async = true;
        window.veloriaRecaptchaQueue = window.veloriaRecaptchaQueue || [];
        window.veloriaRecaptchaOnload = function () {
            (window.veloriaRecaptchaQueue || []).forEach(function (fn) { fn(); });
            window.veloriaRecaptchaQueue = [];
        };
        window.veloriaRecaptchaQueue.push(callback);
        document.head.appendChild(script);
    }

    function renderRecaptcha(form) {
        var recaptchaDiv = form.querySelector('.veloria-contact-form__recaptcha');
        if (!recaptchaDiv || recaptchaDiv.getAttribute('data-rendered') === 'yes') return;
        var siteKey = form.getAttribute('data-sitekey') || recaptchaDiv.getAttribute('data-sitekey');
        if (!siteKey) return;

        loadRecaptchaScript(siteKey, function () {
            if (!window.grecaptcha || !window.grecaptcha.render) return;
            try {
                var widgetId = window.grecaptcha.render(recaptchaDiv, { sitekey: siteKey });
                recaptchaDiv.setAttribute('data-widget-id', String(widgetId));
                recaptchaDiv.setAttribute('data-rendered', 'yes');
            } catch (e) {}
        });
    }

    function getRecaptchaResponse(form) {
        var recaptchaDiv = form.querySelector('.veloria-contact-form__recaptcha');
        if (!recaptchaDiv) return '';
        var widgetId = recaptchaDiv.getAttribute('data-widget-id');
        if (widgetId === null || widgetId === undefined) return '';
        try {
            return window.grecaptcha.getResponse(parseInt(widgetId, 10)) || '';
        } catch (e) {
            return '';
        }
    }

    function initForm(form) {
        if (form.getAttribute('data-contact-form-inited') === 'yes') return;
        form.setAttribute('data-contact-form-inited', 'yes');

        var useRecaptcha = form.getAttribute('data-recaptcha') === '1';
        if (useRecaptcha) renderRecaptcha(form);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var messageEl = getMessageEl(form);
            if (messageEl) messageEl.textContent = '';
            setSubmitting(form, true);

            var ajaxUrl = form.getAttribute('data-ajax-url');
            var recipient = form.getAttribute('data-recipient');
            var fieldsConfigRaw = form.getAttribute('data-fields-config');
            var fieldsConfig = [];
            try {
                fieldsConfig = fieldsConfigRaw ? JSON.parse(fieldsConfigRaw) : [];
            } catch (err) {}

            var formData = new FormData(form);
            formData.set('action', 'veloria_contact_form');
            formData.set('recipient', recipient || '');
            formData.set('use_recaptcha', useRecaptcha ? '1' : '0');
            formData.set('fields_config', JSON.stringify(fieldsConfig));

            var recaptchaDiv = form.querySelector('.veloria-contact-form__recaptcha');
            var widgetId = recaptchaDiv ? recaptchaDiv.getAttribute('data-widget-id') : null;
            if (useRecaptcha) {
                var token = '';
                try {
                    if (widgetId !== null && widgetId !== undefined) token = window.grecaptcha.getResponse(parseInt(widgetId, 10));
                } catch (err) {}
                if (!token) {
                    showMessage(form, form.getAttribute('data-error-msg') || 'Please complete the captcha.', true);
                    setSubmitting(form, false);
                    return;
                }
                formData.set('recaptcha_token', token);
            }

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
                    showMessage(form, msg || (success ? 'Sent.' : 'Error.'), !success);
                    if (success && useRecaptcha && window.grecaptcha && recaptchaDiv) {
                        try {
                            window.grecaptcha.reset(parseInt(widgetId, 10));
                        } catch (err) {}
                    }
                    if (success) form.reset();
                })
                .catch(function (err) {
                    setSubmitting(form, false);
                    var fallback = form.getAttribute('data-error-msg') || 'Something went wrong. Please try again.';
                    showMessage(form, fallback, true);
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
            elementorFrontend.hooks.addAction('frontend/element_ready/veloria-contact-form.default', function ($scope) {
                $scope.find(FORM_SEL).each(function () { initForm(this); });
            });
        });
    }
})();
