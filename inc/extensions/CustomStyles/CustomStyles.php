<?php
namespace Elementrify\Extensions\CustomStyles;

use Elementrify\Base\ExtensionBase;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Outputs Custom CSS entered in plugin settings.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class CustomStyles extends ExtensionBase
{
    public function get_id(): string
    {
        return 'custom-styles';
    }

    public function get_title(): string
    {
        return __('Custom Styles', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Enable custom CSS from Elementrify settings.', 'elementrify');
    }

    public function get_icon(): string
    {
        return 'eicon-code elementrify-icon';
    }

    public function get_category(): string
    {
        return 'content';
    }

    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    public function get_date_updated(): string
    {
        return '2026-04-18';
    }

    public function should_enqueue_frontend_assets(): bool
    {
        return false;
    }

    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_inline_custom_css'], 30);
    }

    public function enqueue_inline_custom_css(): void
    {
        if (is_admin()) {
            return;
        }

        $custom_css = (string) OptionsHelper::get_option('custom_css', '');
        $custom_css = trim($custom_css);

        if ($custom_css === '') {
            return;
        }

        wp_register_style('elementrify-custom-styles-inline', false, [], ELEMENTRIFY_VERSION);
        wp_enqueue_style('elementrify-custom-styles-inline');
        wp_add_inline_style('elementrify-custom-styles-inline', $custom_css);
    }
}
