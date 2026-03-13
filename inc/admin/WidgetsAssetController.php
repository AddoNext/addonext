<?php
namespace Elementrify\Admin;

defined('ABSPATH') || exit;

/**
 * WidgetsAssetController class
 *
 * @package Elementrify
 * @subpackage Admin
 * @since 1.0.0
 */
class WidgetsAssetController
{
    /**
     * The list of styles to register
     *
     * @var array
     */
    private array $styles = [
        [
            'handle' => 'elementrify-advanced-heading',
            'file'   => 'assets/css/advanced-heading.css'
        ],
        [
            'handle' => 'elementrify-advanced-tabs',
            'file'   => 'assets/css/advanced-tabs.css'
        ],
        [
            'handle' => 'elementrify-content-toggle',
            'file'   => 'assets/css/content-toggle.css'
        ],
        [
            'handle' => 'elementrify-advanced-google-map',
            'file'   => 'assets/css/advanced-google-map.css'
        ],
        [
            'handle' => 'elementrify-smart-post-list',
            'file'   => 'assets/css/smart-post-list.css'
        ],
        [
            'handle' => 'elementrify-text-editor',
            'file'   => 'assets/css/text-editor.css'
        ],
        [
            'handle' => 'elementrify-video-player',
            'file'   => 'assets/css/video-player.css'
        ],
        [
            'handle' => 'elementrify-image-box',
            'file'   => 'assets/css/image-box.css'
        ],
        [
            'handle' => 'elementrify-icon-box',
            'file'   => 'assets/css/icon-box.css'
        ],
        [
            'handle' => 'elementrify-icon-list',
            'file'   => 'assets/css/icon-list.css'
        ],
        [
            'handle' => 'elementrify-modal-popup',
            'file'   => 'assets/css/modal-popup.css'
        ],
        [
            'handle' => 'elementrify-divider',
            'file'   => 'assets/css/divider.css'
        ],
        [
            'handle' => 'elementrify-spacer',
            'file'   => 'assets/css/spacer.css'
        ],
        [
            'handle' => 'elementrify-blockquote',
            'file'   => 'assets/css/blockquote.css'
        ],
        [
            'handle' => 'elementrify-animated-counter',
            'file'   => 'assets/css/animated-counter.css'
        ],
        [
            'handle' => 'elementrify-timeline',
            'file'   => 'assets/css/timeline.css'
        ],
        [
            'handle' => 'elementrify-star-rating',
            'file'   => 'assets/css/star-rating.css'
        ],
        [
            'handle' => 'elementrify-image-carousel',
            'file'   => 'assets/css/image-carousel.css'
        ],
        [
            'handle' => 'elementrify-testimonials',
            'file'   => 'assets/css/testimonials.css'
        ],
        [
            'handle' => 'elementrify-tooltip',
            'file'   => 'assets/css/tooltip.css'
        ],
        [
            'handle' => 'elementrify-countdown-timer',
            'file'   => 'assets/css/countdown-timer.css'
        ],
        [
            'handle' => 'elementrify-image-hotspot',
            'file'   => 'assets/css/image-hotspot.css'
        ],
        [
            'handle' => 'elementrify-line-chart',
            'file'   => 'assets/css/line-chart.css'
        ],
        [
            'handle' => 'elementrify-bar-chart',
            'file'   => 'assets/css/bar-chart.css'
        ],
        [
            'handle' => 'elementrify-pie-chart',
            'file'   => 'assets/css/pie-chart.css'
        ],
        [
            'handle' => 'elementrify-contact-form',
            'file'   => 'assets/css/contact-form.css'
        ],
        [
            'handle' => 'elementrify-subscribe-form',
            'file'   => 'assets/css/subscribe-form.css'
        ]
    ];

    private array $scripts = [
        [
            'handle' => 'elementrify-advanced-tabs',
            'file'   => 'assets/js/advanced-tabs.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-content-toggle',
            'file'   => 'assets/js/content-toggle.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-advanced-google-map',
            'file'   => 'assets/js/advanced-google-map.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-video-player',
            'file'   => 'assets/js/video-player.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-image-box',
            'file'   => 'assets/js/image-box.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-modal-popup',
            'file'   => 'assets/js/modal-popup.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-animated-counter',
            'file'   => 'assets/js/animated-counter.js',
            'footer' => true
        ],
        [
            'handle' => 'elementrify-image-carousel',
            'file'   => 'assets/js/image-carousel.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'elementrify-testimonials',
            'file'   => 'assets/js/testimonials.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'elementrify-tooltip',
            'file'   => 'assets/js/tooltip.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-countdown-timer',
            'file'   => 'assets/js/countdown-timer.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-image-hotspot',
            'file'   => 'assets/js/image-hotspot.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-line-chart',
            'file'   => 'assets/js/line-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-bar-chart',
            'file'   => 'assets/js/bar-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-pie-chart',
            'file'   => 'assets/js/pie-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-contact-form',
            'file'   => 'assets/js/contact-form.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-subscribe-form',
            'file'   => 'assets/js/subscribe-form.js',
            'footer' => true,
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('elementor/frontend/after_register_styles', [$this, 'register_styles'], 20);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'register_styles'], 20);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts'], 20);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'register_scripts'], 21);
    }

    /**
     * Register the styles
     */
    public function register_styles(): void
    {
        if (!defined('ELEMENTRIFY_URL') || !defined('ELEMENTRIFY_VERSION')) {
            return;
        }
        foreach ($this->styles as $asset) {
            wp_register_style(
                $asset['handle'],
                ELEMENTRIFY_URL . $asset['file'],
                [],
                ELEMENTRIFY_VERSION
            );
        }
    }

    /**
     * Register the scripts
     */
    public function register_scripts(): void
    {
        if (!defined('ELEMENTRIFY_URL') || !defined('ELEMENTRIFY_VERSION')) {
            return;
        }
        foreach ($this->scripts as $asset) {
            wp_register_script(
                $asset['handle'],
                ELEMENTRIFY_URL . $asset['file'],
                $asset['deps'] ?? [],
                ELEMENTRIFY_VERSION,
                $asset['footer'] ?? true
            );
        }
    }
}
