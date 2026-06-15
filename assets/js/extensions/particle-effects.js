/**
 * particles.js overlays for Elementor (data-veloria-particles on the wrapper).
 */
(function ($) {
	'use strict';

	var DETECT_ON = 'window';

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

	function particleCount(density) {
		var d = parseInt(density, 10);
		if (isNaN(d)) {
			d = 80;
		}
		return Math.max(15, Math.min(220, Math.round(d * 1.1)));
	}

	function lineColorOr(nodeColor, lineColor) {
		var l = normalizeColorValue(lineColor);
		return l !== '' ? l : nodeColor;
	}

	function repulseHover(distance, duration) {
		return {
			detect_on: DETECT_ON,
			events: { onhover: { enable: true, mode: 'repulse' }, onclick: { enable: false }, resize: true },
			modes: { repulse: { distance: distance, duration: duration } },
		};
	}

	function applySpeedPercent(cfg, speedPercent) {
		var pct = parseInt(speedPercent, 10);
		if (isNaN(pct) || pct < 5) {
			pct = 100;
		}
		pct = Math.max(10, Math.min(300, pct));
		var factor = pct / 100;
		if (cfg && cfg.particles && cfg.particles.move && typeof cfg.particles.move.speed === 'number') {
			cfg.particles.move.speed = Math.max(0.05, cfg.particles.move.speed * factor);
		}
		return cfg;
	}

	function baseParticles(nodeColor, lineColor, count) {
		var lc = lineColorOr(nodeColor, lineColor);

		return {
			number: { value: count, density: { enable: true, value_area: 800 } },
			color: { value: nodeColor },
			shape: { type: 'circle' },
			opacity: { value: 0.55, random: true },
			size: { value: 3, random: true },
			line_linked: {
				enable: true,
				distance: 150,
				color: lc,
				opacity: 0.35,
				width: 1,
			},
			move: { enable: true, speed: 1.6, direction: 'none', random: true, out_mode: 'out' },
		};
	}

	var PRESETS = {
		default: function (nodeColor, lineColor, count) {
			return {
				particles: baseParticles(nodeColor, lineColor, count),
				interactivity: repulseHover(130, 0.45),
				retina_detect: true,
			};
		},
		snow: function (nodeColor, lineColor, count) {
			return {
				particles: {
					number: { value: Math.min(160, Math.round(count * 1.4)), density: { enable: true, value_area: 800 } },
					color: { value: nodeColor },
					shape: { type: 'circle' },
					opacity: { value: 0.85, random: true },
					size: { value: 4, random: true },
					line_linked: { enable: false },
					move: { enable: true, speed: 2.2, direction: 'bottom', random: false, straight: true, out_mode: 'out' },
				},
				interactivity: repulseHover(150, 0.45),
				retina_detect: true,
			};
		},
		bubbles: function (nodeColor, lineColor, count) {
			return {
				particles: {
					number: { value: Math.round(count * 0.45), density: { enable: true, value_area: 800 } },
					color: { value: nodeColor },
					shape: { type: 'circle' },
					opacity: { value: 0.25, random: true },
					size: { value: 28, random: true },
					line_linked: { enable: false },
					move: { enable: true, speed: 1.2, direction: 'top', random: true, out_mode: 'out' },
				},
				interactivity: repulseHover(180, 0.4),
				retina_detect: true,
			};
		},
		stardust: function (nodeColor, lineColor, count) {
			return {
				particles: {
					number: { value: Math.min(280, Math.round(count * 2)), density: { enable: true, value_area: 600 } },
					color: { value: nodeColor },
					shape: { type: 'star' },
					opacity: { value: 0.9, random: true },
					size: { value: 2, random: true },
					line_linked: { enable: false },
					move: { enable: true, speed: 0.35, direction: 'none', random: true, out_mode: 'out' },
				},
				interactivity: repulseHover(100, 0.35),
				retina_detect: true,
			};
		},
		polygons: function (nodeColor, lineColor, count) {
			var lc = lineColorOr(nodeColor, lineColor);

			return {
				particles: {
					number: { value: Math.round(count * 0.55), density: { enable: true, value_area: 800 } },
					color: { value: nodeColor },
					shape: { type: 'polygon', polygon: { nb_sides: 5 } },
					opacity: { value: 0.5, random: true },
					size: { value: 5, random: true },
					line_linked: {
						enable: true,
						distance: 160,
						color: lc,
						opacity: 0.25,
						width: 1,
					},
					move: { enable: true, speed: 1.4, direction: 'none', random: true, out_mode: 'out' },
				},
				interactivity: repulseHover(125, 0.4),
				retina_detect: true,
			};
		},
		links: function (nodeColor, lineColor, count) {
			var lc = lineColorOr(nodeColor, lineColor);

			return {
				particles: {
					number: { value: Math.round(count * 0.65), density: { enable: true, value_area: 900 } },
					color: { value: nodeColor },
					shape: { type: 'circle' },
					opacity: { value: 0.45, random: false },
					size: { value: 2, random: false },
					line_linked: {
						enable: true,
						distance: 220,
						color: lc,
						opacity: 0.5,
						width: 1.5,
					},
					move: { enable: true, speed: 0.9, direction: 'none', random: true, out_mode: 'out' },
				},
				interactivity: repulseHover(140, 0.42),
				retina_detect: true,
			};
		},
		nyan: function (nodeColor, lineColor, count) {
			return {
				particles: {
					number: { value: Math.min(90, Math.round(count * 0.5)), density: { enable: true, value_area: 800 } },
					color: { value: nodeColor },
					shape: { type: 'circle' },
					opacity: { value: 0.75, random: true },
					size: { value: 4, random: true },
					line_linked: { enable: false },
					move: { enable: true, speed: 4.5, direction: 'right', random: false, straight: false, out_mode: 'out' },
				},
				interactivity: repulseHover(115, 0.32),
				retina_detect: true,
			};
		},
	};

	function resolveParticleHostId($el) {
		var fromAttr = ($el.attr('data-veloria-particles-host-id') || '').trim();
		if (fromAttr) {
			return fromAttr;
		}
		var dataId = ($el.attr('data-id') || '').trim();
		if (dataId) {
			return dataId;
		}
		var elId = $el.attr('id') || '';
		var m = /^elementor-element-(.+)$/.exec(elId);
		if (m && m[1]) {
			return m[1];
		}
		return '';
	}

	function buildFingerprint($el) {
		return [
			$el.attr('data-veloria-particles-preset') || '',
			$el.attr('data-veloria-particles-density') || '',
			$el.attr('data-veloria-particles-speed') || '100',
			$el.attr('data-veloria-particles-node') || '',
			$el.attr('data-veloria-particles-line') || '',
		].join('|');
	}

	function buildConfig($el) {
		var preset = $el.attr('data-veloria-particles-preset') || 'default';
		var density = $el.attr('data-veloria-particles-density') || '80';
		var speedPct = $el.attr('data-veloria-particles-speed') || '100';
		var nodeColor = normalizeColorValue($el.attr('data-veloria-particles-node')) || '#ffffff';
		var lineColor = normalizeColorValue($el.attr('data-veloria-particles-line'));
		var count = particleCount(density);
		var fn = PRESETS[preset] || PRESETS.default;
		var cfg = fn(nodeColor, lineColor, count);
		return applySpeedPercent(cfg, speedPct);
	}

	function initElement($el) {
		if ($el.attr('data-veloria-particles') !== 'yes') {
			return;
		}
		if (typeof window.particlesJS !== 'function') {
			return;
		}
		var rawId = resolveParticleHostId($el);
		if (!rawId) {
			return;
		}
		var fp = buildFingerprint($el);
		var prevFp = $el.attr('data-veloria-particles-fp');
		var hasCanvas = $el.find('.veloria-particles__host canvas').length > 0;
		if (prevFp === fp && hasCanvas) {
			return;
		}

		var safeId = String(rawId).replace(/[^a-zA-Z0-9_-]/g, '');
		var domId = 'veloria-particles-host-' + safeId;
		$el.find('.veloria-particles__host').remove();
		var $host = $('<div class="veloria-particles__host"></div>').attr('id', domId);
		$el.prepend($host);
		window.particlesJS(domId, buildConfig($el));
		$el.attr('data-veloria-particles-fp', fp);
	}

	var PARTICLE_ATTR_SEL = '[data-veloria-particles="yes"]';

	function collectParticleElements($root) {
		if (!$root || !$root.length) {
			return $();
		}
		return $root.filter(PARTICLE_ATTR_SEL).add($root.find(PARTICLE_ATTR_SEL));
	}

	function scan($scope) {
		var $root = $scope && $scope.length ? $scope : $(document.body);
		if (!$root.length) {
			return;
		}
		collectParticleElements($root).each(function () {
			initElement($(this));
		});
	}

	window.veloriaParticlesRescan = function (doc) {
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
