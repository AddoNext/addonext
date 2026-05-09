<?php
namespace Elementrify\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Utils\AssetPerformance;

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
                    'id'          => 'debug_display',
                    'type'        => 'toggle',
                    'optionKey'   => 'debug_display',
                    'label'       => 'Display Debug Errors',
                    'description' => 'Show PHP and WordPress debug errors on screen',
                    'default'     => false,
                    'dependsOn'   => [
                        'optionKey' => 'debug_mode',
                        'value'     => true
                    ]
                ],
                [
                    'id'          => 'debug_log',
                    'type'        => 'toggle',
                    'optionKey'   => 'debug_log',
                    'label'       => 'Write Debug Log',
                    'description' => 'Write debug messages to wp-content/debug.log',
                    'default'     => true,
                    'dependsOn'   => [
                        'optionKey' => 'debug_mode',
                        'value'     => true
                    ]
                ],
                // [
                //     'id'          => 'load_widgets_globally',
                //     'type'        => 'toggle',
                //     'optionKey'   => 'load_widgets_globally',
                //     'label'       => 'Load Widgets Globally',
                //     'description' => 'Load all widget assets on every page (not recommended)',
                //     'default'     => false
                // ],
                [
                    'id'          => 'beta_features',
                    'type'        => 'toggle',
                    'optionKey'   => 'beta_features',
                    'label'       => 'Enable Beta Features',
                    'description' => 'Get early access to experimental features and widgets',
                    'default'     => true
                ],
                // [
                //     'id'        => 'google_fonts_loading',
                //     'type'      => 'radio',
                //     'optionKey' => 'google_fonts_loading',
                //     'label'     => 'Google Fonts Loading',
                //     'default'   => 'cdn',
                //     'options'   => [
                //         ['value' => 'cdn', 'label' => 'Load from Google CDN (Recommended)'],
                //         ['value' => 'local', 'label' => 'Load Locally (GDPR Compliant)'],
                //         ['value' => 'disable', 'label' => 'Disable Google Fonts']
                //     ]
                // ],
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
                ],
                [
                    'id'          => 'recaptcha_secret_key',
                    'type'        => 'password',
                    'optionKey'   => 'recaptcha_secret_key',
                    'label'       => 'reCAPTCHA Secret Key',
                    'default'     => '',
                    'placeholder' => 'Enter your reCAPTCHA secret key'
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
                ]
                // [
                //     'id'          => 'developer_mode',
                //     'type'        => 'toggle',
                //     'optionKey'   => 'developer_mode',
                //     'label'       => 'Enable Developer Mode',
                //     'description' => 'Unlock advanced features and debugging tools',
                //     'default'     => false
                // ]
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

        register_rest_route('elementrify/v1', '/backups', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_backups_list'],
            'permission_callback' => [$this, 'check_permission']
        ]);
        register_rest_route('elementrify/v1', '/backups/export', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_backups_export'],
            'permission_callback' => [$this, 'check_permission']
        ]);
        register_rest_route('elementrify/v1', '/backups/import', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_backups_import'],
            'permission_callback' => [$this, 'check_permission']
        ]);
        register_rest_route('elementrify/v1', '/backups/download', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_backups_download'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'file' => [
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_file_name'
                ]
            ]
        ]);
        register_rest_route('elementrify/v1', '/backups', [
            'methods'             => 'DELETE',
            'callback'            => [$this, 'rest_backups_delete'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'file' => [
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_file_name'
                ]
            ]
        ]);
    }

    /**
     * Get backup directory
     *
     * @return string
     */
    private static function get_backup_dir(): string
    {
        $dir = WP_CONTENT_DIR . '/elementrify-backups';
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }

        return $dir;
    }

    /**
     * Ensure backup directory exists and is writable
     *
     * @return bool
     */
    private static function ensure_backup_dir(): bool
    {
        $dir = self::get_backup_dir();
        if (!is_dir($dir) || !wp_is_writable($dir)) {
            return false;
        }
        $index = $dir . '/index.php';
        if (!file_exists($index)) {
            file_put_contents($index, '<?php // Silence is golden.');
        }

        return true;
    }

    /**
     * List backup files
     *
     * @return array
     */
    private static function list_backup_files(): array
    {
        $dir  = self::get_backup_dir();
        $list = [];
        if (!is_dir($dir)) {
            return $list;
        }
        $files = glob($dir . '/*.json');
        if (!$files) {
            return $list;
        }
        foreach ($files as $path) {
            $name   = basename($path);
            $list[] = [
                'name'      => $name,
                'date'      => wp_date('M j, Y \a\t g:i A', (int) filemtime($path)),
                'timestamp' => filemtime($path)
            ];
        }
        usort($list, function ($a, $b) {
            return ($b['timestamp'] ?? 0) - ($a['timestamp'] ?? 0);
        });

        return $list;
    }

    /**
     * List backup files
     *
     * @return WP_REST_Response
     */
    public function rest_backups_list(): WP_REST_Response
    {
        $backups = self::list_backup_files();

        return new WP_REST_Response(['success' => true, 'backups' => $backups]);
    }

    /**
     * Export a backup file
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_backups_export(WP_REST_Request $request): WP_REST_Response
    {
        $body           = $request->get_json_params() ?: [];
        $values         = isset($body['values']) && is_array($body['values']) ? $body['values'] : [];
        $save_to_server = !empty($body['save_to_server']);

        if (empty($values)) {
            foreach (self::get_option_keys() as $key) {
                $default = null;
                foreach (self::SECTIONS as $section) {
                    foreach ($section['fields'] as $field) {
                        if (isset($field['optionKey']) && $field['optionKey'] === $key) {
                            $default = $field['default'] ?? null;
                            break 2;
                        }
                    }
                }
                $values[$key] = OptionsHelper::get_option($key, $default);
            }
        }

        $json     = wp_json_encode(['elementrify_backup' => true, 'version' => 1, 'values' => $values], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = 'elementrify-backup-' . gmdate('Y-m-d-H-i-s') . '.json';

        if ($save_to_server && self::ensure_backup_dir()) {
            $dir  = self::get_backup_dir();
            $path = $dir . '/' . $filename;
            file_put_contents($path, $json);
        }

        $backups = self::list_backup_files();

        return new WP_REST_Response([
            'success'     => true,
            'backup_name' => $filename,
            'backups'     => $backups,
            'download'    => $json
        ]);
    }

    /**
     * Import a backup file
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_backups_import(WP_REST_Request $request): WP_REST_Response
    {
        $keys   = self::get_option_keys();
        $values = [];

        if ($request->get_header('content-type') && strpos($request->get_header('content-type'), 'multipart/form-data') !== false) {
            $files = $request->get_file_params();
            if (empty($files['file']) || empty($files['file']['tmp_name'])) {
                return new WP_REST_Response(['success' => false, 'message' => 'No file uploaded'], 400);
            }
            $content = file_get_contents($files['file']['tmp_name']);
            $data    = json_decode($content, true);
            if (!is_array($data) || empty($data['values'])) {
                return new WP_REST_Response(['success' => false, 'message' => 'Invalid backup file'], 400);
            }
            $values = $data['values'];
        } else {
            $body   = $request->get_json_params() ?: [];
            $values = isset($body['values']) && is_array($body['values']) ? $body['values'] : [];
        }

        $clear_import = false;
        foreach (AssetPerformance::OPTION_KEYS as $k) {
            if (isset($values[$k]) && in_array($k, $keys, true)) {
                $clear_import = true;
                break;
            }
        }

        foreach ($values as $key => $value) {
            if (in_array($key, $keys, true)) {
                OptionsHelper::set_option($key, $value);
            }
        }

        if ($clear_import) {
            AssetPerformance::clear_asset_cache_dir();
        }

        return new WP_REST_Response([
            'success' => true,
            'values'  => $values
        ]);
    }

    /**
     * Download a backup file
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_backups_download(WP_REST_Request $request): WP_REST_Response
    {
        $file = $request->get_param('file');
        if (!preg_match('/^elementrify-backup-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}\.json$/', $file)) {
            return new WP_REST_Response(['success' => false, 'message' => 'Invalid file name'], 400);
        }
        $path = self::get_backup_dir() . '/' . $file;
        if (!is_file($path) || !is_readable($path)) {
            return new WP_REST_Response(['success' => false, 'message' => 'File not found'], 404);
        }
        $content = file_get_contents($path);

        return new WP_REST_Response([
            'success' => true,
            'file'    => $file,
            'content' => $content
        ]);
    }

    /**
     * Delete a backup file
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_backups_delete(WP_REST_Request $request): WP_REST_Response
    {
        $file = $request->get_param('file');
        if (!preg_match('/^elementrify-backup-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}\.json$/', $file)) {
            return new WP_REST_Response(['success' => false, 'message' => 'Invalid file name'], 400);
        }
        $path = self::get_backup_dir() . '/' . $file;
        if (is_file($path)) {
            wp_delete_file($path);
        }
        $backups = self::list_backup_files();

        return new WP_REST_Response(['success' => true, 'backups' => $backups]);
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
     * Build settings sections visible for the current extension state.
     *
     * @return array<int, array<string, mixed>>
     */
    private function get_visible_sections(): array
    {
        $sections          = self::SECTIONS;
        $active_extensions = OptionsHelper::get_option('active_extensions', []);
        $active_extensions = is_array($active_extensions) ? $active_extensions : [];

        foreach ($sections as &$section) {
            if (empty($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            $section['fields'] = array_values(
                array_filter(
                    $section['fields'],
                    static function ($field) use ($active_extensions): bool {
                        if (!isset($field['required_extension'])) {
                            return true;
                        }
                        $required = (string) $field['required_extension'];

                        return in_array($required, $active_extensions, true);
                    }
                )
            );
        }
        unset($section);

        return $sections;
    }

    /**
     * Get the settings for the settings page
     *
     * @return WP_REST_Response
     */
    public function rest_get_settings(): WP_REST_Response
    {
        $sections = $this->get_visible_sections();
        $values   = [];
        $keys     = [];

        foreach ($sections as $section) {
            foreach (($section['fields'] ?? []) as $field) {
                if (isset($field['optionKey'])) {
                    $keys[] = (string) $field['optionKey'];
                }
            }
        }

        foreach (array_values(array_unique($keys)) as $key) {
            $default = null;
            foreach ($sections as $section) {
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
            'sections' => $sections,
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
        $before = [];
        foreach (AssetPerformance::OPTION_KEYS as $k) {
            if (in_array($k, $keys, true)) {
                $before[$k] = OptionsHelper::get_option($k, null);
            }
        }

        foreach ($values as $key => $value) {
            if (in_array($key, $keys, true)) {
                OptionsHelper::set_option($key, $value);
            }
        }

        $should_clear = false;
        foreach (AssetPerformance::OPTION_KEYS as $k) {
            if (isset($values[$k], $before[$k]) && $values[$k] !== $before[$k]) {
                $should_clear = true;
                break;
            }
        }
        if ($should_clear) {
            AssetPerformance::clear_asset_cache_dir();
        }

        return new WP_REST_Response([
            'success' => true
        ]);
    }
}
