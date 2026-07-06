<?php
namespace Addonext\Utils;

use DOMNode;
use WP_Error;
use DOMElement;
use DOMDocument;
use WP_REST_Request;
use MatthiasMullie\Minify\JS;
use MatthiasMullie\Minify\CSS;

defined('ABSPATH') || exit;

/**
 * Handles plugin asset minification, cache delivery, and lazy loading.
 */
class AssetPerformance
{
    public const REST_NAMESPACE = 'addonext/v1';

    public const OPTION_KEYS = [
        'minify_assets',
        'lazy_load_assets',
        'asset_caching',
        'cache_lifetime'
    ];

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_filter('script_loader_src', [$this, 'filter_script_src'], 20, 2);
        add_filter('style_loader_src', [$this, 'filter_style_src'], 20, 2);

        if (class_exists(\Elementor\Plugin::class)) {
            add_filter('elementor/frontend/after_get_builder_content', [$this, 'filter_builder_content_lazy'], 20, 2);
        }
    }

    public static function clear_asset_cache_dir(): void
    {
        $uploads = wp_upload_dir();
        if (!empty($uploads['error'])) {
            return;
        }

        $dir = $uploads['basedir'] . '/addonext-assets';
        if (!is_dir($dir)) {
            return;
        }

        $paths = glob($dir . '/*');
        if (!is_array($paths)) {
            return;
        }

        foreach ($paths as $path) {
            if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
                wp_delete_file($path);
            }
        }
    }

    public static function cache_lifetime_to_seconds($value): int
    {
        $map = [
            '1h'  => 3600,
            '6h'  => 21600,
            '24h' => 86400,
            '1w'  => 604800,
            '1m'  => 2592000
        ];

        $lifetime = is_string($value) ? $value : '24h';

        return $map[$lifetime] ?? 86400;
    }

    public function register_routes(): void
    {
        register_rest_route(
            self::REST_NAMESPACE,
            '/asset/(?P<key>[a-f0-9]{64})\.(?P<ext>css|js)',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'rest_serve_asset'],
                'permission_callback' => static function () {
                    return true;
                }
            ]
        );
    }

    /**
     * @return void|WP_Error
     */
    public function rest_serve_asset(WP_REST_Request $request)
    {
        if (!(bool) OptionsHelper::get_option('asset_caching', true)) {
            return new WP_Error('forbidden', '', ['status' => 403]);
        }

        $key  = (string) $request->get_param('key');
        $ext  = (string) $request->get_param('ext');
        $path = $this->cache_file_path($key, $ext);

        if (!is_file($path) || !is_readable($path)) {
            return new WP_Error('not_found', '', ['status' => 404]);
        }

        status_header(200);
        if (!headers_sent()) {
            $max_age      = self::cache_lifetime_to_seconds((string) OptionsHelper::get_option('cache_lifetime', '24h'));
            $content_type = $ext === 'css' ? 'text/css' : 'application/javascript';
            header('Content-Type: ' . $content_type . '; charset=utf-8', true);
            header('Cache-Control: public, max-age=' . (int) $max_age, true);
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $filesystem = $this->get_filesystem();
        if ($filesystem === false) {
            return new WP_Error('filesystem_error', '', ['status' => 500]);
        }

        $content = $filesystem->get_contents($path);
        if ($content === false) {
            return new WP_Error('not_found', '', ['status' => 404]);
        }

        echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Cached asset bytes.
        exit;
    }

    public function filter_script_src($src, $handle)
    {
        if (!is_string($src) || $src === '' || strpos($src, 'data:') === 0) {
            return $src;
        }

        return $this->maybe_rewrite_asset_url($src, 'js');
    }

    public function filter_style_src($src, $handle)
    {
        if (!is_string($src) || $src === '' || strpos($src, 'data:') === 0) {
            return $src;
        }

        return $this->maybe_rewrite_asset_url($src, 'css');
    }

    public function filter_builder_content_lazy($content, $post_id)
    {
        if (!(bool) OptionsHelper::get_option('lazy_load_assets', true) || $this->is_elementor_edit_or_preview()) {
            return $content;
        }

        if (!is_string($content) || $content === '') {
            return $content;
        }

        return $this->add_lazy_to_addonext_images($content);
    }

    private function is_elementor_edit_or_preview(): bool
    {
        if (!class_exists(\Elementor\Plugin::class)) {
            return false;
        }

        $plugin = \Elementor\Plugin::instance();
        if (!empty($plugin->editor) && method_exists($plugin->editor, 'is_edit_mode') && $plugin->editor->is_edit_mode()) {
            return true;
        }

        if (!empty($plugin->preview) && method_exists($plugin->preview, 'is_preview_mode') && $plugin->preview->is_preview_mode()) {
            return true;
        }

        return false;
    }

    private function maybe_rewrite_asset_url($src, string $target_type): string
    {
        if ($this->is_elementor_edit_or_preview()) {
            return $src;
        }

        $minify_enabled = (bool) OptionsHelper::get_option('minify_assets', true);
        $cache_enabled  = (bool) OptionsHelper::get_option('asset_caching', true);
        if (!$minify_enabled && !$cache_enabled) {
            return $src;
        }

        $path = $this->url_to_local_plugin_path($src, $target_type);
        if ($path === null) {
            return $src;
        }

        $key        = $this->build_file_key($path, $target_type);
        $cache_path = $this->cache_file_path($key, $target_type);
        if (!$this->ensure_cache_file($path, $cache_path, $target_type, $minify_enabled)) {
            return $src;
        }

        if ($cache_enabled) {
            return $this->rest_url_for_key($key, $target_type);
        }

        $uploads_url = $this->upload_url_for_key($key, $target_type);
        if ($uploads_url === '') {
            return $src;
        }

        return $uploads_url . '?ver=' . (string) (int) @filemtime($cache_path);
    }

    private function build_file_key(string $absolute_path, string $asset_type): string
    {
        $plugin_dir    = defined('ADDONEXT_DIR') ? (string) constant('ADDONEXT_DIR') : '';
        $version       = defined('ADDONEXT_VERSION') ? (string) constant('ADDONEXT_VERSION') : '1.0.0';
        $relative_path = ltrim(str_replace($plugin_dir, '', $absolute_path), '/\\');
        $modified_at   = (string) (int) @filemtime($absolute_path);

        return hash('sha256', 'addonext|' . $version . '|' . $modified_at . '|' . $asset_type . '|' . $relative_path);
    }

    private function cache_file_path(string $key, string $extension): string
    {
        $cache_dir = $this->cache_dir();
        if ($cache_dir === '') {
            return '';
        }

        return $cache_dir . '/' . $key . '.' . $extension;
    }

    private function cache_dir(): string
    {
        $uploads = wp_upload_dir();
        if (!empty($uploads['error'])) {
            return '';
        }

        $path = $uploads['basedir'] . '/addonext-assets';
        if (!is_dir($path) && !wp_mkdir_p($path)) {
            return '';
        }

        $index = $path . '/index.php';
        if (!is_file($index)) {
            file_put_contents($index, '<?php // Silence is golden.');
        }

        return $path;
    }

    private function ensure_cache_file(string $source_path, string $cache_path, string $asset_type, bool $minify_enabled): bool
    {
        if ($cache_path === '') {
            return false;
        }

        if (is_file($cache_path) && (int) @filesize($cache_path) > 0) {
            return true;
        }

        if (!is_dir(dirname($cache_path)) && !wp_mkdir_p(dirname($cache_path))) {
            return false;
        }

        $tmp = $cache_path . '.tmp.' . getmypid();

        try {
            if ($minify_enabled) {
                if ($asset_type === 'css') {
                    (new CSS($source_path))->minify($tmp);
                } else {
                    (new JS($source_path))->minify($tmp);
                }
            } elseif (!@copy($source_path, $tmp)) {
                return false;
            }
        } catch (\Throwable $throwable) {
            if (is_file($tmp)) {
                @wp_delete_file($tmp);
            }

            return false;
        }

        if (!is_file($tmp) || (int) @filesize($tmp) < 1) {
            if (is_file($tmp)) {
                @wp_delete_file($tmp);
            }

            return false;
        }

        $filesystem = $this->get_filesystem();
        if ($filesystem === false) {
            @wp_delete_file($tmp);

            return false;
        }

        if (!$filesystem->move($tmp, $cache_path, true)) {
            if (!$filesystem->copy($tmp, $cache_path, true)) {
                $filesystem->delete($tmp);

                return false;
            }
            $filesystem->delete($tmp);
        }

        return is_file($cache_path) && (int) @filesize($cache_path) > 0;
    }

    /**
     * @return \WP_Filesystem_Base|false
     */
    private function get_filesystem()
    {
        global $wp_filesystem;

        if (!empty($wp_filesystem)) {
            return $wp_filesystem;
        }

        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if (!WP_Filesystem()) {
            return false;
        }

        return $wp_filesystem;
    }

    private function upload_url_for_key(string $key, string $extension): string
    {
        $uploads = wp_upload_dir();
        if (!empty($uploads['error'])) {
            return '';
        }

        return rtrim((string) $uploads['baseurl'], '/') . '/addonext-assets/' . $key . '.' . $extension;
    }

    private function rest_url_for_key(string $key, string $extension): string
    {
        return (string) get_rest_url(null, self::REST_NAMESPACE . '/asset/' . $key . '.' . $extension);
    }

    private function url_to_local_plugin_path(string $asset_url, string $expected_type): ?string
    {
        if (!defined('ADDONEXT_URL') || !defined('ADDONEXT_DIR')) {
            return null;
        }

        $clean_url = (string) strtok((string) strtok($asset_url, '?'), '#');
        $base_urls = array_values(array_unique([
            untrailingslashit((string) ADDONEXT_URL),
            untrailingslashit((string) str_replace('http://', 'https://', (string) ADDONEXT_URL)),
            untrailingslashit((string) str_replace('https://', 'http://', (string) ADDONEXT_URL))
        ]));

        $relative_path = null;
        foreach ($base_urls as $base_url) {
            $prefix = $base_url . '/';
            if (strpos($clean_url, $prefix) === 0) {
                $relative_path = substr($clean_url, strlen($prefix));
                break;
            }
        }

        if (!is_string($relative_path) || $relative_path === '' || strpos($relative_path, '..') !== false) {
            return null;
        }

        $plugin_dir      = rtrim((string) ADDONEXT_DIR, '/\\') . '/';
        $normalized_path = wp_normalize_path($plugin_dir . $relative_path);
        $real_path       = realpath($normalized_path);
        if ($real_path === false || !is_file($real_path) || !is_readable($real_path)) {
            return null;
        }

        $real_plugin_dir = realpath($plugin_dir);
        if ($real_plugin_dir === false) {
            return null;
        }

        if (strpos(wp_normalize_path($real_path), wp_normalize_path($real_plugin_dir) . '/') !== 0) {
            return null;
        }

        $extension = strtolower((string) pathinfo($real_path, PATHINFO_EXTENSION));
        if ($expected_type === 'js' && in_array($extension, ['js', 'mjs', 'cjs'], true)) {
            return $real_path;
        }

        if ($expected_type === 'css' && $extension === 'css') {
            return $real_path;
        }

        return null;
    }

    private function add_lazy_to_addonext_images(string $html): string
    {
        if (!class_exists(DOMDocument::class)) {
            return $html;
        }

        libxml_use_internal_errors(true);
        $dom          = new DOMDocument('1.0', 'UTF-8');
        $wrapped_html = '<?xml encoding="UTF-8"?><div id="elmntrfy-lw">' . $html . '</div>';

        if ($dom->loadHTML($wrapped_html, LIBXML_HTML_NODEFDTD | LIBXML_COMPACT) === false) {
            libxml_clear_errors();

            return $html;
        }

        $images = $dom->getElementsByTagName('img');
        for ($i = 0; $i < (int) $images->length; $i++) {
            $img = $images->item($i);
            if (!$img instanceof DOMElement) {
                continue;
            }

            if (!$this->node_in_addonext_context($img)) {
                continue;
            }

            $loading = (string) $img->getAttribute('loading');
            if ($loading !== '' && strcasecmp($loading, 'eager') === 0) {
                continue;
            }

            if ($loading === '' || strcasecmp($loading, 'auto') === 0) {
                $img->setAttribute('loading', 'lazy');
            }

            if ((string) $img->getAttribute('decoding') === '') {
                $img->setAttribute('decoding', 'async');
            }
        }

        $wrapper = $dom->getElementById('elmntrfy-lw');
        if (!$wrapper) {
            libxml_clear_errors();

            return $html;
        }

        $output = '';
        foreach ($wrapper->childNodes as $child_node) {
            $output .= (string) $dom->saveHTML($child_node);
        }

        libxml_clear_errors();

        return $output;
    }

    private function node_in_addonext_context(DOMNode $node): bool
    {
        for ($cursor = $node, $depth = 0; $cursor && $depth < 200; $depth++) {
            if ($cursor instanceof DOMElement && $cursor->hasAttribute('data-widget_type')) {
                $widget_type = (string) $cursor->getAttribute('data-widget_type');
                if (strpos($widget_type, 'addonext-') === 0) {
                    return true;
                }
            }

            if (!$cursor->parentNode) {
                break;
            }

            $cursor = $cursor->parentNode;
        }

        return false;
    }
}
