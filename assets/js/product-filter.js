(function () {
  'use strict';

  function setColorSwatchBackground(wrapper) {
    var swatches = wrapper.querySelectorAll('.elementrify-product-filter__color-option span[data-color]');
    for (var i = 0; i < swatches.length; i += 1) {
      var swatch = swatches[i];
      var color = swatch.getAttribute('data-color');
      if (!color) {
        continue;
      }
      swatch.style.backgroundColor = color;
    }
  }

  function formatRatingCount(n) {
    if (typeof n !== 'number' || isNaN(n) || n <= 0) {
      return '';
    }
    if (Math.abs(n % 1) < 0.001) {
      return String(Math.round(n));
    }
    return String(n);
  }

  function bindRatingStars(form) {
    var wrap = form.querySelector('.elementrify-product-filter__rating-stars-wrap');
    if (!wrap) {
      return;
    }

    var stars = wrap.querySelector('.elementrify-product-filter__rating-stars');
    var caption = wrap.querySelector('.elementrify-product-filter__rating-caption');
    var hidden = wrap.querySelector('.elementrify-product-filter__rating-hidden');
    if (!stars || !caption || !hidden) {
      return;
    }

    var textNone = wrap.getAttribute('data-text-none') || '';
    var textHover = wrap.getAttribute('data-text-hover') || '%s stars';
    var textSelected = wrap.getAttribute('data-text-selected') || '%s stars & up';

    var initial = parseFloat(wrap.getAttribute('data-initial-rating') || '0');
    if (isNaN(initial)) {
      initial = 0;
    }
    var selected = initial > 0 ? initial : 0;
    var hoverVal = null;

    function applyStarPairClasses() {
      var pairs = stars.querySelectorAll('.elementrify-product-filter__star-pair');
      for (var i = 0; i < pairs.length; i += 1) {
        var starIndex = i + 1;
        pairs[i].classList.remove('is-full', 'is-half');
        if (selected >= starIndex) {
          pairs[i].classList.add('is-full');
        } else if (selected >= starIndex - 0.5) {
          pairs[i].classList.add('is-half');
        }
      }
    }

    function syncHidden() {
      if (!hidden) {
        return;
      }
      if (!selected || selected <= 0) {
        hidden.value = '';
        hidden.disabled = true;
      } else {
        hidden.disabled = false;
        hidden.value = formatRatingCount(selected);
      }
    }

    function captionFor(value, isHover) {
      if (!value || value <= 0) {
        return textNone;
      }
      var label = formatRatingCount(value);
      var tpl = isHover ? textHover : textSelected;
      return tpl.split('%s').join(label);
    }

    function updateCaption() {
      if (hoverVal != null && hoverVal > 0) {
        caption.textContent = captionFor(hoverVal, true);
      } else {
        caption.textContent = captionFor(selected, false);
      }
    }

    function setSelected(next) {
      selected = next > 0 ? next : 0;
      applyStarPairClasses();
      syncHidden();
      updateCaption();
    }

    stars.addEventListener('click', function (ev) {
      var btn = ev.target.closest('.elementrify-product-filter__star-half');
      if (!btn || !stars.contains(btn)) {
        return;
      }
      var v = parseFloat(btn.getAttribute('data-value') || '0');
      if (isNaN(v)) {
        return;
      }
      if (Math.abs(selected - v) < 0.001) {
        setSelected(0);
      } else {
        setSelected(v);
      }
    });

    stars.addEventListener('pointerover', function (ev) {
      var btn = ev.target.closest('.elementrify-product-filter__star-half');
      if (!btn || !stars.contains(btn)) {
        return;
      }
      var v = parseFloat(btn.getAttribute('data-value') || '0');
      if (!isNaN(v) && v > 0) {
        hoverVal = v;
        updateCaption();
      }
    });

    stars.addEventListener('pointerout', function (ev) {
      if (!stars.contains(ev.relatedTarget)) {
        hoverVal = null;
        updateCaption();
      }
    });

    applyStarPairClasses();
    syncHidden();
    updateCaption();
  }

  function bindDualRange(wrapper) {
    var track = wrapper.querySelector('.elementrify-product-filter__dual-range');
    if (!track) {
      return;
    }

    var rangeMin = track.querySelector('.elementrify-product-filter__range--min');
    var rangeMax = track.querySelector('.elementrify-product-filter__range--max');
    if (!rangeMin || !rangeMax) {
      return;
    }

    var minId = rangeMin.getAttribute('data-min-input');
    var maxId = rangeMin.getAttribute('data-max-input');
    var minInput = minId ? wrapper.querySelector('#' + minId) : null;
    var maxInput = maxId ? wrapper.querySelector('#' + maxId) : null;
    if (!minInput || !maxInput) {
      var fallbackWrap = wrapper.querySelector('.elementrify-product-filter__price-inputs');
      if (fallbackWrap) {
        var nums = fallbackWrap.querySelectorAll('input[type="number"]');
        if (nums.length >= 2) {
          minInput = nums[0];
          maxInput = nums[1];
        }
      }
    }
    if (!minInput || !maxInput) {
      return;
    }

    var lowerLimit = parseInt(rangeMin.min, 10) || 0;
    var upperLimit = parseInt(rangeMin.max, 10) || 500;

    var fillEl = track.querySelector('.elementrify-product-filter__range-fill');

    function clamp(v) {
      if (v < lowerLimit) {
        return lowerLimit;
      }
      if (v > upperLimit) {
        return upperLimit;
      }
      return v;
    }

    function updateRangeFill() {
      if (!fillEl) {
        return;
      }
      var minVal = clamp(parseInt(rangeMin.value, 10) || lowerLimit);
      var maxVal = clamp(parseInt(rangeMax.value, 10) || upperLimit);
      var span = upperLimit - lowerLimit;
      if (span <= 0) {
        span = 1;
      }
      var pMin = ((minVal - lowerLimit) / span) * 100;
      var pMax = ((maxVal - lowerLimit) / span) * 100;
      var w = pMax - pMin;
      if (w < 0) {
        w = 0;
      }
      fillEl.style.left = pMin + '%';
      fillEl.style.width = w + '%';
    }

    function bringRangeToFront(active) {
      if (active === rangeMin) {
        rangeMin.style.zIndex = '5';
        rangeMax.style.zIndex = '4';
      } else {
        rangeMin.style.zIndex = '4';
        rangeMax.style.zIndex = '5';
      }
    }

    function syncFromMinRange() {
      var minVal = clamp(parseInt(rangeMin.value, 10) || lowerLimit);
      var maxVal = clamp(parseInt(rangeMax.value, 10) || upperLimit);
      if (minVal > maxVal) {
        rangeMax.value = String(minVal);
        maxVal = minVal;
      }
      minInput.value = String(minVal);
      maxInput.value = String(maxVal);
      updateRangeFill();
    }

    function syncFromMaxRange() {
      var minVal = clamp(parseInt(rangeMin.value, 10) || lowerLimit);
      var maxVal = clamp(parseInt(rangeMax.value, 10) || upperLimit);
      if (maxVal < minVal) {
        rangeMin.value = String(maxVal);
        minVal = maxVal;
      }
      minInput.value = String(minVal);
      maxInput.value = String(maxVal);
      updateRangeFill();
    }

    rangeMin.addEventListener('pointerdown', function () {
      bringRangeToFront(rangeMin);
    });
    rangeMax.addEventListener('pointerdown', function () {
      bringRangeToFront(rangeMax);
    });

    rangeMin.addEventListener('input', syncFromMinRange);
    rangeMax.addEventListener('input', syncFromMaxRange);

    minInput.addEventListener('change', function () {
      var minVal = clamp(parseInt(minInput.value, 10));
      if (isNaN(minVal)) {
        minVal = lowerLimit;
      }
      var maxVal = clamp(parseInt(maxInput.value, 10) || upperLimit);
      if (minVal > maxVal) {
        minVal = maxVal;
      }
      minInput.value = String(minVal);
      rangeMin.value = String(minVal);
      if (parseInt(rangeMax.value, 10) < minVal) {
        rangeMax.value = String(minVal);
        maxInput.value = String(minVal);
      }
      updateRangeFill();
    });

    maxInput.addEventListener('change', function () {
      var maxVal = clamp(parseInt(maxInput.value, 10));
      if (isNaN(maxVal)) {
        maxVal = upperLimit;
      }
      var minVal = clamp(parseInt(minInput.value, 10) || lowerLimit);
      if (maxVal < minVal) {
        maxVal = minVal;
      }
      maxInput.value = String(maxVal);
      rangeMax.value = String(maxVal);
      if (parseInt(rangeMin.value, 10) > maxVal) {
        rangeMin.value = String(maxVal);
        minInput.value = String(maxVal);
      }
      updateRangeFill();
    });

    updateRangeFill();
  }

  function bindFilterSubmit(wrapper) {
    var form = wrapper.tagName === 'FORM' ? wrapper : wrapper.querySelector('form.elementrify-product-filter');
    if (!form) {
      return;
    }

    form.addEventListener('submit', function () {
      var existingDynamicInputs = form.querySelectorAll('input[type="hidden"][data-elementrify-generated="1"]');
      for (var removeIndex = 0; removeIndex < existingDynamicInputs.length; removeIndex += 1) {
        existingDynamicInputs[removeIndex].remove();
      }

      var ratingHidden = form.querySelector('.elementrify-product-filter__rating-hidden');
      if (ratingHidden) {
        var rv = parseFloat(ratingHidden.value);
        if (!ratingHidden.value || isNaN(rv) || rv <= 0) {
          ratingHidden.value = '';
          ratingHidden.disabled = true;
        } else {
          ratingHidden.disabled = false;
        }
      }

      var groupedValues = {};
      var checkboxes = form.querySelectorAll('input[type="checkbox"][data-filter-target]');
      for (var i = 0; i < checkboxes.length; i += 1) {
        var checkbox = checkboxes[i];
        if (!checkbox.checked) {
          continue;
        }
        var target = checkbox.getAttribute('data-filter-target');
        if (!target) {
          continue;
        }
        if (!groupedValues[target]) {
          groupedValues[target] = [];
        }
        groupedValues[target].push(checkbox.value);
      }

      var keys = Object.keys(groupedValues);
      for (var j = 0; j < keys.length; j += 1) {
        var key = keys[j];
        if (!groupedValues[key].length) {
          continue;
        }
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = groupedValues[key].join(',');
        input.setAttribute('data-elementrify-generated', '1');
        form.appendChild(input);
      }
    });
  }

  function init(scope) {
    var wrappers = scope.querySelectorAll('.elementrify-product-filter');
    for (var i = 0; i < wrappers.length; i += 1) {
      var wrapper = wrappers[i];
      if (wrapper.getAttribute('data-elementrify-filter-init') === '1') {
        continue;
      }
      wrapper.setAttribute('data-elementrify-filter-init', '1');
      setColorSwatchBackground(wrapper);
      bindDualRange(wrapper);
      bindRatingStars(wrapper);
      bindFilterSubmit(wrapper);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      init(document);
    });
  } else {
    init(document);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/elementrify-product-filter.default', function ($scope) {
      var node = $scope && $scope[0] ? $scope[0] : $scope;
      if (node) {
        init(node);
      }
    });
  }
})();
