<?php
namespace Veloria\Admin;

defined('ABSPATH') || exit;

/**
 * WidgetsAssetController class
 *
 * @package Veloria
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
            'handle' => 'veloria-advanced-heading',
            'file'   => 'assets/css/advanced-heading.css'
        ],
        [
            'handle' => 'veloria-advanced-tabs',
            'file'   => 'assets/css/advanced-tabs.css'
        ],
        [
            'handle' => 'veloria-content-toggle',
            'file'   => 'assets/css/content-toggle.css'
        ],
        [
            'handle' => 'veloria-advanced-google-map',
            'file'   => 'assets/css/advanced-google-map.css'
        ],
        [
            'handle' => 'veloria-smart-post-list',
            'file'   => 'assets/css/smart-post-list.css'
        ],
        [
            'handle' => 'veloria-text-editor',
            'file'   => 'assets/css/text-editor.css'
        ],
        [
            'handle' => 'veloria-video-player',
            'file'   => 'assets/css/video-player.css'
        ],
        [
            'handle' => 'veloria-video-gallery',
            'file'   => 'assets/css/video-gallery.css'
        ],
        [
            'handle' => 'veloria-image-box',
            'file'   => 'assets/css/image-box.css'
        ],
        [
            'handle' => 'veloria-author-box',
            'file'   => 'assets/css/author-box.css'
        ],
        [
            'handle' => 'veloria-banner',
            'file'   => 'assets/css/banner.css'
        ],
        [
            'handle' => 'veloria-icon-box',
            'file'   => 'assets/css/icon-box.css'
        ],
        [
            'handle' => 'veloria-icon-list',
            'file'   => 'assets/css/icon-list.css'
        ],
        [
            'handle' => 'veloria-modal-popup',
            'file'   => 'assets/css/modal-popup.css'
        ],
        [
            'handle' => 'veloria-navigation-menu',
            'file'   => 'assets/css/navigation-menu.css'
        ],
        [
            'handle' => 'veloria-notice-board',
            'file'   => 'assets/css/notice-board.css'
        ],
        [
            'handle' => 'veloria-news-ticker',
            'file'   => 'assets/css/news-ticker.css'
        ],
        [
            'handle' => 'veloria-mini-cart',
            'file'   => 'assets/css/mini-cart.css'
        ],
        [
            'handle' => 'veloria-divider',
            'file'   => 'assets/css/divider.css'
        ],
        [
            'handle' => 'veloria-spacer',
            'file'   => 'assets/css/spacer.css'
        ],
        [
            'handle' => 'veloria-blockquote',
            'file'   => 'assets/css/blockquote.css'
        ],
        [
            'handle' => 'veloria-animated-counter',
            'file'   => 'assets/css/animated-counter.css'
        ],
        [
            'handle' => 'veloria-timeline',
            'file'   => 'assets/css/timeline.css'
        ],
        [
            'handle' => 'veloria-star-rating',
            'file'   => 'assets/css/star-rating.css'
        ],
        [
            'handle' => 'veloria-image-carousel',
            'file'   => 'assets/css/image-carousel.css'
        ],
        [
            'handle' => 'veloria-product-carousel',
            'file'   => 'assets/css/product-carousel.css'
        ],
        [
            'handle' => 'veloria-product-grid',
            'file'   => 'assets/css/product-grid.css'
        ],
        [
            'handle' => 'veloria-product-tabs',
            'file'   => 'assets/css/product-tabs.css'
        ],
        [
            'handle' => 'veloria-product-image',
            'file'   => 'assets/css/product-image.css'
        ],
        [
            'handle' => 'veloria-woo-checkout',
            'file'   => 'assets/css/woo-checkout.css'
        ],
        [
            'handle' => 'veloria-woo-dashboard',
            'file'   => 'assets/css/woo-dashboard.css'
        ],
        [
            'handle' => 'veloria-woo-thank-you',
            'file'   => 'assets/css/woo-thank-you.css'
        ],
        [
            'handle' => 'veloria-product-categories',
            'file'   => 'assets/css/product-categories.css'
        ],
        [
            'handle' => 'veloria-add-to-cart',
            'file'   => 'assets/css/add-to-cart.css'
        ],
        [
            'handle' => 'veloria-testimonials',
            'file'   => 'assets/css/testimonials.css'
        ],
        [
            'handle' => 'veloria-tooltip',
            'file'   => 'assets/css/tooltip.css'
        ],
        [
            'handle' => 'veloria-countdown-timer',
            'file'   => 'assets/css/countdown-timer.css'
        ],
        [
            'handle' => 'veloria-image-hotspot',
            'file'   => 'assets/css/image-hotspot.css'
        ],
        [
            'handle' => 'veloria-line-chart',
            'file'   => 'assets/css/line-chart.css'
        ],
        [
            'handle' => 'veloria-area-chart',
            'file'   => 'assets/css/area-chart.css'
        ],
        [
            'handle' => 'veloria-bar-chart',
            'file'   => 'assets/css/bar-chart.css'
        ],
        [
            'handle' => 'veloria-pie-chart',
            'file'   => 'assets/css/pie-chart.css'
        ],
        [
            'handle' => 'veloria-radar-chart',
            'file'   => 'assets/css/radar-chart.css'
        ],
        [
            'handle' => 'veloria-donut-chart',
            'file'   => 'assets/css/donut-chart.css'
        ],
        [
            'handle' => 'veloria-advanced-analytics',
            'file'   => 'assets/css/advanced-analytics.css'
        ],
        [
            'handle' => 'veloria-contact-form',
            'file'   => 'assets/css/contact-form.css'
        ],
        [
            'handle' => 'veloria-contact-form-7-styler',
            'file'   => 'assets/css/contact-form-7-styler.css'
        ],
        [
            'handle' => 'veloria-subscribe-form',
            'file'   => 'assets/css/subscribe-form.css'
        ],
        [
            'handle' => 'veloria-table-of-contents',
            'file'   => 'assets/css/table-of-contents.css'
        ],
        [
            'handle' => 'veloria-team-members',
            'file'   => 'assets/css/team-members.css'
        ],
        [
            'handle' => 'veloria-logo-showcase',
            'file'   => 'assets/css/logo-showcase.css'
        ],
        [
            'handle' => 'veloria-lottie-animation',
            'file'   => 'assets/css/lottie-animation.css'
        ],
        [
            'handle' => 'veloria-progress-bar',
            'file'   => 'assets/css/progress-bar.css'
        ],
        [
            'handle' => 'veloria-circular-progress',
            'file'   => 'assets/css/circular-progress.css'
        ],
        [
            'handle' => 'veloria-pricing-table',
            'file'   => 'assets/css/pricing-table.css'
        ],
        [
            'handle' => 'veloria-data-table',
            'file'   => 'assets/css/data-table.css'
        ],
        [
            'handle' => 'veloria-comparison-table',
            'file'   => 'assets/css/comparison-table.css'
        ],
        [
            'handle' => 'veloria-call-to-action',
            'file'   => 'assets/css/call-to-action.css'
        ],
        [
            'handle' => 'veloria-social-share',
            'file'   => 'assets/css/social-share.css'
        ],
        [
            'handle' => 'veloria-instagram-feed',
            'file'   => 'assets/css/instagram-feed.css'
        ],
        [
            'handle' => 'veloria-twitter-feed',
            'file'   => 'assets/css/twitter-feed.css'
        ],
        [
            'handle' => 'veloria-usp-items',
            'file'   => 'assets/css/usp-items.css'
        ],
        [
            'handle' => 'veloria-flip-box',
            'file'   => 'assets/css/flip-box.css'
        ],
        [
            'handle' => 'veloria-code-snippet',
            'file'   => 'assets/css/code-snippet.css'
        ],
        [
            'handle' => 'veloria-highlight',
            'file'   => 'assets/css/highlight.css'
        ],
        [
            'handle' => 'veloria-hero-banner',
            'file'   => 'assets/css/hero-banner.css'
        ],
        [
            'handle' => 'veloria-hero-slider',
            'file'   => 'assets/css/hero-slider.css'
        ],
        [
            'handle' => 'veloria-dual-heading',
            'file'   => 'assets/css/dual-heading.css'
        ],
        [
            'handle' => 'veloria-animated-gradient',
            'file'   => 'assets/css/animated-gradient.css'
        ],
        [
            'handle' => 'veloria-animated-svg',
            'file'   => 'assets/css/animated-svg.css'
        ],
        [
            'handle' => 'veloria-bento-grid',
            'file'   => 'assets/css/bento-grid.css'
        ],
        [
            'handle' => 'veloria-dropcap',
            'file'   => 'assets/css/dropcap.css'
        ],
        [
            'handle' => 'veloria-off-canvas-content',
            'file'   => 'assets/css/off-canvas-content.css'
        ],
        [
            'handle' => 'veloria-image-gallery',
            'file'   => 'assets/css/image-gallery.css'
        ],
        [
            'handle' => 'veloria-animated-text',
            'file'   => 'assets/css/animated-text.css'
        ],
        [
            'handle' => 'veloria-before-after-slider',
            'file'   => 'assets/css/before-after-slider.css'
        ],
        [
            'handle' => 'veloria-promo-box',
            'file'   => 'assets/css/promo-box.css'
        ],
        [
            'handle' => 'veloria-price-list',
            'file'   => 'assets/css/price-list.css'
        ]
    ];

    private array $scripts = [
        [
            'handle' => 'veloria-advanced-tabs',
            'file'   => 'assets/js/advanced-tabs.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-content-toggle',
            'file'   => 'assets/js/content-toggle.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-advanced-google-map',
            'file'   => 'assets/js/advanced-google-map.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-video-player',
            'file'   => 'assets/js/video-player.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-video-gallery',
            'file'   => 'assets/js/video-gallery.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-image-box',
            'file'   => 'assets/js/image-box.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-modal-popup',
            'file'   => 'assets/js/modal-popup.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-navigation-menu',
            'file'   => 'assets/js/navigation-menu.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-hero-banner',
            'file'   => 'assets/js/hero-banner.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-hero-slider',
            'file'   => 'assets/js/hero-slider.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'swiper'],
        ],
        [
            'handle' => 'veloria-notice-board',
            'file'   => 'assets/js/notice-board.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-animated-counter',
            'file'   => 'assets/js/animated-counter.js',
            'footer' => true
        ],
        [
            'handle' => 'veloria-animated-svg',
            'file'   => 'assets/js/animated-svg.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'veloria-image-carousel',
            'file'   => 'assets/js/image-carousel.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'veloria-product-carousel',
            'file'   => 'assets/js/product-carousel.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'veloria-product-grid',
            'file'   => 'assets/js/product-grid.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'veloria-product-tabs',
            'file'   => 'assets/js/product-tabs.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'veloria-product-image',
            'file'   => 'assets/js/product-image.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-woo-checkout',
            'file'   => 'assets/js/woo-checkout.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'wc-checkout'],
        ],
        [
            'handle' => 'veloria-woo-dashboard',
            'file'   => 'assets/js/woo-dashboard.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery', 'wc-country-select', 'wc-address-i18n'],
        ],
        [
            'handle' => 'veloria-product-categories',
            'file'   => 'assets/js/product-categories.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-add-to-cart',
            'file'   => 'assets/js/add-to-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
        ],
        [
            'handle' => 'veloria-mini-cart',
            'file'   => 'assets/js/mini-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
        ],
        [
            'handle' => 'veloria-testimonials',
            'file'   => 'assets/js/testimonials.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'veloria-tooltip',
            'file'   => 'assets/js/tooltip.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-countdown-timer',
            'file'   => 'assets/js/countdown-timer.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-promo-box',
            'file'   => 'assets/js/promo-box.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-image-hotspot',
            'file'   => 'assets/js/image-hotspot.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-line-chart',
            'file'   => 'assets/js/line-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-area-chart',
            'file'   => 'assets/js/area-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'veloria-bar-chart',
            'file'   => 'assets/js/bar-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-pie-chart',
            'file'   => 'assets/js/pie-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-radar-chart',
            'file'   => 'assets/js/radar-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'veloria-donut-chart',
            'file'   => 'assets/js/donut-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'veloria-advanced-analytics',
            'file'   => 'assets/js/advanced-analytics.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'veloria-contact-form',
            'file'   => 'assets/js/contact-form.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-subscribe-form',
            'file'   => 'assets/js/subscribe-form.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-table-of-contents',
            'file'   => 'assets/js/table-of-contents.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-team-members',
            'file'   => 'assets/js/team-members.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'veloria-logo-showcase',
            'file'   => 'assets/js/logo-showcase.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'veloria-lottie-web',
            'file'   => 'assets/js/lib/lottie/lottie.min.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-lottie-animation',
            'file'   => 'assets/js/lottie-animation.js',
            'footer' => true,
            'deps'   => ['veloria-lottie-web'],
        ],
        [
            'handle' => 'veloria-dual-heading-editor',
            'file'   => 'assets/js/dual-heading-editor.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'veloria-instagram-feed',
            'file'   => 'assets/js/instagram-feed.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'veloria-flip-box',
            'file'   => 'assets/js/flip-box.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-code-snippet',
            'file'   => 'assets/js/code-snippet.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-off-canvas-content',
            'file'   => 'assets/js/off-canvas-content.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-image-gallery',
            'file'   => 'assets/js/image-gallery.js',
            'footer' => true,
        ],
        [
            'handle' => 'veloria-animated-text',
            'file'   => 'assets/js/animated-text.js',
            'footer' => true,
            'deps'   => ['jquery'],
        ],
        [
            'handle' => 'veloria-before-after-slider',
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
        if (!defined('VELORIA_URL') || !defined('VELORIA_VERSION')) {
            return;
        }
        foreach ($this->styles as $asset) {
            wp_register_style(
                $asset['handle'],
                VELORIA_URL . $asset['file'],
                [],
                VELORIA_VERSION
            );
        }
    }

    /**
     * Register the scripts
     */
    public function register_scripts(): void
    {
        if (!defined('VELORIA_URL') || !defined('VELORIA_VERSION')) {
            return;
        }
        foreach ($this->scripts as $asset) {
            wp_register_script(
                $asset['handle'],
                VELORIA_URL . $asset['file'],
                $asset['deps'] ?? [],
                VELORIA_VERSION,
                $asset['footer'] ?? true
            );
        }
    }
}
