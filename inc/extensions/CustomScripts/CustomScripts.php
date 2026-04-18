<?php
namespace Elementrify\Extensions\CustomScripts;

use Elementrify\Base\ExtensionBase;
use Elementrify\Common\ExtensionManager;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Outputs Custom JavaScript entered in plugin settings.
 *
 * @package Elementrify
 * @since 1.0.0
 */
class CustomScripts extends ExtensionBase
{
    public function get_id(): string
    {
        return 'custom-scripts';
    }

    public function get_title(): string
    {
        return __('Custom Scripts', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Enable custom JavaScript from Elementrify settings.', 'elementrify');
    }

    public function get_icon(): string
    {
        return 'eicon-editor-code elementrify-icon';
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_fields(): array
    {
        return [
            [
                'id'          => 'load_position',
                'type'        => 'select',
                'label'       => __('Load position', 'elementrify'),
                'description' => __('Choose where custom JavaScript should be printed.', 'elementrify'),
                'default'     => 'footer',
                'options'     => [
                    ['value' => 'header', 'label' => __('Header', 'elementrify')],
                    ['value' => 'footer', 'label' => __('Footer', 'elementrify')],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function sanitize_settings(array $input): array
    {
        $merged = $this->merge_settings_defaults($input);
        $value  = isset($merged['load_position']) ? (string) $merged['load_position'] : 'footer';

        if (!in_array($value, ['header', 'footer'], true)) {
            $value = 'footer';
        }

        return ['load_position' => $value];
    }

    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_inline_custom_js'], 30);
    }

    public function enqueue_inline_custom_js(): void
    {
        if (is_admin()) {
            return;
        }

        $custom_js = (string) OptionsHelper::get_option('custom_js', '');
        $custom_js = trim($custom_js);

        if ($custom_js === '') {
            return;
        }

        $settings = emty_container(ExtensionManager::class)->get_extension_settings_payload($this->get_id());
        $position = isset($settings['load_position']) ? (string) $settings['load_position'] : 'footer';
        $in_footer = $position !== 'header';

        wp_register_script('elementrify-custom-scripts-inline', '', [], ELEMENTRIFY_VERSION, $in_footer);
        wp_enqueue_script('elementrify-custom-scripts-inline');
        wp_add_inline_script('elementrify-custom-scripts-inline', $custom_js);
    }
}
