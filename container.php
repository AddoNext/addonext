<?php

defined('ABSPATH') or exit;

use function DI\create;
use Addonext\Base\WidgetBase;
use Addonext\Admin\AdminAssets;
use Addonext\Utils\DataProcessor;
use Addonext\Utils\OptionsHelper;
use Addonext\Widgets\Badge\Badge;
use Addonext\Common\WidgetManager;
use Addonext\Admin\AdminController;
use Addonext\Widgets\Banner\Banner;
use Addonext\Widgets\Button\Button;
use Addonext\Widgets\Spacer\Spacer;
use Addonext\Admin\WidgetController;
use Addonext\Utils\AssetPerformance;
use Addonext\Common\ExtensionManager;
use Addonext\Widgets\Divider\Divider;
use Addonext\Widgets\Dropcap\Dropcap;
use Addonext\Widgets\FlipBox\FlipBox;
use Addonext\Widgets\IconBox\IconBox;
use Addonext\Widgets\Tooltip\Tooltip;
use Addonext\Admin\SettingsController;
use Addonext\Admin\MigrationController;
use Addonext\Widgets\BarChart\BarChart;
use Addonext\Widgets\IconList\IconList;
use Addonext\Widgets\ImageBox\ImageBox;
use Addonext\Widgets\MiniCart\MiniCart;
use Addonext\Widgets\PromoBox\PromoBox;
use Addonext\Widgets\Timeline\Timeline;
use Addonext\Widgets\UspItems\UspItems;
use Addonext\Admin\ExtensionsController;
use Addonext\Widgets\Accordion\Accordion;
use Addonext\Widgets\AddToCart\AddToCart;
use Addonext\Widgets\AuthorBox\AuthorBox;
use Addonext\Widgets\DataTable\DataTable;
use Addonext\Widgets\Highlight\Highlight;
use Addonext\Widgets\LineChart\LineChart;
use Addonext\Widgets\PriceList\PriceList;
use Addonext\Admin\WidgetsAssetController;
use Addonext\Widgets\Blockquote\Blockquote;
use Addonext\Widgets\HeroSlider\HeroSlider;
use Addonext\Widgets\ModalPopup\ModalPopup;
use Addonext\Widgets\StarRating\StarRating;
use Addonext\Widgets\TextEditor\TextEditor;
use Addonext\Widgets\AnimatedSvg\AnimatedSvg;
use Addonext\Widgets\CodeSnippet\CodeSnippet;
use Addonext\Widgets\ContactForm\ContactForm;
use Addonext\Widgets\DualHeading\DualHeading;
use Addonext\Widgets\ProductGrid\ProductGrid;
use Addonext\Widgets\ProductTabs\ProductTabs;
use Addonext\Widgets\ProgressBar\ProgressBar;
use Addonext\Widgets\SocialShare\SocialShare;
use Addonext\Widgets\TeamMembers\TeamMembers;
use Addonext\Widgets\TwitterFeed\TwitterFeed;
use Addonext\Widgets\VideoPlayer\VideoPlayer;
use Addonext\Extensions\Duplicator\Duplicator;
use Addonext\Widgets\AdvancedTabs\AdvancedTabs;
use Addonext\Widgets\AnimatedText\AnimatedText;
use Addonext\Widgets\CallToAction\CallToAction;
use Addonext\Widgets\ImageGallery\ImageGallery;
use Addonext\Widgets\ImageHotspot\ImageHotspot;
use Addonext\Widgets\LogoShowcase\LogoShowcase;
use Addonext\Widgets\PricingTable\PricingTable;
use Addonext\Widgets\Testimonials\Testimonials;
use Addonext\Widgets\VideoGallery\VideoGallery;
use Addonext\Extensions\ScrollToTop\ScrollToTop;
use Addonext\Widgets\ContentToggle\ContentToggle;
use Addonext\Widgets\ImageCarousel\ImageCarousel;
use Addonext\Widgets\SmartPostList\SmartPostList;
use Addonext\Extensions\SmoothScroll\SmoothScroll;
use Addonext\Widgets\NavigationMenu\NavigationMenu;
use Addonext\Extensions\MovingCircles\MovingCircles;
use Addonext\Widgets\AdvancedHeading\AdvancedHeading;
use Addonext\Widgets\LottieAnimation\LottieAnimation;
use Addonext\Widgets\OffCanvasContent\OffCanvasContent;
use Addonext\Widgets\AdvancedAnalytics\AdvancedAnalytics;
use Addonext\Widgets\AdvancedGoogleMap\AdvancedGoogleMap;
use Addonext\Widgets\ProductCategories\ProductCategories;
use Addonext\Widgets\ContactForm7Styler\ContactForm7Styler;
use Addonext\Extensions\ReadingProgressBar\ReadingProgressBar;

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
