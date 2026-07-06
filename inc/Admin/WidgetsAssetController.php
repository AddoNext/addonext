<?php
namespace Addonext\Admin;

use Addonext\Utils\WooCommerceAssets;

defined('ABSPATH') || exit;

/**
 * WidgetsAssetController class
 *
 * @package AddoNext
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
            'handle' => 'addonext-advanced-heading',
            'file'   => 'assets/css/advanced-heading.css'
        ],
        [
            'handle' => 'addonext-advanced-tabs',
            'file'   => 'assets/css/advanced-tabs.css'
        ],
        [
            'handle' => 'addonext-content-toggle',
            'file'   => 'assets/css/content-toggle.css'
        ],
        [
            'handle' => 'addonext-advanced-google-map',
            'file'   => 'assets/css/advanced-google-map.css'
        ],
        [
            'handle' => 'addonext-smart-post-list',
            'file'   => 'assets/css/smart-post-list.css'
        ],
        [
            'handle' => 'addonext-text-editor',
            'file'   => 'assets/css/text-editor.css'
        ],
        [
            'handle' => 'addonext-video-player',
            'file'   => 'assets/css/video-player.css'
        ],
        [
            'handle' => 'addonext-video-gallery',
            'file'   => 'assets/css/video-gallery.css'
        ],
        [
            'handle' => 'addonext-image-box',
            'file'   => 'assets/css/image-box.css'
        ],
        [
            'handle' => 'addonext-author-box',
            'file'   => 'assets/css/author-box.css'
        ],
        [
            'handle' => 'addonext-banner',
            'file'   => 'assets/css/banner.css'
        ],
        [
            'handle' => 'addonext-icon-box',
            'file'   => 'assets/css/icon-box.css'
        ],
        [
            'handle' => 'addonext-icon-list',
            'file'   => 'assets/css/icon-list.css'
        ],
        [
            'handle' => 'addonext-modal-popup',
            'file'   => 'assets/css/modal-popup.css'
        ],
        [
            'handle' => 'addonext-navigation-menu',
            'file'   => 'assets/css/navigation-menu.css'
        ],
        [
            'handle' => 'addonext-notice-board',
            'file'   => 'assets/css/notice-board.css'
        ],
        [
            'handle' => 'addonext-news-ticker',
            'file'   => 'assets/css/news-ticker.css'
        ],
        [
            'handle' => 'addonext-mini-cart',
            'file'   => 'assets/css/mini-cart.css'
        ],
        [
            'handle' => 'addonext-divider',
            'file'   => 'assets/css/divider.css'
        ],
        [
            'handle' => 'addonext-spacer',
            'file'   => 'assets/css/spacer.css'
        ],
        [
            'handle' => 'addonext-blockquote',
            'file'   => 'assets/css/blockquote.css'
        ],
        [
            'handle' => 'addonext-animated-counter',
            'file'   => 'assets/css/animated-counter.css'
        ],
        [
            'handle' => 'addonext-timeline',
            'file'   => 'assets/css/timeline.css'
        ],
        [
            'handle' => 'addonext-star-rating',
            'file'   => 'assets/css/star-rating.css'
        ],
        [
            'handle' => 'addonext-image-carousel',
            'file'   => 'assets/css/image-carousel.css'
        ],
        [
            'handle' => 'addonext-product-carousel',
            'file'   => 'assets/css/product-carousel.css'
        ],
        [
            'handle' => 'addonext-product-grid',
            'file'   => 'assets/css/product-grid.css'
        ],
        [
            'handle' => 'addonext-product-tabs',
            'file'   => 'assets/css/product-tabs.css'
        ],
        [
            'handle' => 'addonext-product-image',
            'file'   => 'assets/css/product-image.css'
        ],
        [
            'handle' => 'addonext-woo-checkout',
            'file'   => 'assets/css/woo-checkout.css'
        ],
        [
            'handle' => 'addonext-woo-dashboard',
            'file'   => 'assets/css/woo-dashboard.css'
        ],
        [
            'handle' => 'addonext-woo-thank-you',
            'file'   => 'assets/css/woo-thank-you.css'
        ],
        [
            'handle' => 'addonext-product-categories',
            'file'   => 'assets/css/product-categories.css'
        ],
        [
            'handle' => 'addonext-add-to-cart',
            'file'   => 'assets/css/add-to-cart.css'
        ],
        [
            'handle' => 'addonext-testimonials',
            'file'   => 'assets/css/testimonials.css'
        ],
        [
            'handle' => 'addonext-tooltip',
            'file'   => 'assets/css/tooltip.css'
        ],
        [
            'handle' => 'addonext-countdown-timer',
            'file'   => 'assets/css/countdown-timer.css'
        ],
        [
            'handle' => 'addonext-image-hotspot',
            'file'   => 'assets/css/image-hotspot.css'
        ],
        [
            'handle' => 'addonext-line-chart',
            'file'   => 'assets/css/line-chart.css'
        ],
        [
            'handle' => 'addonext-area-chart',
            'file'   => 'assets/css/area-chart.css'
        ],
        [
            'handle' => 'addonext-bar-chart',
            'file'   => 'assets/css/bar-chart.css'
        ],
        [
            'handle' => 'addonext-pie-chart',
            'file'   => 'assets/css/pie-chart.css'
        ],
        [
            'handle' => 'addonext-radar-chart',
            'file'   => 'assets/css/radar-chart.css'
        ],
        [
            'handle' => 'addonext-donut-chart',
            'file'   => 'assets/css/donut-chart.css'
        ],
        [
            'handle' => 'addonext-advanced-analytics',
            'file'   => 'assets/css/advanced-analytics.css'
        ],
        [
            'handle' => 'addonext-contact-form',
            'file'   => 'assets/css/contact-form.css'
        ],
        [
            'handle' => 'addonext-contact-form-7-styler',
            'file'   => 'assets/css/contact-form-7-styler.css'
        ],
        [
            'handle' => 'addonext-subscribe-form',
            'file'   => 'assets/css/subscribe-form.css'
        ],
        [
            'handle' => 'addonext-table-of-contents',
            'file'   => 'assets/css/table-of-contents.css'
        ],
        [
            'handle' => 'addonext-team-members',
            'file'   => 'assets/css/team-members.css'
        ],
        [
            'handle' => 'addonext-logo-showcase',
            'file'   => 'assets/css/logo-showcase.css'
        ],
        [
            'handle' => 'addonext-lottie-animation',
            'file'   => 'assets/css/lottie-animation.css'
        ],
        [
            'handle' => 'addonext-progress-bar',
            'file'   => 'assets/css/progress-bar.css'
        ],
        [
            'handle' => 'addonext-circular-progress',
            'file'   => 'assets/css/circular-progress.css'
        ],
        [
            'handle' => 'addonext-pricing-table',
            'file'   => 'assets/css/pricing-table.css'
        ],
        [
            'handle' => 'addonext-data-table',
            'file'   => 'assets/css/data-table.css'
        ],
        [
            'handle' => 'addonext-comparison-table',
            'file'   => 'assets/css/comparison-table.css'
        ],
        [
            'handle' => 'addonext-call-to-action',
            'file'   => 'assets/css/call-to-action.css'
        ],
        [
            'handle' => 'addonext-social-share',
            'file'   => 'assets/css/social-share.css'
        ],
        [
            'handle' => 'addonext-instagram-feed',
            'file'   => 'assets/css/instagram-feed.css'
        ],
        [
            'handle' => 'addonext-twitter-feed',
            'file'   => 'assets/css/twitter-feed.css'
        ],
        [
            'handle' => 'addonext-usp-items',
            'file'   => 'assets/css/usp-items.css'
        ],
        [
            'handle' => 'addonext-flip-box',
            'file'   => 'assets/css/flip-box.css'
        ],
        [
            'handle' => 'addonext-code-snippet',
            'file'   => 'assets/css/code-snippet.css'
        ],
        [
            'handle' => 'addonext-highlight',
            'file'   => 'assets/css/highlight.css'
        ],
        [
            'handle' => 'addonext-hero-banner',
            'file'   => 'assets/css/hero-banner.css'
        ],
        [
            'handle' => 'addonext-hero-slider',
            'file'   => 'assets/css/hero-slider.css'
        ],
        [
            'handle' => 'addonext-dual-heading',
            'file'   => 'assets/css/dual-heading.css'
        ],
        [
            'handle' => 'addonext-animated-gradient',
            'file'   => 'assets/css/animated-gradient.css'
        ],
        [
            'handle' => 'addonext-animated-svg',
            'file'   => 'assets/css/animated-svg.css'
        ],
        [
            'handle' => 'addonext-bento-grid',
            'file'   => 'assets/css/bento-grid.css'
        ],
        [
            'handle' => 'addonext-dropcap',
            'file'   => 'assets/css/dropcap.css'
        ],
        [
            'handle' => 'addonext-off-canvas-content',
            'file'   => 'assets/css/off-canvas-content.css'
        ],
        [
            'handle' => 'addonext-image-gallery',
            'file'   => 'assets/css/image-gallery.css'
        ],
        [
            'handle' => 'addonext-animated-text',
            'file'   => 'assets/css/animated-text.css'
        ],
        [
            'handle' => 'addonext-before-after-slider',
            'file'   => 'assets/css/before-after-slider.css'
        ],
        [
            'handle' => 'addonext-promo-box',
            'file'   => 'assets/css/promo-box.css'
        ],
        [
            'handle' => 'addonext-price-list',
            'file'   => 'assets/css/price-list.css'
        ]
    ];

    private array $scripts = [
        [
            'handle' => 'addonext-advanced-tabs',
            'file'   => 'assets/js/advanced-tabs.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-content-toggle',
            'file'   => 'assets/js/content-toggle.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-advanced-google-map',
            'file'   => 'assets/js/advanced-google-map.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-video-player',
            'file'   => 'assets/js/video-player.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-video-gallery',
            'file'   => 'assets/js/video-gallery.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-image-box',
            'file'   => 'assets/js/image-box.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-modal-popup',
            'file'   => 'assets/js/modal-popup.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-navigation-menu',
            'file'   => 'assets/js/navigation-menu.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-hero-banner',
            'file'   => 'assets/js/hero-banner.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-hero-slider',
            'file'   => 'assets/js/hero-slider.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'swiper'],
        ],
        [
            'handle' => 'addonext-notice-board',
            'file'   => 'assets/js/notice-board.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-animated-counter',
            'file'   => 'assets/js/animated-counter.js',
            'footer' => true
        ],
        [
            'handle' => 'addonext-animated-svg',
            'file'   => 'assets/js/animated-svg.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'addonext-image-carousel',
            'file'   => 'assets/js/image-carousel.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'addonext-product-carousel',
            'file'   => 'assets/js/product-carousel.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'addonext-product-grid',
            'file'   => 'assets/js/product-grid.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'addonext-product-tabs',
            'file'   => 'assets/js/product-tabs.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'wc-add-to-cart'],
        ],
        [
            'handle' => 'addonext-product-image',
            'file'   => 'assets/js/product-image.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-woo-checkout',
            'file'   => 'assets/js/woo-checkout.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend', 'wc-checkout'],
        ],
        [
            'handle' => 'addonext-woo-dashboard',
            'file'   => 'assets/js/woo-dashboard.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery', 'wc-country-select', 'wc-address-i18n'],
        ],
        [
            'handle' => 'addonext-product-categories',
            'file'   => 'assets/js/product-categories.js',
            'footer' => true,
            'deps'   => ['swiper', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-add-to-cart',
            'file'   => 'assets/js/add-to-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
        ],
        [
            'handle' => 'addonext-mini-cart',
            'file'   => 'assets/js/mini-cart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend', 'jquery'],
        ],
        [
            'handle' => 'addonext-testimonials',
            'file'   => 'assets/js/testimonials.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'addonext-tooltip',
            'file'   => 'assets/js/tooltip.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-countdown-timer',
            'file'   => 'assets/js/countdown-timer.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-promo-box',
            'file'   => 'assets/js/promo-box.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-image-hotspot',
            'file'   => 'assets/js/image-hotspot.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-line-chart',
            'file'   => 'assets/js/line-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-area-chart',
            'file'   => 'assets/js/area-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'addonext-bar-chart',
            'file'   => 'assets/js/bar-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-pie-chart',
            'file'   => 'assets/js/pie-chart.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-radar-chart',
            'file'   => 'assets/js/radar-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'addonext-donut-chart',
            'file'   => 'assets/js/donut-chart.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'addonext-advanced-analytics',
            'file'   => 'assets/js/advanced-analytics.js',
            'footer' => true,
            'deps'   => ['elementor-frontend'],
        ],
        [
            'handle' => 'addonext-contact-form',
            'file'   => 'assets/js/contact-form.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-subscribe-form',
            'file'   => 'assets/js/subscribe-form.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-table-of-contents',
            'file'   => 'assets/js/table-of-contents.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-team-members',
            'file'   => 'assets/js/team-members.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'addonext-logo-showcase',
            'file'   => 'assets/js/logo-showcase.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'addonext-lottie-web',
            'file'   => 'assets/js/lib/lottie/lottie.min.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-lottie-animation',
            'file'   => 'assets/js/lottie-animation.js',
            'footer' => true,
            'deps'   => ['addonext-lottie-web'],
        ],
        [
            'handle' => 'addonext-dual-heading-editor',
            'file'   => 'assets/js/dual-heading-editor.js',
            'footer' => true,
            'deps'   => ['jquery', 'elementor-frontend'],
        ],
        [
            'handle' => 'addonext-instagram-feed',
            'file'   => 'assets/js/instagram-feed.js',
            'footer' => true,
            'deps'   => ['swiper'],
        ],
        [
            'handle' => 'addonext-flip-box',
            'file'   => 'assets/js/flip-box.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-code-snippet',
            'file'   => 'assets/js/code-snippet.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-off-canvas-content',
            'file'   => 'assets/js/off-canvas-content.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-image-gallery',
            'file'   => 'assets/js/image-gallery.js',
            'footer' => true,
        ],
        [
            'handle' => 'addonext-animated-text',
            'file'   => 'assets/js/animated-text.js',
            'footer' => true,
            'deps'   => ['jquery'],
        ],
        [
            'handle' => 'addonext-before-after-slider',
            'file'   => 'assets/js/before-after-slider.js',
            'footer' => true,
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('elementor/editor/before_enqueue_scripts', [WooCommerceAssets::class, 'ensure_frontend_scripts_registered'], 5);
        add_action('elementor/frontend/after_register_scripts', [WooCommerceAssets::class, 'ensure_frontend_scripts_registered'], 5);
        add_action('elementor/preview/enqueue_scripts', [WooCommerceAssets::class, 'ensure_frontend_scripts_registered'], 5);
        add_action('wp_enqueue_scripts', [WooCommerceAssets::class, 'ensure_frontend_scripts_registered'], 4);
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
        if (!defined('ADDONEXT_URL') || !defined('ADDONEXT_VERSION')) {
            return;
        }
        foreach ($this->styles as $asset) {
            wp_register_style(
                $asset['handle'],
                ADDONEXT_URL . $asset['file'],
                [],
                ADDONEXT_VERSION
            );
        }
    }

    /**
     * Register the scripts
     */
    public function register_scripts(): void
    {
        if (!defined('ADDONEXT_URL') || !defined('ADDONEXT_VERSION')) {
            return;
        }
        WooCommerceAssets::ensure_frontend_scripts_registered();

        foreach ($this->scripts as $asset) {
            wp_register_script(
                $asset['handle'],
                ADDONEXT_URL . $asset['file'],
                WooCommerceAssets::script_dependencies($asset['deps'] ?? []),
                ADDONEXT_VERSION,
                $asset['footer'] ?? true
            );
        }
    }
}
