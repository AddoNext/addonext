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
            'handle' => 'elementrify-author-box',
            'file'   => 'assets/css/author-box.css'
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
            'handle' => 'elementrify-mini-cart',
            'file'   => 'assets/css/mini-cart.css'
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
            'handle' => 'elementrify-product-carousel',
            'file'   => 'assets/css/product-carousel.css'
        ],
        [
            'handle' => 'elementrify-product-grid',
            'file'   => 'assets/css/product-grid.css'
        ],
        [
            'handle' => 'elementrify-product-categories',
            'file'   => 'assets/css/product-categories.css'
        ],
        [
            'handle' => 'elementrify-add-to-cart',
            'file'   => 'assets/css/add-to-cart.css'
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
            'handle' => 'elementrify-area-chart',
            'file'   => 'assets/css/area-chart.css'
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
            'handle' => 'elementrify-radar-chart',
            'file'   => 'assets/css/radar-chart.css'
        ],
        [
            'handle' => 'elementrify-donut-chart',
            'file'   => 'assets/css/donut-chart.css'
        ],
        [
            'handle' => 'elementrify-advanced-analytics',
            'file'   => 'assets/css/advanced-analytics.css'
        ],
        [
            'handle' => 'elementrify-contact-form',
            'file'   => 'assets/css/contact-form.css'
        ],
        [
            'handle' => 'elementrify-subscribe-form',
            'file'   => 'assets/css/subscribe-form.css'
        ],
        [
            'handle' => 'elementrify-table-of-contents',
            'file'   => 'assets/css/table-of-contents.css'
        ],
        [
            'handle' => 'elementrify-team-members',
            'file'   => 'assets/css/team-members.css'
        ],
        [
            'handle' => 'elementrify-logo-showcase',
            'file'   => 'assets/css/logo-showcase.css'
        ],
        [
            'handle' => 'elementrify-lottie-animation',
            'file'   => 'assets/css/lottie-animation.css'
        ],
        [
            'handle' => 'elementrify-progress-bar',
            'file'   => 'assets/css/progress-bar.css'
        ],
        [
            'handle' => 'elementrify-circular-progress',
            'file'   => 'assets/css/circular-progress.css'
        ],
        [
            'handle' => 'elementrify-pricing-table',
            'file'   => 'assets/css/pricing-table.css'
        ],
        [
            'handle' => 'elementrify-data-table',
            'file'   => 'assets/css/data-table.css'
        ],
        [
            'handle' => 'elementrify-comparison-table',
            'file'   => 'assets/css/comparison-table.css'
        ],
        [
            'handle' => 'elementrify-call-to-action',
            'file'   => 'assets/css/call-to-action.css'
        ],
        [
            'handle' => 'elementrify-social-share',
            'file'   => 'assets/css/social-share.css'
        ],
        [
            'handle' => 'elementrify-instagram-feed',
            'file'   => 'assets/css/instagram-feed.css'
        ],
        [
            'handle' => 'elementrify-flip-box',
            'file'   => 'assets/css/flip-box.css'
        ],
        [
            'handle' => 'elementrify-code-snippet',
            'file'   => 'assets/css/code-snippet.css'
        ],
        [
            'handle' => 'elementrify-highlight',
            'file'   => 'assets/css/highlight.css'
        ],
        [
            'handle' => 'elementrify-dual-heading',
            'file'   => 'assets/css/dual-heading.css'
        ],
        [
            'handle' => 'elementrify-animated-gradient',
            'file'   => 'assets/css/animated-gradient.css'
        ],
        [
            'handle' => 'elementrify-bento-grid',
            'file'   => 'assets/css/bento-grid.css'
        ],
        [
            'handle' => 'elementrify-dropcap',
            'file'   => 'assets/css/dropcap.css'
        ],
        [
            'handle' => 'elementrify-off-canvas-content',
            'file'   => 'assets/css/off-canvas-content.css'
        ],
        [
            'handle' => 'elementrify-image-gallery',
            'file'   => 'assets/css/image-gallery.css'
        ],
        [
            'handle' => 'elementrify-animated-text',
            'file'   => 'assets/css/animated-text.css'
        ],
        [
            'handle' => 'elementrify-before-after-slider',
            'file'   => 'assets/css/before-after-slider.css'
        ],
        [
            'handle' => 'elementrify-promo-box',
            'file'   => 'assets/css/promo-box.css'
        ],
        [
            'handle' => 'elementrify-price-list',
            'file'   => 'assets/css/price-list.css'
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
            'handle' => 'elementrify-product-carousel',
            'file'   => 'assets/js/product-carousel.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'elementrify-product-grid',
            'file'   => 'assets/js/product-grid.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'elementrify-product-categories',
            'file'   => 'assets/js/product-categories.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend'],
        ],
        [
            'handle' => 'elementrify-add-to-cart',
            'file'   => 'assets/js/add-to-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
        ],
        [
            'handle' => 'elementrify-mini-cart',
            'file'   => 'assets/js/mini-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
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
            'handle' => 'elementrify-promo-box',
            'file'   => 'assets/js/promo-box.js',
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
            'handle' => 'elementrify-area-chart',
            'file'   => 'assets/js/area-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
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
            'handle' => 'elementrify-radar-chart',
            'file'   => 'assets/js/radar-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'elementrify-donut-chart',
            'file'   => 'assets/js/donut-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'elementrify-advanced-analytics',
            'file'   => 'assets/js/advanced-analytics.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
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
        ],
        [
            'handle' => 'elementrify-table-of-contents',
            'file'   => 'assets/js/table-of-contents.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-team-members',
            'file'   => 'assets/js/team-members.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'elementrify-logo-showcase',
            'file'   => 'assets/js/logo-showcase.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'elementrify-lottie-web',
            'file'   => 'assets/js/lib/lottie/lottie.min.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-lottie-animation',
            'file'   => 'assets/js/lottie-animation.js',
            'footer' => true,
            'deps'   => ['elementrify-lottie-web'],
        ],
        [
            'handle' => 'elementrify-dual-heading-editor',
            'file'   => 'assets/js/dual-heading-editor.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'elementrify-instagram-feed',
            'file'   => 'assets/js/instagram-feed.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'elementrify-flip-box',
            'file'   => 'assets/js/flip-box.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-code-snippet',
            'file'   => 'assets/js/code-snippet.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-off-canvas-content',
            'file'   => 'assets/js/off-canvas-content.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-image-gallery',
            'file'   => 'assets/js/image-gallery.js',
            'footer' => true,
        ],
        [
            'handle' => 'elementrify-animated-text',
            'file'   => 'assets/js/animated-text.js',
            'footer' => true,
            'deps'   => ['jquery'],
        ],
        [
            'handle' => 'elementrify-before-after-slider',
            'file'   => 'assets/js/before-after-slider.js',
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
