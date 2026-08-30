<?php
namespace Addonext\Common;

defined('ABSPATH') || exit;

use Elementor\Widgets_Manager;
use Addonext\Utils\OptionsHelper;
use Addonext\Widgets\Accordion\Accordion;
use Addonext\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Addonext\Widgets\AdvancedHeading\AdvancedHeading;
use Addonext\Widgets\AdvancedTabs\AdvancedTabs;
use Addonext\Widgets\AnimatedText\AnimatedText;
use Addonext\Widgets\Badge\Badge;
use Addonext\Widgets\Blockquote\Blockquote;
use Addonext\Widgets\Button\Button;
use Addonext\Widgets\CodeSnippet\CodeSnippet;
use Addonext\Widgets\ContentToggle\ContentToggle;
use Addonext\Widgets\Divider\Divider;
use Addonext\Widgets\Dropcap\Dropcap;
use Addonext\Widgets\HeroSlider\HeroSlider;
use Addonext\Widgets\Highlight\Highlight;
use Addonext\Widgets\IconBox\IconBox;
use Addonext\Widgets\IconList\IconList;
use Addonext\Widgets\ImageBox\ImageBox;
use Addonext\Widgets\ModalPopup\ModalPopup;
use Addonext\Widgets\NavigationMenu\NavigationMenu;
use Addonext\Widgets\OffCanvasContent\OffCanvasContent;
use Addonext\Widgets\SmartPostList\SmartPostList;
use Addonext\Widgets\Spacer\Spacer;
use Addonext\Widgets\TextEditor\TextEditor;
use Addonext\Widgets\VideoGallery\VideoGallery;
use Addonext\Widgets\VideoPlayer\VideoPlayer;
use Addonext\Widgets\AnimatedSvg\AnimatedSvg;
use Addonext\Widgets\DualHeading\DualHeading;
use Addonext\Widgets\FlipBox\FlipBox;
use Addonext\Widgets\ImageCarousel\ImageCarousel;
use Addonext\Widgets\ImageGallery\ImageGallery;
use Addonext\Widgets\ImageHotspot\ImageHotspot;
use Addonext\Widgets\LottieAnimation\LottieAnimation;
use Addonext\Widgets\ProgressBar\ProgressBar;
use Addonext\Widgets\StarRating\StarRating;
use Addonext\Widgets\Timeline\Timeline;
use Addonext\Widgets\Tooltip\Tooltip;
use Addonext\Widgets\AuthorBox\AuthorBox;
use Addonext\Widgets\Banner\Banner;
use Addonext\Widgets\CallToAction\CallToAction;
use Addonext\Widgets\ContactForm\ContactForm;
use Addonext\Widgets\ContactForm7Styler\ContactForm7Styler;
use Addonext\Widgets\InstagramFeed\InstagramFeed;
use Addonext\Widgets\LogoShowcase\LogoShowcase;
use Addonext\Widgets\PriceList\PriceList;
use Addonext\Widgets\PricingTable\PricingTable;
use Addonext\Widgets\PromoBox\PromoBox;
use Addonext\Widgets\SocialShare\SocialShare;
use Addonext\Widgets\TeamMembers\TeamMembers;
use Addonext\Widgets\Testimonials\Testimonials;
use Addonext\Widgets\TwitterFeed\TwitterFeed;
use Addonext\Widgets\UspItems\UspItems;
use Addonext\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Addonext\Widgets\BarChart\BarChart;
use Addonext\Widgets\DataTable\DataTable;
use Addonext\Widgets\LineChart\LineChart;
use Addonext\Widgets\AddToCart\AddToCart;
use Addonext\Widgets\MiniCart\MiniCart;
use Addonext\Widgets\ProductCategories\ProductCategories;
use Addonext\Widgets\ProductGrid\ProductGrid;
use Addonext\Widgets\ProductImage\ProductImage;
use Addonext\Widgets\ProductTabs\ProductTabs;
use Addonext\Widgets\WooCheckout\WooCheckout;
use Addonext\Widgets\WooThankYou\WooThankYou;

/**
 * WidgetManager class
 *
 * @package AddoNext
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
        HeroSlider::class,
        Highlight::class,
        IconBox::class,
        IconList::class,
        ImageBox::class,
        ModalPopup::class,
        NavigationMenu::class,
        OffCanvasContent::class,
        SmartPostList::class,
        Spacer::class,
        TextEditor::class,
        VideoGallery::class,
        VideoPlayer::class,
        AnimatedSvg::class,
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
        ContactForm::class,
        ContactForm7Styler::class,
        InstagramFeed::class,
        LogoShowcase::class,
        PriceList::class,
        PricingTable::class,
        PromoBox::class,
        SocialShare::class,
        TeamMembers::class,
        Testimonials::class,
        UspItems::class,
        TwitterFeed::class,
        AdvancedAnalytics::class,
        BarChart::class,
        DataTable::class,
        LineChart::class,
        AddToCart::class,
        MiniCart::class,
        ProductCategories::class,
        ProductGrid::class,
        ProductImage::class,
        ProductTabs::class,
        WooCheckout::class,
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
        add_action('init', [MiniCart::class, 'init_fragment_hooks'], 5);
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_badge_assets']);
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
            'addonext-editor-badge',
            ADDONEXT_URL . 'assets/css/editor-badge.css',
            [],
            ADDONEXT_VERSION
        );
    }

    /**
     * Loads editor JS so Custom CSS / Custom JS from extensions update the preview iframe while editing.
     *
     * @return void
     */


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
            'moving-circles' => ADDONEXT_URL . 'assets/js/editor/addonext-moving-circles-preview.js',
        ];

        if (addonext_pro_class_exists('\Addonext\Extensions\ParticleEffects\ParticleEffects')) {
            $scripts['particle-effects'] = ADDONEXT_URL . 'assets/js/editor/addonext-particle-effects-preview.js';
        }

        foreach ($scripts as $slug => $src) {
            if (!in_array($slug, $active, true)) {
                continue;
            }

            wp_enqueue_script(
                'addonext-editor-ext-' . str_replace('-', '_', $slug),
                $src,
                ['jquery', 'elementor-editor'],
                ADDONEXT_VERSION,
                true
            );
        }
    }

    /**
     * Register the 'addonext' Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_category($elements_manager)
    {
        $elements_manager->add_category(
            'addonext',
            [
                'title' => 'AddoNext',
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
        $active_names = OptionsHelper::get_option('active_widgets', []);
        $active_names = is_array($active_names) ? $active_names : [];

        return array_filter(self::get_all_widget_classes(), function ($widget) use ($active_names) {
            $instance = addonext_container($widget);
            if (!in_array($instance->get_name(), $active_names, true)) {
                return false;
            }
            if ($instance->is_preview_only() && self::widget_is_preview_gated($instance)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Whether a preview-only widget should stay disabled on the front end.
     *
     * @param object $instance
     * @return bool
     */
    private static function widget_is_preview_gated(object $instance): bool
    {
        if (!method_exists($instance, 'is_preview_only') || !$instance->is_preview_only()) {
            return false;
        }

        if (addonext_pro_class_exists('\Addonext\Pro\Utils\PreviewAccess')) {
            return \Addonext\Pro\Utils\PreviewAccess::is_preview_mode();
        }

        return true;
    }

    /**
     * Free and conditionally loaded pro widget classes.
     *
     * @return array<int, class-string>
     */
    public static function get_all_widget_classes(): array
    {
        $widgets = self::$widgets;

        if (function_exists('addonext_get_pro_widget_classes')) {
            $widgets = array_merge($widgets, addonext_get_pro_widget_classes());
        }

        return $widgets;
    }

    /**
     * Get the widgets
     *
     * @return array
     */
    public function get_widgets(): array
    {
        return self::get_all_widget_classes();
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
