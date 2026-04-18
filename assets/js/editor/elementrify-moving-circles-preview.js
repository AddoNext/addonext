/**
 * Syncs moving-circles data attributes from the editor model into the preview iframe, then rescans.
 */
(function ($) {
	'use strict';

	function getPreviewFrame() {
		return document.getElementById('elementor-preview-iframe');
	}

	function getPreviewDocument() {
		var frame = getPreviewFrame();
		return frame && frame.contentDocument ? frame.contentDocument : null;
	}

	function getPostId() {
		if (window.elementor && elementor.config && elementor.config.document) {
			return elementor.config.document.id;
		}
		return null;
	}

	function getDocumentRootContainer() {
		if (typeof window.elementor === 'undefined' || !elementor.documents) {
			return null;
		}
		var doc = elementor.documents.getCurrent();
		if (!doc) {
			return null;
		}
		if (doc.container) {
			return doc.container;
		}
		if (typeof doc.getContainer === 'function') {
			return doc.getContainer();
		}
		return null;
	}

	function walkContainerTree(callback) {
		var root = getDocumentRootContainer();
		if (!root || !root.children) {
			return;
		}
		try {
			if (typeof root.children.forEachRecursive === 'function') {
				root.children.forEachRecursive(callback);
				return;
			}
		} catch (e) {
			/* fall through */
		}
		try {
			if (typeof root.forEachChildrenRecursive === 'function') {
				root.forEachChildrenRecursive(callback);
			}
		} catch (e2) {
			/* ignore */
		}
	}

	function walkConfigElements(items, callback) {
		if (!Array.isArray(items)) {
			return;
		}
		items.forEach(function (item) {
			if (!item || typeof item !== 'object') {
				return;
			}
			callback(item);
			if (Array.isArray(item.elements)) {
				walkConfigElements(item.elements, callback);
			}
		});
	}

	function getConfigElementsRoot() {
		if (typeof window.elementor === 'undefined' || !elementor.documents) {
			return null;
		}
		var doc = elementor.documents.getCurrent();
		if (!doc) {
			return null;
		}
		if (doc.config && Array.isArray(doc.config.elements)) {
			return doc.config.elements;
		}
		if (elementor.config && elementor.config.document && Array.isArray(elementor.config.document.elements)) {
			return elementor.config.document.elements;
		}
		return null;
	}

	function normalizeElementorColor(val) {
		if (val === null || val === undefined) {
			return '';
		}
		if (typeof val === 'string') {
			return val.trim();
		}
		if (typeof val === 'object') {
			if (val.color) {
				return String(val.color).trim();
			}
			if (typeof val.value === 'string' && val.value.trim() !== '') {
				return val.value.trim();
			}
		}
		return String(val).trim();
	}

	function clearMovingCirclesMarkup(el) {
		if (!el) {
			return;
		}
		var host = el.querySelector('.elementrify-moving-circles__host');
		if (host) {
			host.remove();
		}
		el.classList.remove('elementrify-moving-circles');
		el.removeAttribute('data-elementrify-moving-circles');
		el.removeAttribute('data-elementrify-moving-circles-host-id');
		el.removeAttribute('data-elementrify-moving-circles-count');
		el.removeAttribute('data-elementrify-moving-circles-speed');
		el.removeAttribute('data-elementrify-moving-circles-size');
		el.removeAttribute('data-elementrify-moving-circles-color');
		el.removeAttribute('data-elementrify-moving-circles-fp');
	}

	function applyMovingCirclesMarkupFromPlainSettings(el, id, plain) {
		if (!el || !plain || typeof plain !== 'object') {
			return;
		}
		var enabled = plain.elementrify_moving_circles_enable;
		if (enabled !== 'yes') {
			clearMovingCirclesMarkup(el);
			return;
		}
		var count = plain.elementrify_moving_circles_count;
		var countSize = 10;
		if (count && typeof count === 'object' && count.size !== undefined && count.size !== null) {
			countSize = count.size;
		}
		countSize = Math.max(3, Math.min(28, parseInt(countSize, 10) || 10));
		var speed = plain.elementrify_moving_circles_speed;
		var speedSize = 100;
		if (speed && typeof speed === 'object' && speed.size !== undefined && speed.size !== null) {
			speedSize = speed.size;
		} else if (typeof speed === 'number') {
			speedSize = speed;
		}
		speedSize = Math.max(20, Math.min(200, parseInt(speedSize, 10) || 100));
		var sizeCtrl = plain.elementrify_moving_circles_size;
		var sizePx = 48;
		if (sizeCtrl && typeof sizeCtrl === 'object' && sizeCtrl.size !== undefined && sizeCtrl.size !== null) {
			sizePx = sizeCtrl.size;
		}
		sizePx = Math.max(12, Math.min(140, parseInt(sizePx, 10) || 48));
		var circleColor = normalizeElementorColor(plain.elementrify_moving_circles_color) || '#ffffff';

		el.classList.add('elementrify-moving-circles');
		el.setAttribute('data-elementrify-moving-circles', 'yes');
		el.setAttribute('data-elementrify-moving-circles-host-id', id);
		el.setAttribute('data-elementrify-moving-circles-count', String(countSize));
		el.setAttribute('data-elementrify-moving-circles-speed', String(speedSize));
		el.setAttribute('data-elementrify-moving-circles-size', String(sizePx));
		el.setAttribute('data-elementrify-moving-circles-color', circleColor);
		el.removeAttribute('data-elementrify-moving-circles-fp');
	}

	function resolveContainerId(container) {
		if (!container) {
			return '';
		}
		if (container.id) {
			return String(container.id);
		}
		if (container.model && typeof container.model.get === 'function') {
			var mid = container.model.get('id');
			if (mid) {
				return String(mid);
			}
		}
		return '';
	}

	function plainSettingsFromContainer(container) {
		var settings = container.settings;
		if (!settings) {
			return null;
		}
		var plain = null;
		if (typeof settings.toJSON === 'function') {
			try {
				plain = settings.toJSON();
			} catch (e) {
				plain = null;
			}
		}
		if (!plain && settings.attributes && typeof settings.attributes === 'object') {
			plain = settings.attributes;
		}
		if (!plain || typeof plain !== 'object') {
			return null;
		}
		if (typeof settings.get === 'function') {
			[
				'elementrify_moving_circles_enable',
				'elementrify_moving_circles_count',
				'elementrify_moving_circles_speed',
				'elementrify_moving_circles_size',
				'elementrify_moving_circles_color',
			].forEach(function (key) {
				var live = settings.get(key);
				if (live !== undefined) {
					plain[key] = live;
				}
			});
		}
		return plain;
	}

	function findPreviewNode(iframeDoc, postId, safeId) {
		if (!iframeDoc || !safeId) {
			return null;
		}
		var esc = typeof CSS !== 'undefined' && CSS.escape ? CSS.escape(safeId) : safeId.replace(/\\/g, '\\\\').replace(/"/g, '\\"');
		var scope =
			iframeDoc.querySelector('.elementor-' + postId) ||
			iframeDoc.querySelector('[data-elementor-id="' + String(postId) + '"]') ||
			iframeDoc.body;
		if (!scope) {
			return null;
		}
		var selectors = [
			'.elementor-element[data-id="' + esc + '"]',
			'.e-con[data-id="' + esc + '"]',
			'[data-id="' + esc + '"]',
			'.elementor-element.elementor-element-' + esc,
		];
		var i;
		var el = null;
		for (i = 0; i < selectors.length; i += 1) {
			el = scope.querySelector(selectors[i]);
			if (el) {
				return el;
			}
		}
		return iframeDoc.querySelector(selectors[0]) || iframeDoc.querySelector(selectors[2]);
	}

	function syncMovingCirclesMarkupFromEditorIntoPreview() {
		var iframeDoc = getPreviewDocument();
		if (!iframeDoc || typeof window.elementor === 'undefined') {
			return;
		}
		var postId = getPostId();
		if (!postId) {
			return;
		}

		var seen = Object.create(null);

		function syncOne(id, plainSettings) {
			if (!id || !plainSettings) {
				return;
			}
			var safeId = String(id).replace(/[^a-zA-Z0-9_-]/g, '');
			if (!safeId) {
				return;
			}
			var el = findPreviewNode(iframeDoc, postId, safeId);
			if (!el) {
				return;
			}
			seen[safeId] = true;
			applyMovingCirclesMarkupFromPlainSettings(el, safeId, plainSettings);
		}

		walkContainerTree(function (container) {
			var id = resolveContainerId(container);
			var plain = plainSettingsFromContainer(container);
			if (id && plain) {
				syncOne(id, plain);
			}
		});

		var cfgRoot = getConfigElementsRoot();
		if (cfgRoot) {
			walkConfigElements(cfgRoot, function (item) {
				var id = item.id;
				if (!id) {
					return;
				}
				var safeCfgId = String(id).replace(/[^a-zA-Z0-9_-]/g, '');
				if (safeCfgId && seen[safeCfgId]) {
					return;
				}
				var plain = item.settings;
				if (plain && typeof plain === 'object') {
					syncOne(id, plain);
				}
			});
		}
	}

	function rescanPreviewIframe() {
		var frame = getPreviewFrame();
		if (!frame || !frame.contentWindow) {
			return;
		}
		var win = frame.contentWindow;
		if (typeof win.elementrifyMovingCirclesRescan !== 'function') {
			return;
		}
		try {
			win.elementrifyMovingCirclesRescan(frame.contentDocument);
		} catch (e) {
			/* ignore */
		}
	}

	function runFullMovingCirclesPreviewRefresh() {
		syncMovingCirclesMarkupFromEditorIntoPreview();
		rescanPreviewIframe();
	}

	function debounce(fn, ms) {
		var t;
		return function () {
			clearTimeout(t);
			var args = arguments;
			t = setTimeout(function () {
				fn.apply(null, args);
			}, ms);
		};
	}

	var debouncedRefresh = debounce(runFullMovingCirclesPreviewRefresh, 280);

	$(function () {
		if (typeof window.elementor === 'undefined') {
			return;
		}

		elementor.on('preview:loaded', function () {
			[100, 600, 2200].forEach(function (ms) {
				setTimeout(runFullMovingCirclesPreviewRefresh, ms);
			});
		});

		if (elementor.channels && elementor.channels.editor) {
			elementor.channels.editor.on('change', debouncedRefresh);
			elementor.channels.editor.on('editor:element:style:update', function () {
				runFullMovingCirclesPreviewRefresh();
			});
		}

		['widget', 'section', 'column', 'container'].forEach(function (elType) {
			elementor.hooks.addAction('panel/open_editor/' + elType, function () {
				setTimeout(runFullMovingCirclesPreviewRefresh, 120);
			});
		});

		elementor.on('document:loaded', function () {
			setTimeout(runFullMovingCirclesPreviewRefresh, 200);
		});
	});
})(jQuery);
