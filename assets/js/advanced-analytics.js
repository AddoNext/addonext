(function () {
  'use strict';

  function parseValueParts(raw) {
    var value = String(raw || '').trim();
    var match = value.match(/^([^\d-]*)(-?[\d,.]+)(.*)$/);
    if (!match) {
      return { prefix: '', number: null, suffix: '', decimals: 0 };
    }

    var prefix = match[1] || '';
    var numberPart = match[2] || '';
    var suffix = match[3] || '';
    var normalized = numberPart.replace(/,/g, '');
    var number = parseFloat(normalized);
    var decimalMatch = normalized.match(/\.(\d+)/);
    var decimals = decimalMatch ? decimalMatch[1].length : 0;

    return {
      prefix: prefix,
      number: isNaN(number) ? null : number,
      suffix: suffix,
      decimals: decimals,
    };
  }

  function formatNumber(number, decimals) {
    var fixed = number.toFixed(decimals);
    var parts = fixed.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return parts.join(decimals > 0 ? '.' : '');
  }

  function animateElement(el, duration) {
    if (!el || el.getAttribute('data-aa-animated') === '1') {
      return;
    }

    var raw = el.getAttribute('data-aa-value') || el.textContent || '';
    var parsed = parseValueParts(raw);
    if (parsed.number === null) {
      return;
    }

    el.setAttribute('data-aa-animated', '1');

    var start = null;
    var target = parsed.number;

    function frame(ts) {
      if (start === null) {
        start = ts;
      }
      var progress = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = target * eased;

      el.textContent = parsed.prefix + formatNumber(current, parsed.decimals) + parsed.suffix;

      if (progress < 1) {
        window.requestAnimationFrame(frame);
      } else {
        el.textContent = raw;
      }
    }

    window.requestAnimationFrame(frame);
  }

  function animateWidget(widget) {
    if (!widget || widget.getAttribute('data-aa-animated-widget') === '1') {
      return;
    }
    widget.setAttribute('data-aa-animated-widget', '1');

    var durationRaw = parseInt(widget.getAttribute('data-aa-duration') || '1400', 10);
    var duration = isNaN(durationRaw) ? 1400 : Math.max(200, durationRaw);
    var values = widget.querySelectorAll('.elementrify-advanced-analytics__value[data-aa-value]');
    values.forEach(function (el) {
      animateElement(el, duration);
    });
  }

  function isInViewport(el) {
    if (!el || !el.getBoundingClientRect) {
      return false;
    }
    var rect = el.getBoundingClientRect();
    var vh = window.innerHeight || document.documentElement.clientHeight || 0;
    return rect.top <= vh * 0.9 && rect.bottom >= 0;
  }

  function setupVisibilityTrigger(widgets) {
    if (!widgets || !widgets.length) {
      return;
    }

    if (typeof IntersectionObserver !== 'undefined') {
      var observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateWidget(entry.target);
            obs.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.2,
        rootMargin: '0px 0px -10% 0px',
      });

      widgets.forEach(function (widget) {
        observer.observe(widget);
      });
      return;
    }

    function checkAndRun() {
      widgets.forEach(function (widget) {
        if (widget.getAttribute('data-aa-animated-widget') === '1') {
          return;
        }
        if (isInViewport(widget)) {
          animateWidget(widget);
        }
      });
    }

    checkAndRun();
    window.addEventListener('scroll', checkAndRun, { passive: true });
    window.addEventListener('resize', checkAndRun);
  }

  function run(root) {
    var scope = root || document;
    var widgets = scope.querySelectorAll('.elementrify-advanced-analytics[data-aa-animate="yes"]');
    setupVisibilityTrigger(Array.prototype.slice.call(widgets));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { run(document); });
  } else {
    run(document);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-advanced-analytics.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) {
        return;
      }
      var values = el.querySelectorAll('.elementrify-advanced-analytics__value[data-aa-value]');
      values.forEach(function (node) { node.removeAttribute('data-aa-animated'); });
      var widgets = el.querySelectorAll('.elementrify-advanced-analytics[data-aa-animate="yes"]');
      widgets.forEach(function (widget) { widget.removeAttribute('data-aa-animated-widget'); });
      run(el);
    });
  }
})();
