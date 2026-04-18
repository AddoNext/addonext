/**
 * Live preview for Elementrify custom CSS / JS in the Elementor editor iframe.
 */
(function ($) {
	'use strict';

	var cfg = window.elementrifyEditorCustomCode || { stylesActive: false, scriptsActive: false };
	var debouncers = {};

	if (!cfg.stylesActive && !cfg.scriptsActive) {
		return;
	}

	function debounceByKey(key, fn, delay) {
		return function () {
			clearTimeout(debouncers[key]);
			debouncers[key] = setTimeout(fn, delay);
		};
	}

	function getPreviewDocument() {
		var frame = document.getElementById('elementor-preview-iframe');
		return frame && frame.contentDocument ? frame.contentDocument : null;
	}

	function getPostId() {
		if (window.elementor && elementor.config && elementor.config.document) {
			return elementor.config.document.id;
		}
		return null;
	}

	function buildWrapperSelector(postId, elementId) {
		return '.elementor-' + postId + ' .elementor-element.elementor-element-' + elementId;
	}

	function getSettingsModel(model) {
		if (!model || typeof model.get !== 'function') {
			return null;
		}
		var settings = model.get('settings');
		if (settings && typeof settings.get === 'function') {
			return settings;
		}
		return null;
	}

	function resolveModelFromView(view) {
		try {
			if (!view) {
				return null;
			}
			var container = view.getContainer && view.getContainer();
			if (container && container.model) {
				return container.model;
			}
			return view.model || null;
		} catch (e) {
			return null;
		}
	}

	function applyForModel(model) {
		if (!cfg.stylesActive || !model) {
			return;
		}
		var doc = getPreviewDocument();
		var postId = getPostId();
		var elementId = model.get('id');
		if (!doc || !postId || !elementId) {
			return;
		}
		var settings = getSettingsModel(model);
		if (!settings) {
			return;
		}
		var raw = settings.get('elementrify_custom_css');
		var css = typeof raw === 'string' ? raw.trim() : '';
		var styleId = 'elementrify-editor-live-css-' + elementId;
		var existing = doc.getElementById(styleId);
		if (!css) {
			if (existing) {
				existing.remove();
			}
			return;
		}
		var selector = buildWrapperSelector(postId, elementId);
		var processed = css.split('selector').join(selector);
		if (!existing) {
			existing = doc.createElement('style');
			existing.id = styleId;
			doc.head.appendChild(existing);
		}
		existing.textContent = processed;
	}

	function applyJsForModel(model) {
		if (!cfg.scriptsActive || !model) {
			return;
		}
		var doc = getPreviewDocument();
		var elementId = model.get('id');
		if (!doc || !elementId) {
			return;
		}
		var settings = getSettingsModel(model);
		if (!settings) {
			return;
		}
		var raw = settings.get('elementrify_custom_js');
		var js = typeof raw === 'string' ? raw.trim() : '';
		var scriptId = 'elementrify-editor-live-js-' + elementId;
		var existing = doc.getElementById(scriptId);
		if (existing) {
			existing.remove();
		}
		if (!js) {
			return;
		}
		var scr = doc.createElement('script');
		scr.id = scriptId;
		scr.appendChild(doc.createTextNode(js));
		(doc.body || doc.documentElement).appendChild(scr);
	}

	function walkViews(view, visit) {
		if (!view) {
			return;
		}
		visit(view);
		var children = view.children;
		if (children && typeof children.each === 'function') {
			children.each(function (child) {
				walkViews(child, visit);
			});
		}
	}

	function refreshAllLiveStyles() {
		if (!window.elementor || typeof elementor.getPreviewView !== 'function') {
			return;
		}
		var main = elementor.getPreviewView();
		if (!main) {
			return;
		}
		walkViews(main, function (v) {
			var m = resolveModelFromView(v);
			if (m) {
				applyForModel(m);
				applyJsForModel(m);
			}
		});
	}

	function bindSettingsListener(model) {
		if (!model || model._elementrifyCodePreviewBound) {
			return;
		}
		var settings = getSettingsModel(model);
		if (!settings || typeof settings.on !== 'function') {
			return;
		}
		model._elementrifyCodePreviewBound = true;
		var elId = model.get('id') || 'unknown';
		var run = debounceByKey('elementrify-live-' + elId, function () {
			applyForModel(model);
			applyJsForModel(model);
		}, 200);
		settings.on('change', run);
	}

	function registerPanelHooks() {
		if (!window.elementor || !elementor.hooks) {
			return;
		}
		['widget', 'section', 'column', 'container'].forEach(function (elType) {
			elementor.hooks.addAction('panel/open_editor/' + elType, function (panel, model) {
				bindSettingsListener(model);
				applyForModel(model);
				applyJsForModel(model);
			});
		});
	}

	$(function () {
		if (typeof window.elementor === 'undefined') {
			return;
		}
		elementor.on('preview:loaded', function () {
			setTimeout(refreshAllLiveStyles, 150);
		});
		elementor.on('document:loaded', function () {
			setTimeout(refreshAllLiveStyles, 150);
		});
		registerPanelHooks();
		if (elementor.channels && elementor.channels.editor) {
			elementor.channels.editor.on('editor:element:style:update', function (view) {
				var m = resolveModelFromView(view);
				if (m) {
					applyForModel(m);
					applyJsForModel(m);
				}
			});
			var channelRefresh = debounceByKey('elementrify-editor-channel', function () {
				var selected =
					elementor.selection && elementor.selection.getElements && elementor.selection.getElements()[0];
				if (!selected) {
					return;
				}
				var m = resolveModelFromView(selected);
				if (m) {
					applyForModel(m);
					applyJsForModel(m);
				}
			}, 200);
			elementor.channels.editor.on('change', channelRefresh);
		}
	});
})(jQuery);
