<?php

use function DI\create;
use Elementrify\Base\WidgetBase;
use Elementrify\Admin\AdminAssets;
use Elementrify\Utils\DataProcessor;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Widgets\Badge\Badge;
use Elementrify\Common\WidgetManager;
use Elementrify\Admin\AdminController;
use Elementrify\Widgets\Banner\Banner;
use Elementrify\Widgets\Button\Button;
use Elementrify\Widgets\Spacer\Spacer;
use Elementrify\Admin\WidgetController;
use Elementrify\Utils\AssetPerformance;
use Elementrify\Common\ExtensionManager;
use Elementrify\Widgets\Divider\Divider;
use Elementrify\Widgets\Dropcap\Dropcap;
use Elementrify\Widgets\FlipBox\FlipBox;
use Elementrify\Widgets\IconBox\IconBox;
use Elementrify\Widgets\Tooltip\Tooltip;
use Elementrify\Admin\SettingsController;
use Elementrify\Admin\MigrationController;
use Elementrify\Common\GlobalCustomAssets;
use Elementrify\Widgets\BarChart\BarChart;
use Elementrify\Widgets\IconList\IconList;
use Elementrify\Widgets\ImageBox\ImageBox;
use Elementrify\Widgets\MiniCart\MiniCart;
use Elementrify\Widgets\PieChart\PieChart;
use Elementrify\Widgets\PromoBox\PromoBox;
use Elementrify\Widgets\Timeline\Timeline;
use Elementrify\Widgets\UspItems\UspItems;
use Elementrify\Admin\ExtensionsController;
use Elementrify\Widgets\Accordion\Accordion;
use Elementrify\Widgets\AddToCart\AddToCart;
use Elementrify\Widgets\AreaChart\AreaChart;
use Elementrify\Widgets\AuthorBox\AuthorBox;
use Elementrify\Widgets\BentoGrid\BentoGrid;
use Elementrify\Widgets\DataTable\DataTable;
use Elementrify\Widgets\Highlight\Highlight;
use Elementrify\Widgets\LineChart\LineChart;
use Elementrify\Widgets\PriceList\PriceList;
use Elementrify\Admin\WidgetsAssetController;
use Elementrify\Widgets\Blockquote\Blockquote;
use Elementrify\Widgets\DonutChart\DonutChart;
use Elementrify\Widgets\HeroBanner\HeroBanner;
use Elementrify\Widgets\HeroSlider\HeroSlider;
use Elementrify\Widgets\ModalPopup\ModalPopup;
use Elementrify\Widgets\NewsTicker\NewsTicker;
use Elementrify\Widgets\RadarChart\RadarChart;
use Elementrify\Widgets\StarRating\StarRating;
use Elementrify\Widgets\TextEditor\TextEditor;
use Elementrify\Widgets\AnimatedSvg\AnimatedSvg;
use Elementrify\Widgets\CodeSnippet\CodeSnippet;
use Elementrify\Widgets\ContactForm\ContactForm;
use Elementrify\Widgets\DualHeading\DualHeading;
use Elementrify\Widgets\NoticeBoard\NoticeBoard;
use Elementrify\Widgets\ProductGrid\ProductGrid;
use Elementrify\Widgets\ProductTabs\ProductTabs;
use Elementrify\Widgets\ProgressBar\ProgressBar;
use Elementrify\Widgets\SocialShare\SocialShare;
use Elementrify\Widgets\TeamMembers\TeamMembers;
use Elementrify\Widgets\TwitterFeed\TwitterFeed;
use Elementrify\Widgets\VideoPlayer\VideoPlayer;
use Elementrify\Extensions\Duplicator\Duplicator;
use Elementrify\Widgets\AdvancedTabs\AdvancedTabs;
use Elementrify\Widgets\AnimatedText\AnimatedText;
use Elementrify\Widgets\CallToAction\CallToAction;
use Elementrify\Widgets\ImageGallery\ImageGallery;
use Elementrify\Widgets\ImageHotspot\ImageHotspot;
use Elementrify\Widgets\LogoShowcase\LogoShowcase;
use Elementrify\Widgets\PricingTable\PricingTable;
use Elementrify\Widgets\Testimonials\Testimonials;
use Elementrify\Widgets\VideoGallery\VideoGallery;
use Elementrify\Extensions\ScrollToTop\ScrollToTop;
use Elementrify\Widgets\ContentToggle\ContentToggle;
use Elementrify\Widgets\ImageCarousel\ImageCarousel;
use Elementrify\Widgets\ProductFilter\ProductFilter;
use Elementrify\Widgets\SmartPostList\SmartPostList;
use Elementrify\Widgets\SubscribeForm\SubscribeForm;
use Elementrify\Extensions\CustomStyles\CustomStyles;
use Elementrify\Extensions\SmoothScroll\SmoothScroll;
use Elementrify\Widgets\CountdownTimer\CountdownTimer;
use Elementrify\Widgets\NavigationMenu\NavigationMenu;
use Elementrify\Extensions\CustomScripts\CustomScripts;
use Elementrify\Extensions\MovingCircles\MovingCircles;
use Elementrify\Widgets\AdvancedHeading\AdvancedHeading;
use Elementrify\Widgets\AnimatedCounter\AnimatedCounter;
use Elementrify\Widgets\ComparisonTable\ComparisonTable;
use Elementrify\Widgets\LottieAnimation\LottieAnimation;
use Elementrify\Widgets\ProductCarousel\ProductCarousel;
use Elementrify\Widgets\TableOfContents\TableOfContents;
use Elementrify\Widgets\AnimatedGradient\AnimatedGradient;
use Elementrify\Widgets\CircularProgress\CircularProgress;
use Elementrify\Widgets\OffCanvasContent\OffCanvasContent;
use Elementrify\Extensions\ParticleEffects\ParticleEffects;
use Elementrify\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Elementrify\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Elementrify\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Elementrify\Widgets\ProductCategories\ProductCategories;
use Elementrify\Widgets\ContactForm7Styler\ContactForm7Styler;
use Elementrify\Extensions\ReadingProgressBar\ReadingProgressBar;

return [
    AdminController::class        => create(AdminController::class),
    AdminAssets::class            => create(AdminAssets::class),
    WidgetManager::class          => create(WidgetManager::class),
    WidgetController::class       => create(WidgetController::class),
    WidgetsAssetController::class => create(WidgetsAssetController::class),
    SettingsController::class     => create(SettingsController::class),
    OptionsHelper::class          => create(OptionsHelper::class),
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
