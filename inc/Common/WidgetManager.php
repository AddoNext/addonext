<?php
namespace Veloria\Common;

defined('ABSPATH') || exit;

use Elementor\Widgets_Manager;
use Veloria\Utils\OptionsHelper;
use Veloria\Utils\LicenseCapabilities;
use Veloria\Widgets\Accordion\Accordion;
use Veloria\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Veloria\Widgets\AdvancedHeading\AdvancedHeading;
use Veloria\Widgets\AdvancedTabs\AdvancedTabs;
use Veloria\Widgets\AnimatedText\AnimatedText;
use Veloria\Widgets\Badge\Badge;
use Veloria\Widgets\Blockquote\Blockquote;
use Veloria\Widgets\Button\Button;
use Veloria\Widgets\CodeSnippet\CodeSnippet;
use Veloria\Widgets\ContentToggle\ContentToggle;
use Veloria\Widgets\Divider\Divider;
use Veloria\Widgets\Dropcap\Dropcap;
use Veloria\Widgets\HeroBanner\HeroBanner;
use Veloria\Widgets\HeroSlider\HeroSlider;
use Veloria\Widgets\Highlight\Highlight;
use Veloria\Widgets\IconBox\IconBox;
use Veloria\Widgets\IconList\IconList;
use Veloria\Widgets\ImageBox\ImageBox;
use Veloria\Widgets\ModalPopup\ModalPopup;
use Veloria\Widgets\NavigationMenu\NavigationMenu;
use Veloria\Widgets\NewsTicker\NewsTicker;
use Veloria\Widgets\OffCanvasContent\OffCanvasContent;
use Veloria\Widgets\SmartPostList\SmartPostList;
use Veloria\Widgets\Spacer\Spacer;
use Veloria\Widgets\TableOfContents\TableOfContents;
use Veloria\Widgets\TextEditor\TextEditor;
use Veloria\Widgets\VideoGallery\VideoGallery;
use Veloria\Widgets\VideoPlayer\VideoPlayer;
use Veloria\Widgets\AnimatedCounter\AnimatedCounter;
use Veloria\Widgets\AnimatedGradient\AnimatedGradient;
use Veloria\Widgets\AnimatedSvg\AnimatedSvg;
use Veloria\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Veloria\Widgets\BentoGrid\BentoGrid;
use Veloria\Widgets\CircularProgress\CircularProgress;
use Veloria\Widgets\CountdownTimer\CountdownTimer;
use Veloria\Widgets\DualHeading\DualHeading;
use Veloria\Widgets\FlipBox\FlipBox;
use Veloria\Widgets\ImageCarousel\ImageCarousel;
use Veloria\Widgets\ImageGallery\ImageGallery;
use Veloria\Widgets\ImageHotspot\ImageHotspot;
use Veloria\Widgets\LottieAnimation\LottieAnimation;
use Veloria\Widgets\ProgressBar\ProgressBar;
use Veloria\Widgets\StarRating\StarRating;
use Veloria\Widgets\Timeline\Timeline;
use Veloria\Widgets\Tooltip\Tooltip;
use Veloria\Widgets\AuthorBox\AuthorBox;
use Veloria\Widgets\Banner\Banner;
use Veloria\Widgets\CallToAction\CallToAction;
use Veloria\Widgets\ComparisonTable\ComparisonTable;
use Veloria\Widgets\ContactForm\ContactForm;
use Veloria\Widgets\ContactForm7Styler\ContactForm7Styler;
use Veloria\Widgets\InstagramFeed\InstagramFeed;
use Veloria\Widgets\LogoShowcase\LogoShowcase;
use Veloria\Widgets\NoticeBoard\NoticeBoard;
use Veloria\Widgets\PriceList\PriceList;
use Veloria\Widgets\PricingTable\PricingTable;
use Veloria\Widgets\PromoBox\PromoBox;
use Veloria\Widgets\SocialShare\SocialShare;
use Veloria\Widgets\SubscribeForm\SubscribeForm;
use Veloria\Widgets\TeamMembers\TeamMembers;
use Veloria\Widgets\Testimonials\Testimonials;
use Veloria\Widgets\TwitterFeed\TwitterFeed;
use Veloria\Widgets\UspItems\UspItems;
use Veloria\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Veloria\Widgets\AreaChart\AreaChart;
use Veloria\Widgets\BarChart\BarChart;
use Veloria\Widgets\DataTable\DataTable;
use Veloria\Widgets\DonutChart\DonutChart;
use Veloria\Widgets\LineChart\LineChart;
use Veloria\Widgets\PieChart\PieChart;
use Veloria\Widgets\RadarChart\RadarChart;
use Veloria\Widgets\AddToCart\AddToCart;
use Veloria\Widgets\MiniCart\MiniCart;
use Veloria\Widgets\ProductCarousel\ProductCarousel;
use Veloria\Widgets\ProductCategories\ProductCategories;
use Veloria\Widgets\ProductFilter\ProductFilter;
use Veloria\Widgets\ProductGrid\ProductGrid;
use Veloria\Widgets\ProductImage\ProductImage;
use Veloria\Widgets\ProductTabs\ProductTabs;
use Veloria\Widgets\WooCheckout\WooCheckout;
use Veloria\Widgets\WooDashboard\WooDashboard;
use Veloria\Widgets\WooThankYou\WooThankYou;

/**
 * WidgetManager class
 *
 * @package Veloria
 * @since 1.0.0
 */
class WidgetManager
{
    /**
     * The list of widgets to register
     *
     * @var array
     */
    protected static $widgets = [
        Accordion::class,
        AdvancedGoogleMap::class,
        AdvancedHeading::class,
        AdvancedTabs::class,
        AnimatedText::class,
        Badge::class,
        Blockquote::class,
        Button::class,
        CodeSnippet::class,
        ContentToggle::class,
        Divider::class,
        Dropcap::class,
        HeroBanner::class,
        HeroSlider::class,
        Highlight::class,
        IconBox::class,
        IconList::class,
        ImageBox::class,
        ModalPopup::class,
        NavigationMenu::class,
        NewsTicker::class,
        OffCanvasContent::class,
        SmartPostList::class,
        Spacer::class,
        TableOfContents::class,
        TextEditor::class,
        VideoGallery::class,
        VideoPlayer::class,
        AnimatedCounter::class,
        AnimatedGradient::class,
        AnimatedSvg::class,
        BeforeAfterSlider::class,
        BentoGrid::class,
        CircularProgress::class,
        CountdownTimer::class,
        DualHeading::class,
        FlipBox::class,
        ImageCarousel::class,
        ImageGallery::class,
        ImageHotspot::class,
        LottieAnimation::class,
        ProgressBar::class,
        StarRating::class,
        Timeline::class,
        Tooltip::class,
        AuthorBox::class,
        Banner::class,
        CallToAction::class,
        ComparisonTable::class,
        ContactForm::class,
        ContactForm7Styler::class,
        InstagramFeed::class,
        LogoShowcase::class,
        NoticeBoard::class,
        PriceList::class,
        PricingTable::class,
        PromoBox::class,
        SocialShare::class,
        SubscribeForm::class,
        TeamMembers::class,
        Testimonials::class,
        UspItems::class,
        TwitterFeed::class,
        AdvancedAnalytics::class,
        AreaChart::class,
        BarChart::class,
        DataTable::class,
        DonutChart::class,
        LineChart::class,
        PieChart::class,
        RadarChart::class,
        AddToCart::class,
        MiniCart::class,
        ProductCarousel::class,
        ProductCategories::class,
        ProductFilter::class,
        ProductGrid::class,
        ProductImage::class,
        ProductTabs::class,
        WooCheckout::class,
        WooDashboard::class,
        WooThankYou::class,
    ];

    /**
     * The list of categories to register
     *
     * @var array
     */
    protected static $categories = [
        'content'   => [
            'title' => 'Content Widgets',
            'icon'  => 'fa-layer-group'
        ],
        'creative'  => [
            'title' => 'Creative Widgets',
            'icon'  => 'fa-palette'
        ],
        'marketing' => [
            'title' => 'Marketing Widgets',
            'icon'  => 'fa-bullhorn'
        ],
        'data'      => [
            'title' => 'Data & Chart Widgets',
            'icon'  => 'fa-chart-pie'
        ],
        'woocommerce' => [
            'title' => 'WooCommerce Widgets',
            'icon'  => 'fa-shopping-cart'
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [ContactForm::class, 'register_ajax_handlers'], 5);
        add_action('init', [SubscribeForm::class, 'register_ajax_handlers'], 5);
        add_action('init', [WooDashboard::class, 'register_ajax_handlers'], 5);
        add_action('init', [MiniCart::class, 'init_fragment_hooks'], 5);
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_badge_assets']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_custom_code_preview']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_extension_visual_previews']);
    }

    /**
     * Enqueue the editor badge assets
     *
     * @return void
     */
    public function enqueue_editor_badge_assets(): void
    {
        wp_enqueue_style(
            'veloria-editor-badge',
            VELORIA_URL . 'assets/css/editor-badge.css',
            [],
            VELORIA_VERSION
        );
    }

    /**
     * Loads editor JS so Custom CSS / Custom JS from extensions update the preview iframe while editing.
     *
     * @return void
     */
    public function enqueue_editor_custom_code_preview(): void
    {
        $active = OptionsHelper::get_option('active_extensions', []);
        $active = is_array($active) ? $active : [];
        $stylesActive = in_array('custom-styles', $active, true);
        $scriptsActive = in_array('custom-scripts', $active, true);

        if (!$stylesActive && !$scriptsActive) {
            return;
        }

        wp_enqueue_script(
            'veloria-editor-custom-code',
            VELORIA_URL . 'assets/js/editor/veloria-custom-code-preview.js',
            ['jquery', 'elementor-editor'],
            VELORIA_VERSION,
            true
        );

        wp_localize_script(
            'veloria-editor-custom-code',
            'veloriaEditorCustomCode',
            [
                'stylesActive'  => $stylesActive,
                'scriptsActive' => $scriptsActive,
            ]
        );
    }

    /**
     * Editor scripts that sync Style-tab overlay data into the preview iframe (particles, moving circles, etc.).
     *
     * @return void
     */
    public function enqueue_editor_extension_visual_previews(): void
    {
        $active = OptionsHelper::get_option('active_extensions', []);
        $active = is_array($active) ? $active : [];

        $scripts = [
            'particle-effects' => VELORIA_URL . 'assets/js/editor/veloria-particle-effects-preview.js',
            'moving-circles'   => VELORIA_URL . 'assets/js/editor/veloria-moving-circles-preview.js',
        ];

        foreach ($scripts as $slug => $src) {
            if (!in_array($slug, $active, true)) {
                continue;
            }

            wp_enqueue_script(
                'veloria-editor-ext-' . str_replace('-', '_', $slug),
                $src,
                ['jquery', 'elementor-editor'],
                VELORIA_VERSION,
                true
            );
        }
    }

    /**
     * Register the 'veloria' Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_category($elements_manager)
    {
        $elements_manager->add_category(
            'veloria',
            [
                'title' => 'Veloria',
                'icon'  => 'fa-layer-group'
            ]
        );
    }

    /**
     * Get the active widgets
     *
     * @return array
     */
    public static function get_active_widgets(): array
    {
        $widgets = OptionsHelper::get_option('active_widgets', []);

        return array_filter(self::$widgets, function ($widget) use ($widgets) {
            $instance = emty_container($widget);
            if (!in_array($instance->get_name(), $widgets, true)) {
                return false;
            }
            if ($instance->get_required_plan() === 'pro' && !LicenseCapabilities::site_has_active_pro_plan()) {
                return false;
            }

            return true;
        });
    }

    /**
     * Get the widgets
     *
     * @return array
     */
    public function get_widgets(): array
    {
        return self::$widgets;
    }

    /**
     * Get the categories
     *
     * @return array
     */
    public function get_categories(): array
    {
        return self::$categories;
    }

    /**
     * Register the widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager
     */
    public function register_widgets(Widgets_Manager $widgets_manager): void
    {
        $widgets = self::get_active_widgets();
        foreach ($widgets as $widget) {
            if (class_exists('\Elementor\Plugin') && method_exists(\Elementor\Plugin::instance()->widgets_manager, 'register_widget_type')) {
                $widgets_manager->register(new $widget());
            }
        }
    }
}
