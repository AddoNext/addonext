<?php
namespace Elementrify\Common;

use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Outputs Custom CSS and Custom JavaScript saved in plugin settings on the front end.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class GlobalCustomAssets
{
    /**
     * @return void
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets'], 30);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_custom_css'], 30);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_custom_js'], 30);
    }

    /**
     * @return void
     */
    public function enqueue_assets(): void
    {
        if (is_admin() && ! $this->is_elementor_preview_request()) {
            return;
        }

        $this->enqueue_custom_css();
        $this->enqueue_custom_js();
    }

    /**
     * @return bool
     */
    private function is_elementor_preview_request(): bool
    {
        if (! function_exists('wp_doing_ajax')) {
            return false;
        }

        if (! wp_doing_ajax()) {
            return false;
        }

        $action = isset($_REQUEST['action']) ? (string) wp_unslash($_REQUEST['action']) : '';
        return $action === 'elementor_ajax';
    }

    /**
     * @return void
     */
    public function enqueue_custom_css(): void
    {
        $custom_css = trim((string) OptionsHelper::get_option('custom_css', ''));

        if ($custom_css === '') {
            return;
        }

        wp_register_style('elementrify-global-custom-css', false, [], ELEMENTRIFY_VERSION);
        wp_enqueue_style('elementrify-global-custom-css');
        wp_add_inline_style('elementrify-global-custom-css', $custom_css);
    }

    /**
     * @return void
     */
    public function enqueue_custom_js(): void
    {
        $custom_js = trim((string) OptionsHelper::get_option('custom_js', ''));

        if ($custom_js === '') {
            return;
        }

        $active = OptionsHelper::get_option('active_extensions', []);
        $active = is_array($active) ? $active : [];
        $in_footer = true;

        if (in_array('custom-scripts', $active, true)) {
            $all    = OptionsHelper::get_option('extension_settings', []);
            $saved  = (is_array($all) && isset($all['custom-scripts']) && is_array($all['custom-scripts'])) ? $all['custom-scripts'] : [];
            $position = isset($saved['load_position']) ? (string) $saved['load_position'] : 'footer';
            $in_footer = $position !== 'header';
        }

        wp_register_script('elementrify-global-custom-js', '', [], ELEMENTRIFY_VERSION, $in_footer);
        wp_enqueue_script('elementrify-global-custom-js');
        wp_add_inline_script('elementrify-global-custom-js', $custom_js);
    }
}
