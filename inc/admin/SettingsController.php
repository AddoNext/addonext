<?php
namespace Elementrify\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Settings Controller for Elementrify
 */
class SettingsController
{
    /**
     * Sections for the settings page
     */
    const SECTIONS = [
        [
            'id'          => 'general',
            'title'       => 'General Settings',
            'description' => 'Configure basic plugin settings and preferences',
            'icon'        => 'cog',
            'fields'      => [
                [
                    'id'          => 'debug_mode',
                    'type'        => 'toggle',
                    'optionKey'   => 'debug_mode',
                    'label'       => 'Enable Debug Mode',
                    'description' => 'Show detailed error messages and logs for troubleshooting',
                    'default'     => false
                ],
                [
                    'id'          => 'load_widgets_globally',
                    'type'        => 'toggle',
                    'optionKey'   => 'load_widgets_globally',
                    'label'       => 'Load Widgets Globally',
                    'description' => 'Load all widget assets on every page (not recommended)',
                    'default'     => false
                ],
                [
                    'id'          => 'beta_features',
                    'type'        => 'toggle',
                    'optionKey'   => 'beta_features',
                    'label'       => 'Enable Beta Features',
                    'description' => 'Get early access to experimental features and widgets',
                    'default'     => true
                ],
                [
                    'id'        => 'google_fonts_loading',
                    'type'      => 'radio',
                    'optionKey' => 'google_fonts_loading',
                    'label'     => 'Google Fonts Loading',
                    'default'   => 'cdn',
                    'options'   => [
                        ['value' => 'cdn', 'label' => 'Load from Google CDN (Recommended)'],
                        ['value' => 'local', 'label' => 'Load Locally (GDPR Compliant)'],
                        ['value' => 'disable', 'label' => 'Disable Google Fonts']
                    ]
                ],
                [
                    'id'          => 'css_class_prefix',
                    'type'        => 'text',
                    'optionKey'   => 'css_class_prefix',
                    'label'       => 'Custom CSS Class Prefix',
                    'description' => 'Prefix for all widget CSS classes to avoid conflicts',
                    'default'     => 'elementrify-',
                    'placeholder' => 'elementrify-'
                ]
            ]
        ],
        [
            'id'          => 'performance',
            'title'       => 'Performance Optimization',
            'description' => 'Optimize loading speed and resource management',
            'icon'        => 'bolt',
            'fields'      => [
                [
                    'id'          => 'minify_assets',
                    'type'        => 'toggle',
                    'optionKey'   => 'minify_assets',
                    'label'       => 'Minify CSS & JavaScript',
                    'description' => 'Reduce file sizes for faster loading times',
                    'default'     => true
                ],
                [
                    'id'          => 'lazy_load_assets',
                    'type'        => 'toggle',
                    'optionKey'   => 'lazy_load_assets',
                    'label'       => 'Lazy Load Assets',
                    'description' => 'Load widget assets only when needed on the page',
                    'default'     => true
                ],
                [
                    'id'          => 'asset_caching',
                    'type'        => 'toggle',
                    'optionKey'   => 'asset_caching',
                    'label'       => 'Enable Asset Caching',
                    'description' => 'Cache generated CSS and JS files for better performance',
                    'default'     => true
                ],
                [
                    'id'        => 'cache_lifetime',
                    'type'      => 'select',
                    'optionKey' => 'cache_lifetime',
                    'label'     => 'Cache Lifetime',
                    'default'   => '24h',
                    'options'   => [
                        ['value' => '1h', 'label' => '1 Hour'],
                        ['value' => '6h', 'label' => '6 Hours'],
                        ['value' => '24h', 'label' => '24 Hours'],
                        ['value' => '1w', 'label' => '1 Week'],
                        ['value' => '1m', 'label' => '1 Month']
                    ]
                ]
            ]
        ],
        [
            'id'          => 'integrations',
            'title'       => 'Third-Party Integrations',
            'description' => 'Connect with external services and APIs',
            'icon'        => 'puzzle-piece',
            'fields'      => []
        ],
        [
            'id'          => 'api',
            'title'       => 'API Keys & Credentials',
            'description' => 'Manage API keys for various services',
            'icon'        => 'key',
            'fields'      => [
                [
                    'id'        => 'google_maps_api_key',
                    'type'      => 'password',
                    'optionKey' => 'google_maps_api_key',
                    'label'     => 'Google Maps API Key',
                    'default'   => ''
                ],
                [
                    'id'        => 'mailchimp_api_key',
                    'type'      => 'password',
                    'optionKey' => 'mailchimp_api_key',
                    'label'     => 'Mailchimp API Key',
                    'default'   => ''
                ],
                [
                    'id'        => 'instagram_access_token',
                    'type'      => 'password',
                    'optionKey' => 'instagram_access_token',
                    'label'     => 'Instagram Access Token',
                    'default'   => ''
                ],
                [
                    'id'          => 'recaptcha_site_key',
                    'type'        => 'text',
                    'optionKey'   => 'recaptcha_site_key',
                    'label'       => 'reCAPTCHA Site Key',
                    'default'     => '',
                    'placeholder' => 'Enter your reCAPTCHA site key'
                ]
            ]
        ],
        [
            'id'          => 'advanced',
            'title'       => 'Advanced Settings',
            'description' => 'For advanced users and developers only',
            'icon'        => 'code',
            'fields'      => [
                [
                    'id'          => 'custom_css',
                    'type'        => 'textarea',
                    'optionKey'   => 'custom_css',
                    'label'       => 'Custom CSS',
                    'default'     => '',
                    'placeholder' => '/* Add your custom CSS here */'
                ],
                [
                    'id'          => 'custom_js',
                    'type'        => 'textarea',
                    'optionKey'   => 'custom_js',
                    'label'       => 'Custom JavaScript',
                    'default'     => '',
                    'placeholder' => '// Add your custom JavaScript here'
                ],
                [
                    'id'          => 'developer_mode',
                    'type'        => 'toggle',
                    'optionKey'   => 'developer_mode',
                    'label'       => 'Enable Developer Mode',
                    'description' => 'Unlock advanced features and debugging tools',
                    'default'     => false
                ]
            ]
        ],
        [
            'id'          => 'backup',
            'title'       => 'Backup & Restore',
            'description' => 'Export and import your settings',
            'icon'        => 'database',
            'fields'      => []
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * Register REST endpoints for the settings page
     *
     * @return void
     */
    public function register_endpoints(): void
    {
        register_rest_route('elementrify/v1', '/settings', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_get_settings'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('elementrify/v1', '/settings', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_save_settings'],
            'permission_callback' => [$this, 'check_permission']
        ]);
    }

    /**
     * Check if the user has permission to access the settings page
     *
     * @return bool
     */
    public function check_permission(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * Get the option keys for the settings page
     *
     * @return array
     */
    private static function get_option_keys(): array
    {
        $keys = [];
        foreach (self::SECTIONS as $section) {
            foreach ($section['fields'] as $field) {
                $keys[] = $field['optionKey'];
            }
        }

        return array_unique($keys);
    }

    /**
     * Get the settings for the settings page
     *
     * @return WP_REST_Response
     */
    public function rest_get_settings(): WP_REST_Response
    {
        $values = [];
        foreach (self::get_option_keys() as $key) {
            $default = null;
            foreach (self::SECTIONS as $section) {
                foreach ($section['fields'] as $field) {
                    if ($field['optionKey'] === $key) {
                        $default = $field['default'] ?? null;
                        break 2;
                    }
                }
            }
            $values[$key] = OptionsHelper::get_option($key, $default);
        }

        return new WP_REST_Response([
            'success'  => true,
            'sections' => self::SECTIONS,
            'values'   => $values
        ]);
    }

    /**
     * Save the settings for the settings page
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_save_settings(WP_REST_Request $request): WP_REST_Response
    {
        $body   = $request->get_json_params();
        $values = isset($body['values']) && is_array($body['values']) ? $body['values'] : [];
        $keys   = self::get_option_keys();

        foreach ($values as $key => $value) {
            if (in_array($key, $keys, true)) {
                OptionsHelper::set_option($key, $value);
            }
        }

        return new WP_REST_Response([
            'success' => true
        ]);
    }
}
