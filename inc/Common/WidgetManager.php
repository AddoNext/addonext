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
use Elementrify\Widgets\FAQ\FAQ;
use Elementrify\Widgets\Highlight\Highlight;
use Elementrify\Widgets\IconBox\IconBox;
use Elementrify\Widgets\IconList\IconList;
use Elementrify\Widgets\ImageBox\ImageBox;
use Elementrify\Widgets\ListWidget\ListWidget;
use Elementrify\Widgets\ModalPopup\ModalPopup;
use Elementrify\Widgets\OffCanvasContent\OffCanvasContent;
use Elementrify\Widgets\SmartPostList\SmartPostList;
use Elementrify\Widgets\Spacer\Spacer;
use Elementrify\Widgets\TableOfContents\TableOfContents;
use Elementrify\Widgets\TextEditor\TextEditor;
use Elementrify\Widgets\Toggle\Toggle;
use Elementrify\Widgets\VideoPlayer\VideoPlayer;
use Elementrify\Widgets\AnimatedCounter\AnimatedCounter;
use Elementrify\Widgets\AnimatedGradient\AnimatedGradient;
use Elementrify\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Elementrify\Widgets\BentoGrid\BentoGrid;
use Elementrify\Widgets\CircularProgress\CircularProgress;
use Elementrify\Widgets\CountdownTimer\CountdownTimer;
use Elementrify\Widgets\DualHeading\DualHeading;
use Elementrify\Widgets\FlipBox\FlipBox;
use Elementrify\Widgets\HoverBox\HoverBox;
use Elementrify\Widgets\ImageCarousel\ImageCarousel;
use Elementrify\Widgets\ImageGallery\ImageGallery;
use Elementrify\Widgets\ImageHotspot\ImageHotspot;
use Elementrify\Widgets\LottieAnimation\LottieAnimation;
use Elementrify\Widgets\Marquee\Marquee;
use Elementrify\Widgets\Parallax\Parallax;
use Elementrify\Widgets\ParticleEffects\ParticleEffects;
use Elementrify\Widgets\ProgressBar\ProgressBar;
use Elementrify\Widgets\ScrollAnimation\ScrollAnimation;
use Elementrify\Widgets\StarRating\StarRating;
use Elementrify\Widgets\Timeline\Timeline;
use Elementrify\Widgets\TiltEffect\TiltEffect;
use Elementrify\Widgets\Tooltip\Tooltip;
use Elementrify\Widgets\AuthorBox\AuthorBox;
use Elementrify\Widgets\Banner\Banner;
use Elementrify\Widgets\CallToAction\CallToAction;
use Elementrify\Widgets\ComparisonTable\ComparisonTable;
use Elementrify\Widgets\ContactForm\ContactForm;
use Elementrify\Widgets\InstagramFeed\InstagramFeed;
use Elementrify\Widgets\LogoShowcase\LogoShowcase;
use Elementrify\Widgets\Newsletter\Newsletter;
use Elementrify\Widgets\NotificationBar\NotificationBar;
use Elementrify\Widgets\PriceList\PriceList;
use Elementrify\Widgets\PricingTable\PricingTable;
use Elementrify\Widgets\PromoBox\PromoBox;
use Elementrify\Widgets\SocialShare\SocialShare;
use Elementrify\Widgets\SubscribeForm\SubscribeForm;
use Elementrify\Widgets\TeamMembers\TeamMembers;
use Elementrify\Widgets\Testimonials\Testimonials;
use Elementrify\Widgets\TwitterFeed\TwitterFeed;
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
use Elementrify\Widgets\ProductTabs\ProductTabs;

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
        FAQ::class,
        Highlight::class,
        IconBox::class,
        IconList::class,
        ImageBox::class,
        ListWidget::class,
        ModalPopup::class,
        OffCanvasContent::class,
        SmartPostList::class,
        Spacer::class,
        TableOfContents::class,
        TextEditor::class,
        Toggle::class,
        VideoPlayer::class,
        AnimatedCounter::class,
        AnimatedGradient::class,
        BeforeAfterSlider::class,
        BentoGrid::class,
        CircularProgress::class,
        CountdownTimer::class,
        DualHeading::class,
        FlipBox::class,
        HoverBox::class,
        ImageCarousel::class,
        ImageGallery::class,
        ImageHotspot::class,
        LottieAnimation::class,
        Marquee::class,
        Parallax::class,
        ParticleEffects::class,
        ProgressBar::class,
        ScrollAnimation::class,
        StarRating::class,
        Timeline::class,
        TiltEffect::class,
        Tooltip::class,
        AuthorBox::class,
        Banner::class,
        CallToAction::class,
        ComparisonTable::class,
        ContactForm::class,
        InstagramFeed::class,
        LogoShowcase::class,
        Newsletter::class,
        NotificationBar::class,
        PriceList::class,
        PricingTable::class,
        PromoBox::class,
        SocialShare::class,
        SubscribeForm::class,
        TeamMembers::class,
        Testimonials::class,
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
        ProductTabs::class,
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
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_badge_assets']);
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
