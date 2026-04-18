<?php
namespace Elementrify\Common;

use Elementor\Widgets_Manager;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Widgets\Accordion\Accordion;
use Elementrify\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Elementrify\Widgets\AdvancedHeading\AdvancedHeading;
use Elementrify\Widgets\AdvancedTabs\AdvancedTabs;
use Elementrify\Widgets\AnimatedText\AnimatedText;
use Elementrify\Widgets\Badge\Badge;
use Elementrify\Widgets\Blockquote\Blockquote;
use Elementrify\Widgets\Button\Button;
use Elementrify\Widgets\CodeSnippet\CodeSnippet;
use Elementrify\Widgets\ContentToggle\ContentToggle;
use Elementrify\Widgets\Divider\Divider;
use Elementrify\Widgets\Dropcap\Dropcap;
use Elementrify\Widgets\HeroBanner\HeroBanner;
use Elementrify\Widgets\HeroSlider\HeroSlider;
use Elementrify\Widgets\Highlight\Highlight;
use Elementrify\Widgets\IconBox\IconBox;
use Elementrify\Widgets\IconList\IconList;
use Elementrify\Widgets\ImageBox\ImageBox;
use Elementrify\Widgets\ModalPopup\ModalPopup;
use Elementrify\Widgets\NavigationMenu\NavigationMenu;
use Elementrify\Widgets\NewsTicker\NewsTicker;
use Elementrify\Widgets\OffCanvasContent\OffCanvasContent;
use Elementrify\Widgets\SmartPostList\SmartPostList;
use Elementrify\Widgets\Spacer\Spacer;
use Elementrify\Widgets\TableOfContents\TableOfContents;
use Elementrify\Widgets\TextEditor\TextEditor;
use Elementrify\Widgets\VideoGallery\VideoGallery;
use Elementrify\Widgets\VideoPlayer\VideoPlayer;
use Elementrify\Widgets\AnimatedCounter\AnimatedCounter;
use Elementrify\Widgets\AnimatedGradient\AnimatedGradient;
use Elementrify\Widgets\AnimatedSvg\AnimatedSvg;
use Elementrify\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Elementrify\Widgets\BentoGrid\BentoGrid;
use Elementrify\Widgets\CircularProgress\CircularProgress;
use Elementrify\Widgets\CountdownTimer\CountdownTimer;
use Elementrify\Widgets\DualHeading\DualHeading;
use Elementrify\Widgets\FlipBox\FlipBox;
use Elementrify\Widgets\ImageCarousel\ImageCarousel;
use Elementrify\Widgets\ImageGallery\ImageGallery;
use Elementrify\Widgets\ImageHotspot\ImageHotspot;
use Elementrify\Widgets\LottieAnimation\LottieAnimation;
use Elementrify\Widgets\ProgressBar\ProgressBar;
use Elementrify\Widgets\StarRating\StarRating;
use Elementrify\Widgets\Timeline\Timeline;
use Elementrify\Widgets\Tooltip\Tooltip;
use Elementrify\Widgets\AuthorBox\AuthorBox;
use Elementrify\Widgets\Banner\Banner;
use Elementrify\Widgets\CallToAction\CallToAction;
use Elementrify\Widgets\ComparisonTable\ComparisonTable;
use Elementrify\Widgets\ContactForm\ContactForm;
use Elementrify\Widgets\ContactForm7Styler\ContactForm7Styler;
use Elementrify\Widgets\InstagramFeed\InstagramFeed;
use Elementrify\Widgets\LogoShowcase\LogoShowcase;
use Elementrify\Widgets\NoticeBoard\NoticeBoard;
use Elementrify\Widgets\PriceList\PriceList;
use Elementrify\Widgets\PricingTable\PricingTable;
use Elementrify\Widgets\PromoBox\PromoBox;
use Elementrify\Widgets\SocialShare\SocialShare;
use Elementrify\Widgets\SubscribeForm\SubscribeForm;
use Elementrify\Widgets\TeamMembers\TeamMembers;
use Elementrify\Widgets\Testimonials\Testimonials;
use Elementrify\Widgets\TwitterFeed\TwitterFeed;
use Elementrify\Widgets\UspItems\UspItems;
use Elementrify\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Elementrify\Widgets\AreaChart\AreaChart;
use Elementrify\Widgets\BarChart\BarChart;
use Elementrify\Widgets\DataTable\DataTable;
use Elementrify\Widgets\DonutChart\DonutChart;
use Elementrify\Widgets\LineChart\LineChart;
use Elementrify\Widgets\PieChart\PieChart;
use Elementrify\Widgets\RadarChart\RadarChart;
use Elementrify\Widgets\AddToCart\AddToCart;
use Elementrify\Widgets\MiniCart\MiniCart;
use Elementrify\Widgets\ProductCarousel\ProductCarousel;
use Elementrify\Widgets\ProductCategories\ProductCategories;
use Elementrify\Widgets\ProductFilter\ProductFilter;
use Elementrify\Widgets\ProductGrid\ProductGrid;
use Elementrify\Widgets\ProductImage\ProductImage;
use Elementrify\Widgets\ProductTabs\ProductTabs;
use Elementrify\Widgets\WooCheckout\WooCheckout;
use Elementrify\Widgets\WooDashboard\WooDashboard;
use Elementrify\Widgets\WooThankYou\WooThankYou;

defined('ABSPATH') or exit;

/**
 * WidgetManager class
 *
 * @package Elementrify
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
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_particle_effects_preview']);
    }

    /**
     * Enqueue the editor badge assets
     *
     * @return void
     */
    public function enqueue_editor_badge_assets(): void
    {
        wp_enqueue_style(
            'elementrify-editor-badge',
            ELEMENTRIFY_URL . 'assets/css/editor-badge.css',
            [],
            ELEMENTRIFY_VERSION
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
            'elementrify-editor-custom-code',
            ELEMENTRIFY_URL . 'assets/js/editor/elementrify-custom-code-preview.js',
            ['jquery', 'elementor-editor'],
            ELEMENTRIFY_VERSION,
            true
        );

        wp_localize_script(
            'elementrify-editor-custom-code',
            'elementrifyEditorCustomCode',
            [
                'stylesActive'  => $stylesActive,
                'scriptsActive' => $scriptsActive,
            ]
        );
    }

    /**
     * Refreshes particle overlays in the preview iframe when Style controls change.
     *
     * @return void
     */
    public function enqueue_editor_particle_effects_preview(): void
    {
        $active = OptionsHelper::get_option('active_extensions', []);
        $active = is_array($active) ? $active : [];

        if (!in_array('particle-effects', $active, true)) {
            return;
        }

        wp_enqueue_script(
            'elementrify-editor-particle-preview',
            ELEMENTRIFY_URL . 'assets/js/editor/elementrify-particle-effects-preview.js',
            ['jquery', 'elementor-editor'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    /**
     * Register the 'elementrify' Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_category($elements_manager)
    {
        $elements_manager->add_category(
            'elementrify',
            [
                'title' => 'Elementrify',
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
            return in_array(emty_container($widget)->get_name(), $widgets);
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
