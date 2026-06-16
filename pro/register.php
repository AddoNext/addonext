<?php

defined('ABSPATH') or exit;

use Veloria\Pro\Admin\AccountController;
use Veloria\Widgets\AnimatedCounter\AnimatedCounter;
use Veloria\Widgets\AnimatedGradient\AnimatedGradient;
use Veloria\Widgets\AreaChart\AreaChart;
use Veloria\Widgets\BeforeAfterSlider\BeforeAfterSlider;
use Veloria\Widgets\BentoGrid\BentoGrid;
use Veloria\Widgets\CircularProgress\CircularProgress;
use Veloria\Widgets\ComparisonTable\ComparisonTable;
use Veloria\Widgets\CountdownTimer\CountdownTimer;
use Veloria\Widgets\DonutChart\DonutChart;
use Veloria\Widgets\HeroBanner\HeroBanner;
use Veloria\Widgets\NewsTicker\NewsTicker;
use Veloria\Widgets\NoticeBoard\NoticeBoard;
use Veloria\Widgets\PieChart\PieChart;
use Veloria\Widgets\ProductCarousel\ProductCarousel;
use Veloria\Widgets\ProductFilter\ProductFilter;
use Veloria\Widgets\RadarChart\RadarChart;
use Veloria\Widgets\SubscribeForm\SubscribeForm;
use Veloria\Widgets\TableOfContents\TableOfContents;
use Veloria\Widgets\WooDashboard\WooDashboard;
use Veloria\Extensions\ParticleEffects\ParticleEffects;

/**
 * Pro widget classes when premium code is available.
 *
 * @return array<int, class-string>
 */
function veloria_get_pro_widget_classes(): array
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
function veloria_get_pro_extension_map(): array
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
function veloria_bootstrap_pro(): void
{
    if (veloria_has_pro_account_api() && !defined('VELORIA_PRO_ACCOUNT_BOOTSTRAPPED')) {
        new AccountController();
        define('VELORIA_PRO_ACCOUNT_BOOTSTRAPPED', true);
    }

    if (emt_fs()->can_use_premium_code__premium_only()) {
        if (emt_fs()->is_plan('pro')) {
            add_action('init', [SubscribeForm::class, 'register_ajax_handlers'], 5);
            add_action('init', [WooDashboard::class, 'register_ajax_handlers'], 5);
        }
    }
}
