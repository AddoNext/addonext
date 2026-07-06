<?php

defined('ABSPATH') or exit;

use Addonext\Pro\Admin\AccountController;
use Addonext\Widgets\AnimatedCounter\AnimatedCounter;
use Addonext\Widgets\AnimatedGradient\AnimatedGradient;
use Addonext\Widgets\AreaChart\AreaChart;
use Addonext\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Addonext\Widgets\BentoGrid\BentoGrid;
use Addonext\Widgets\CircularProgress\CircularProgress;
use Addonext\Widgets\ComparisonTable\ComparisonTable;
use Addonext\Widgets\CountdownTimer\CountdownTimer;
use Addonext\Widgets\DonutChart\DonutChart;
use Addonext\Widgets\HeroBanner\HeroBanner;
use Addonext\Widgets\NewsTicker\NewsTicker;
use Addonext\Widgets\NoticeBoard\NoticeBoard;
use Addonext\Widgets\PieChart\PieChart;
use Addonext\Widgets\ProductCarousel\ProductCarousel;
use Addonext\Widgets\ProductFilter\ProductFilter;
use Addonext\Widgets\RadarChart\RadarChart;
use Addonext\Widgets\SubscribeForm\SubscribeForm;
use Addonext\Widgets\TableOfContents\TableOfContents;
use Addonext\Widgets\WooDashboard\WooDashboard;
use Addonext\Extensions\ParticleEffects\ParticleEffects;

/**
 * Pro widget classes when premium code is available.
 *
 * @return array<int, class-string>
 */
function addonext_get_pro_widget_classes(): array
{
    if (emt_fs()->can_use_premium_code__premium_only()) {
        if (emt_fs()->is_plan('pro')) {
            return [
                AnimatedCounter::class,
                AnimatedGradient::class,
                AreaChart::class,
                BeforeAfterSlider::class,
                BentoGrid::class,
                CircularProgress::class,
                ComparisonTable::class,
                CountdownTimer::class,
                DonutChart::class,
                HeroBanner::class,
                NewsTicker::class,
                NoticeBoard::class,
                PieChart::class,
                ProductCarousel::class,
                ProductFilter::class,
                RadarChart::class,
                SubscribeForm::class,
                TableOfContents::class,
                WooDashboard::class,
            ];
        }
    }

    return [];
}

/**
 * Pro extension map when premium code is available.
 *
 * @return array<string, class-string>
 */
function addonext_get_pro_extension_map(): array
{
    if (emt_fs()->can_use_premium_code__premium_only()) {
        if (emt_fs()->is_plan('pro')) {
            return [
                'particle-effects' => ParticleEffects::class,
            ];
        }
    }

    return [];
}

/**
 * Bootstrap pro-only hooks and services.
 *
 * @return void
 */
function addonext_bootstrap_pro(): void
{
    if (addonext_has_pro_account_api() && !defined('ADDONEXT_PRO_ACCOUNT_BOOTSTRAPPED')) {
        new AccountController();
        define('ADDONEXT_PRO_ACCOUNT_BOOTSTRAPPED', true);
    }

    if (emt_fs()->can_use_premium_code__premium_only()) {
        if (emt_fs()->is_plan('pro')) {
            add_action('init', [SubscribeForm::class, 'register_ajax_handlers'], 5);
            add_action('init', [WooDashboard::class, 'register_ajax_handlers'], 5);
        }
    }
}
