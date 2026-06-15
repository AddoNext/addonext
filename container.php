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
use Veloria\Common\GlobalCustomAssets;
use Veloria\Widgets\BarChart\BarChart;
use Veloria\Widgets\IconList\IconList;
use Veloria\Widgets\ImageBox\ImageBox;
use Veloria\Widgets\MiniCart\MiniCart;
use Veloria\Widgets\PieChart\PieChart;
use Veloria\Widgets\PromoBox\PromoBox;
use Veloria\Widgets\Timeline\Timeline;
use Veloria\Widgets\UspItems\UspItems;
use Veloria\Admin\ExtensionsController;
use Veloria\Widgets\Accordion\Accordion;
use Veloria\Widgets\AddToCart\AddToCart;
use Veloria\Widgets\AreaChart\AreaChart;
use Veloria\Widgets\AuthorBox\AuthorBox;
use Veloria\Widgets\BentoGrid\BentoGrid;
use Veloria\Widgets\DataTable\DataTable;
use Veloria\Widgets\Highlight\Highlight;
use Veloria\Widgets\LineChart\LineChart;
use Veloria\Widgets\PriceList\PriceList;
use Veloria\Admin\WidgetsAssetController;
use Veloria\Widgets\Blockquote\Blockquote;
use Veloria\Widgets\DonutChart\DonutChart;
use Veloria\Widgets\HeroBanner\HeroBanner;
use Veloria\Widgets\HeroSlider\HeroSlider;
use Veloria\Widgets\ModalPopup\ModalPopup;
use Veloria\Widgets\NewsTicker\NewsTicker;
use Veloria\Widgets\RadarChart\RadarChart;
use Veloria\Widgets\StarRating\StarRating;
use Veloria\Widgets\TextEditor\TextEditor;
use Veloria\Widgets\AnimatedSvg\AnimatedSvg;
use Veloria\Widgets\CodeSnippet\CodeSnippet;
use Veloria\Widgets\ContactForm\ContactForm;
use Veloria\Widgets\DualHeading\DualHeading;
use Veloria\Widgets\NoticeBoard\NoticeBoard;
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
use Veloria\Widgets\ProductFilter\ProductFilter;
use Veloria\Widgets\SmartPostList\SmartPostList;
use Veloria\Widgets\SubscribeForm\SubscribeForm;
use Veloria\Extensions\CustomStyles\CustomStyles;
use Veloria\Extensions\SmoothScroll\SmoothScroll;
use Veloria\Widgets\CountdownTimer\CountdownTimer;
use Veloria\Widgets\NavigationMenu\NavigationMenu;
use Veloria\Extensions\CustomScripts\CustomScripts;
use Veloria\Extensions\MovingCircles\MovingCircles;
use Veloria\Widgets\AdvancedHeading\AdvancedHeading;
use Veloria\Widgets\AnimatedCounter\AnimatedCounter;
use Veloria\Widgets\ComparisonTable\ComparisonTable;
use Veloria\Widgets\LottieAnimation\LottieAnimation;
use Veloria\Widgets\ProductCarousel\ProductCarousel;
use Veloria\Widgets\TableOfContents\TableOfContents;
use Veloria\Widgets\AnimatedGradient\AnimatedGradient;
use Veloria\Widgets\CircularProgress\CircularProgress;
use Veloria\Widgets\OffCanvasContent\OffCanvasContent;
use Veloria\Extensions\ParticleEffects\ParticleEffects;
use Veloria\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Veloria\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Veloria\Widgets\BeforeAfterSlider\BeforeAfterSlider;
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
    AnimatedCounter::class        => create(AnimatedCounter::class),
    AnimatedGradient::class       => create(AnimatedGradient::class),
    AnimatedSvg::class            => create(AnimatedSvg::class),
    AnimatedText::class           => create(AnimatedText::class),
    AreaChart::class              => create(AreaChart::class),
    AuthorBox::class              => create(AuthorBox::class),
    Badge::class                  => create(Badge::class),
    Banner::class                 => create(Banner::class),
    BarChart::class               => create(BarChart::class),
    BeforeAfterSlider::class      => create(BeforeAfterSlider::class),
    BentoGrid::class              => create(BentoGrid::class),
    Blockquote::class             => create(Blockquote::class),
    Button::class                 => create(Button::class),
    CallToAction::class           => create(CallToAction::class),
    CircularProgress::class       => create(CircularProgress::class),
    CodeSnippet::class            => create(CodeSnippet::class),
    ComparisonTable::class        => create(ComparisonTable::class),
    ContactForm::class            => create(ContactForm::class),
    ContactForm7Styler::class     => create(ContactForm7Styler::class),
    ContentToggle::class          => create(ContentToggle::class),
    CountdownTimer::class         => create(CountdownTimer::class),
    DataTable::class              => create(DataTable::class),
    Divider::class                => create(Divider::class),
    DonutChart::class             => create(DonutChart::class),
    Dropcap::class                => create(Dropcap::class),
    DualHeading::class            => create(DualHeading::class),
    FlipBox::class                => create(FlipBox::class),
    HeroBanner::class             => create(HeroBanner::class),
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
    NewsTicker::class             => create(NewsTicker::class),
    NoticeBoard::class            => create(NoticeBoard::class),
    OffCanvasContent::class       => create(OffCanvasContent::class),
    PieChart::class               => create(PieChart::class),
    PriceList::class              => create(PriceList::class),
    PricingTable::class           => create(PricingTable::class),
    ProductCarousel::class        => create(ProductCarousel::class),
    ProductCategories::class      => create(ProductCategories::class),
    ProductFilter::class          => create(ProductFilter::class),
    ProductGrid::class            => create(ProductGrid::class),
    ProductTabs::class            => create(ProductTabs::class),
    ProgressBar::class            => create(ProgressBar::class),
    PromoBox::class               => create(PromoBox::class),
    RadarChart::class             => create(RadarChart::class),
    SmartPostList::class          => create(SmartPostList::class),
    SocialShare::class            => create(SocialShare::class),
    Spacer::class                 => create(Spacer::class),
    StarRating::class             => create(StarRating::class),
    SubscribeForm::class          => create(SubscribeForm::class),
    TableOfContents::class        => create(TableOfContents::class),
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
    CustomScripts::class          => create(CustomScripts::class),
    CustomStyles::class           => create(CustomStyles::class),
    ReadingProgressBar::class     => create(ReadingProgressBar::class),
    ScrollToTop::class            => create(ScrollToTop::class),
    ParticleEffects::class        => create(ParticleEffects::class),
    MovingCircles::class          => create(MovingCircles::class),
    ExtensionManager::class       => create(ExtensionManager::class),
    GlobalCustomAssets::class     => create(GlobalCustomAssets::class),
    AssetPerformance::class       => create(AssetPerformance::class),
    ExtensionsController::class   => create(ExtensionsController::class),
    MigrationController::class    => create(MigrationController::class)
];
