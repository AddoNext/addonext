/**
 * Syncs particle data attributes from the editor model into the preview iframe, then rescans.
 * Uses both Container tree iteration and raw document.config.elements (Backbone-free) for reliability.
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

	function clearParticleMarkup(el) {
		if (!el) {
			return;
		}
		var host = el.querySelector('.veloria-particles__host');
		if (host) {
			host.remove();
		}
		el.classList.remove('veloria-particles');
		el.removeAttribute('data-veloria-particles');
		el.removeAttribute('data-veloria-particles-host-id');
		el.removeAttribute('data-veloria-particles-preset');
		el.removeAttribute('data-veloria-particles-density');
		el.removeAttribute('data-veloria-particles-speed');
		el.removeAttribute('data-veloria-particles-node');
		el.removeAttribute('data-veloria-particles-line');
		el.removeAttribute('data-veloria-particles-fp');
	}

	function applyParticleMarkupFromPlainSettings(el, id, plain) {
		if (!el || !plain || typeof plain !== 'object') {
			return;
		}
		var enabled = plain.veloria_particles_enable;
		if (enabled !== 'yes') {
			clearParticleMarkup(el);
			return;
		}
		var preset = plain.veloria_particles_preset || 'default';
		var density = plain.veloria_particles_density;
		var densitySize = 80;
		if (density && typeof density === 'object' && density.size !== undefined && density.size !== null) {
			densitySize = density.size;
		}
		var speed = plain.veloria_particles_speed;
		var speedSize = 100;
		if (speed && typeof speed === 'object' && speed.size !== undefined && speed.size !== null) {
			speedSize = speed.size;
		} else if (typeof speed === 'number') {
			speedSize = speed;
		}
		speedSize = Math.max(10, Math.min(300, parseInt(speedSize, 10) || 100));

		var nodeRaw = plain.veloria_particles_node_color;
		if (nodeRaw === undefined || nodeRaw === null || nodeRaw === '') {
			nodeRaw = plain.veloria_particles_color;
		}
		var nodeColor = normalizeElementorColor(nodeRaw) || '#ffffff';
		var lineColor = normalizeElementorColor(plain.veloria_particles_line_color);

		el.classList.add('veloria-particles');
		el.setAttribute('data-veloria-particles', 'yes');
		el.setAttribute('data-veloria-particles-host-id', id);
		el.setAttribute('data-veloria-particles-preset', String(preset));
		el.setAttribute('data-veloria-particles-density', String(densitySize));
		el.setAttribute('data-veloria-particles-speed', String(speedSize));
		el.setAttribute('data-veloria-particles-node', nodeColor);
		el.removeAttribute('data-veloria-particles-fp');
		if (lineColor) {
			el.setAttribute('data-veloria-particles-line', lineColor);
		} else {
			el.removeAttribute('data-veloria-particles-line');
		}
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
				'veloria_particles_enable',
				'veloria_particles_preset',
				'veloria_particles_density',
				'veloria_particles_speed',
				'veloria_particles_node_color',
				'veloria_particles_line_color',
				'veloria_particles_color',
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

	function syncParticleMarkupFromEditorIntoPreview() {
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
			applyParticleMarkupFromPlainSettings(el, safeId, plainSettings);
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
		if (typeof win.veloriaParticlesRescan !== 'function') {
			return;
		}
		try {
			win.veloriaParticlesRescan(frame.contentDocument);
		} catch (e) {
			/* ignore cross-origin or incomplete load */
		}
	}

	function runFullParticlePreviewRefresh() {
		syncParticleMarkupFromEditorIntoPreview();
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

	var debouncedRefresh = debounce(runFullParticlePreviewRefresh, 280);

	$(function () {
		if (typeof window.elementor === 'undefined') {
			return;
		}

		elementor.on('preview:loaded', function () {
			[100, 600, 2200].forEach(function (ms) {
				setTimeout(runFullParticlePreviewRefresh, ms);
			});
		});

		if (elementor.channels && elementor.channels.editor) {
			elementor.channels.editor.on('change', debouncedRefresh);
			elementor.channels.editor.on('editor:element:style:update', function () {
				runFullParticlePreviewRefresh();
			});
		}

		['widget', 'section', 'column', 'container'].forEach(function (elType) {
			elementor.hooks.addAction('panel/open_editor/' + elType, function () {
				setTimeout(runFullParticlePreviewRefresh, 120);
			});
		});

		elementor.on('document:loaded', function () {
			setTimeout(runFullParticlePreviewRefresh, 200);
		});
	});
})(jQuery);
