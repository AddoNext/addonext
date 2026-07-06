/**
 * Floating circle backdrops for wrappers with data-addonext-moving-circles.
 */
(function ($) {
	'use strict';

	function normalizeColorValue(raw) {
		if (raw === null || raw === undefined) {
			return '';
		}
		if (typeof raw === 'string') {
			return raw.trim();
		}
		if (typeof raw === 'object') {
			if (raw.color) {
				return String(raw.color).trim();
			}
			if (typeof raw.value === 'string' && raw.value.trim() !== '') {
				return raw.value.trim();
			}
		}
		return String(raw).trim();
	}

	function buildFingerprint($el) {
		return [
			$el.attr('data-addonext-moving-circles-count') || '',
			$el.attr('data-addonext-moving-circles-speed') || '100',
			$el.attr('data-addonext-moving-circles-size') || '',
			$el.attr('data-addonext-moving-circles-color') || '',
		].join('|');
	}

	function buildHost(count, sizePx, color, speedPercent) {
		var $host = $('<div class="addonext-moving-circles__host" aria-hidden="true"></div>');
		var el = $host[0];
		var baseDurSec = Math.max(8, (20 * 100) / Math.max(20, speedPercent));
		el.style.setProperty('--addonext-mc-fill', color);
		el.style.setProperty('--addonext-mc-dur', baseDurSec + 's');
		el.style.setProperty('--addonext-mc-size', sizePx + 'px');
		var i;
		for (i = 0; i < count; i += 1) {
			$host.append($('<span class="addonext-moving-circles__circle"></span>'));
		}
		return $host;
	}

	function initElement($el) {
		if ($el.attr('data-addonext-moving-circles') !== 'yes') {
			return;
		}
		var count = parseInt($el.attr('data-addonext-moving-circles-count'), 10);
		if (isNaN(count)) {
			count = 10;
		}
		count = Math.max(3, Math.min(28, count));
		var speedPct = parseInt($el.attr('data-addonext-moving-circles-speed'), 10);
		if (isNaN(speedPct)) {
			speedPct = 100;
		}
		speedPct = Math.max(20, Math.min(200, speedPct));
		var sizePx = parseInt($el.attr('data-addonext-moving-circles-size'), 10);
		if (isNaN(sizePx)) {
			sizePx = 48;
		}
		sizePx = Math.max(12, Math.min(140, sizePx));
		var color = normalizeColorValue($el.attr('data-addonext-moving-circles-color')) || 'rgba(255,255,255,0.35)';
		var fp = buildFingerprint($el);
		var prevFp = $el.attr('data-addonext-moving-circles-fp');
		var hasHost = $el.find('.addonext-moving-circles__host').length > 0;
		if (prevFp === fp && hasHost) {
			return;
		}
		$el.find('.addonext-moving-circles__host').remove();
		$el.prepend(buildHost(count, sizePx, color, speedPct));
		$el.attr('data-addonext-moving-circles-fp', fp);
	}

	var MC_ATTR_SEL = '[data-addonext-moving-circles="yes"]';

	function collectMcElements($root) {
		if (!$root || !$root.length) {
			return $();
		}
		return $root.filter(MC_ATTR_SEL).add($root.find(MC_ATTR_SEL));
	}

	function scan($scope) {
		var $root = $scope && $scope.length ? $scope : $(document.body);
		if (!$root.length) {
			return;
		}
		collectMcElements($root).each(function () {
			initElement($(this));
		});
	}

	window.addonextMovingCirclesRescan = function (doc) {
		var d = doc || document;
		if (!d.body) {
			return;
		}
		scan($(d.body));
	};

	var elementorHookBound = false;

	function bindElementor() {
		if (elementorHookBound || typeof window.elementorFrontend === 'undefined' || !elementorFrontend.hooks) {
			return;
		}
		elementorHookBound = true;
		elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
			scan($scope);
		});
	}

	$(window).on('elementor/frontend/init', bindElementor);
	if (typeof window.elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
		bindElementor();
	}

	function scheduleEditorPreviewRescans() {
		if (window.location.search.indexOf('elementor-preview') === -1) {
			return;
		}
		[0, 500, 2000].forEach(function (delayMs) {
			window.setTimeout(function () {
				bindElementor();
				scan($(document.body));
			}, delayMs);
		});
	}

	$(function () {
		scan($(document.body));
		scheduleEditorPreviewRescans();
	});
})(jQuery);
