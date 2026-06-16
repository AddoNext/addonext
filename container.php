<?php

defined('ABSPATH') or exit;

use function DI\create;
use Veloria\Base\WidgetBase;
use Veloria\Admin\AdminAssets;
use Veloria\Utils\DataProcessor;
use Veloria\Utils\OptionsHelper;
use Veloria\Widgets\Badge\Badge;
use Veloria\Common\WidgetManager;
use Veloria\Admin\AdminController;
use Veloria\Widgets\Banner\Banner;
use Veloria\Widgets\Button\Button;
use Veloria\Widgets\Spacer\Spacer;
use Veloria\Admin\WidgetController;
use Veloria\Utils\AssetPerformance;
use Veloria\Utils\DebugModeManager;
use Veloria\Common\ExtensionManager;
use Veloria\Widgets\Divider\Divider;
use Veloria\Widgets\Dropcap\Dropcap;
use Veloria\Widgets\FlipBox\FlipBox;
use Veloria\Widgets\IconBox\IconBox;
use Veloria\Widgets\Tooltip\Tooltip;
use Veloria\Admin\SettingsController;
use Veloria\Admin\MigrationController;
use Veloria\Widgets\BarChart\BarChart;
use Veloria\Widgets\IconList\IconList;
use Veloria\Widgets\ImageBox\ImageBox;
use Veloria\Widgets\MiniCart\MiniCart;
use Veloria\Widgets\PromoBox\PromoBox;
use Veloria\Widgets\Timeline\Timeline;
use Veloria\Widgets\UspItems\UspItems;
use Veloria\Admin\ExtensionsController;
use Veloria\Widgets\Accordion\Accordion;
use Veloria\Widgets\AddToCart\AddToCart;
use Veloria\Widgets\AuthorBox\AuthorBox;
use Veloria\Widgets\DataTable\DataTable;
use Veloria\Widgets\Highlight\Highlight;
use Veloria\Widgets\LineChart\LineChart;
use Veloria\Widgets\PriceList\PriceList;
use Veloria\Admin\WidgetsAssetController;
use Veloria\Widgets\Blockquote\Blockquote;
use Veloria\Widgets\HeroSlider\HeroSlider;
use Veloria\Widgets\ModalPopup\ModalPopup;
use Veloria\Widgets\StarRating\StarRating;
use Veloria\Widgets\TextEditor\TextEditor;
use Veloria\Widgets\AnimatedSvg\AnimatedSvg;
use Veloria\Widgets\CodeSnippet\CodeSnippet;
use Veloria\Widgets\ContactForm\ContactForm;
use Veloria\Widgets\DualHeading\DualHeading;
use Veloria\Widgets\ProductGrid\ProductGrid;
use Veloria\Widgets\ProductTabs\ProductTabs;
use Veloria\Widgets\ProgressBar\ProgressBar;
use Veloria\Widgets\SocialShare\SocialShare;
use Veloria\Widgets\TeamMembers\TeamMembers;
use Veloria\Widgets\TwitterFeed\TwitterFeed;
use Veloria\Widgets\VideoPlayer\VideoPlayer;
use Veloria\Extensions\Duplicator\Duplicator;
use Veloria\Widgets\AdvancedTabs\AdvancedTabs;
use Veloria\Widgets\AnimatedText\AnimatedText;
use Veloria\Widgets\CallToAction\CallToAction;
use Veloria\Widgets\ImageGallery\ImageGallery;
use Veloria\Widgets\ImageHotspot\ImageHotspot;
use Veloria\Widgets\LogoShowcase\LogoShowcase;
use Veloria\Widgets\PricingTable\PricingTable;
use Veloria\Widgets\Testimonials\Testimonials;
use Veloria\Widgets\VideoGallery\VideoGallery;
use Veloria\Extensions\ScrollToTop\ScrollToTop;
use Veloria\Widgets\ContentToggle\ContentToggle;
use Veloria\Widgets\ImageCarousel\ImageCarousel;
use Veloria\Widgets\SmartPostList\SmartPostList;
use Veloria\Extensions\SmoothScroll\SmoothScroll;
use Veloria\Widgets\NavigationMenu\NavigationMenu;
use Veloria\Extensions\MovingCircles\MovingCircles;
use Veloria\Widgets\AdvancedHeading\AdvancedHeading;
use Veloria\Widgets\LottieAnimation\LottieAnimation;
use Veloria\Widgets\OffCanvasContent\OffCanvasContent;
use Veloria\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Veloria\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Veloria\Widgets\ProductCategories\ProductCategories;
use Veloria\Widgets\ContactForm7Styler\ContactForm7Styler;
use Veloria\Extensions\ReadingProgressBar\ReadingProgressBar;

return [
    AdminController::class        => create(AdminController::class),
    AdminAssets::class            => create(AdminAssets::class),
    WidgetManager::class          => create(WidgetManager::class),
    WidgetController::class       => create(WidgetController::class),
    WidgetsAssetController::class => create(WidgetsAssetController::class),
    SettingsController::class     => create(SettingsController::class),
    OptionsHelper::class          => create(OptionsHelper::class),
    DebugModeManager::class       => create(DebugModeManager::class),
    DataProcessor::class          => create(DataProcessor::class),
    WidgetBase::class             => create(WidgetBase::class),
    Accordion::class              => create(Accordion::class),
    AddToCart::class              => create(AddToCart::class),
    AdvancedAnalytics::class      => create(AdvancedAnalytics::class),
    AdvancedGoogleMap::class      => create(AdvancedGoogleMap::class),
    AdvancedHeading::class        => create(AdvancedHeading::class),
    AdvancedTabs::class           => create(AdvancedTabs::class),
    AnimatedSvg::class            => create(AnimatedSvg::class),
    AnimatedText::class           => create(AnimatedText::class),
    AuthorBox::class              => create(AuthorBox::class),
    Badge::class                  => create(Badge::class),
    Banner::class                 => create(Banner::class),
    BarChart::class               => create(BarChart::class),
    Blockquote::class             => create(Blockquote::class),
    Button::class                 => create(Button::class),
    CallToAction::class           => create(CallToAction::class),
    CodeSnippet::class            => create(CodeSnippet::class),
    ContactForm::class            => create(ContactForm::class),
    ContactForm7Styler::class     => create(ContactForm7Styler::class),
    ContentToggle::class          => create(ContentToggle::class),
    DataTable::class              => create(DataTable::class),
    Divider::class                => create(Divider::class),
    Dropcap::class                => create(Dropcap::class),
    DualHeading::class            => create(DualHeading::class),
    FlipBox::class                => create(FlipBox::class),
    HeroSlider::class             => create(HeroSlider::class),
    Highlight::class              => create(Highlight::class),
    IconBox::class                => create(IconBox::class),
    IconList::class               => create(IconList::class),
    ImageBox::class               => create(ImageBox::class),
    ImageCarousel::class          => create(ImageCarousel::class),
    ImageGallery::class           => create(ImageGallery::class),
    ImageHotspot::class           => create(ImageHotspot::class),
    LineChart::class              => create(LineChart::class),
    LogoShowcase::class           => create(LogoShowcase::class),
    LottieAnimation::class        => create(LottieAnimation::class),
    MiniCart::class               => create(MiniCart::class),
    ModalPopup::class             => create(ModalPopup::class),
    NavigationMenu::class         => create(NavigationMenu::class),
    OffCanvasContent::class       => create(OffCanvasContent::class),
    PriceList::class              => create(PriceList::class),
    PricingTable::class           => create(PricingTable::class),
    ProductCategories::class      => create(ProductCategories::class),
    ProductGrid::class            => create(ProductGrid::class),
    ProductTabs::class            => create(ProductTabs::class),
    ProgressBar::class            => create(ProgressBar::class),
    PromoBox::class               => create(PromoBox::class),
    SmartPostList::class          => create(SmartPostList::class),
    SocialShare::class            => create(SocialShare::class),
    Spacer::class                 => create(Spacer::class),
    StarRating::class             => create(StarRating::class),
    TeamMembers::class            => create(TeamMembers::class),
    Testimonials::class           => create(Testimonials::class),
    UspItems::class               => create(UspItems::class),
    TextEditor::class             => create(TextEditor::class),
    Timeline::class               => create(Timeline::class),
    Tooltip::class                => create(Tooltip::class),
    TwitterFeed::class            => create(TwitterFeed::class),
    VideoGallery::class           => create(VideoGallery::class),
    VideoPlayer::class            => create(VideoPlayer::class),
    SmoothScroll::class           => create(SmoothScroll::class),
    Duplicator::class             => create(Duplicator::class),
    ReadingProgressBar::class     => create(ReadingProgressBar::class),
    ScrollToTop::class            => create(ScrollToTop::class),
    MovingCircles::class          => create(MovingCircles::class),
    ExtensionManager::class       => create(ExtensionManager::class),
    AssetPerformance::class       => create(AssetPerformance::class),
    ExtensionsController::class   => create(ExtensionsController::class),
    MigrationController::class    => create(MigrationController::class)
];
