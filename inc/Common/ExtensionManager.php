<?php
namespace Veloria\Common;

use Veloria\Base\ExtensionBase;
use Veloria\Utils\OptionsHelper;
use Veloria\Extensions\Duplicator\Duplicator;
use Veloria\Extensions\ReadingProgressBar\ReadingProgressBar;
use Veloria\Extensions\MovingCircles\MovingCircles;
use Veloria\Extensions\ScrollToTop\ScrollToTop;
use Veloria\Extensions\SmoothScroll\SmoothScroll;

defined('ABSPATH') or exit;

/**
 * Veloria Extension Manager class
 *
 * @package Veloria
 * @since 1.0.0
 */
class ExtensionManager
{
    /**
     * Extension id => class name (avoid instantiating inactive extensions to read ids).
     *
     * @var array<string, class-string<ExtensionBase>>
     */
    protected static $extensions = [
        'smooth-scroll'  => SmoothScroll::class,
        'duplicator'     => Duplicator::class,
        'reading-progress-bar' => ReadingProgressBar::class,
        'scroll-to-top'  => ScrollToTop::class,
        'moving-circles'   => MovingCircles::class,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'bootstrap_active_extension_instances'], 25);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_capable_extension_assets'], 5);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_capable_extension_assets'], 5);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_preview_capable_extensions_after_elementor'], 22);
    }

    /**
     * Loads extension assets in the preview iframe when the active class defines enqueue_for_elementor_preview().
     *
     * @return void
     */
    public function enqueue_preview_capable_extension_assets(): void
    {
        foreach ($this->get_active_extensions() as $class) {
            $instance = emty_container($class);

            if (!method_exists($instance, 'enqueue_for_elementor_preview')) {
                continue;
            }

            $instance->enqueue_for_elementor_preview();
        }
    }

    /**
     * Runs after Elementor preview scripts so optional extension scripts still enqueue.
     *
     * @return void
     */
    public function enqueue_preview_capable_extensions_after_elementor(): void
    {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }

        $preview = \Elementor\Plugin::$instance->preview;

        if (!is_object($preview) || !method_exists($preview, 'is_preview_mode') || !$preview->is_preview_mode()) {
            return;
        }

        foreach ($this->get_active_extensions() as $class) {
            $instance = emty_container($class);

            if (!method_exists($instance, 'enqueue_for_elementor_preview')) {
                continue;
            }

            $instance->enqueue_for_elementor_preview();
        }
    }

    /**
     * Instantiate enabled extensions on the front so they can register assets.
     *
     * @return void
     */
    public function bootstrap_active_extension_instances(): void
    {
        foreach ($this->get_active_extensions() as $class) {
            $instance = emty_container($class);
            $instance->register_hooks();

            if (!$instance->should_enqueue_frontend_assets()) {
                continue;
            }

            if (is_admin()) {
                continue;
            }

            add_action('wp_enqueue_scripts', [$instance, 'enqueue_assets'], 10);
        }
    }

    /**
     * Resolve extension class name by id slug
     *
     * @param string $id
     * @return class-string<ExtensionBase>|null
     */
    public function get_extension_class_for_id(string $id): ?string
    {
        return $this->get_extension_registry()[$id] ?? null;
    }

    /**
     * Stored settings for one extension merged with defaults
     *
     * @param string $id
     * @return array<string, mixed>
     */
    public function get_extension_settings_payload(string $id): array
    {
        $class = $this->get_extension_class_for_id($id);

        if ($class === null) {
            return [];
        }

        $instance = emty_container($class);
        $saved    = $this->get_raw_extension_settings($id);

        return $instance->merge_settings_defaults($saved);
    }

    /**
     * @param string $id
     * @return array<string, mixed>
     */
    private function get_raw_extension_settings(string $id): array
    {
        $all = OptionsHelper::get_option('extension_settings', []);

        if (!is_array($all) || !isset($all[$id]) || !is_array($all[$id])) {
            return [];
        }

        return $all[$id];
    }

    /**
     * Persist sanitized settings for an extension
     *
     * @param string $id
     * @param array<string, mixed> $settings
     * @return void
     */
    public function save_extension_settings(string $id, array $settings): void
    {
        $all       = OptionsHelper::get_option('extension_settings', []);
        $all       = is_array($all) ? $all : [];
        $all[$id] = $settings;
        OptionsHelper::set_option('extension_settings', $all);
    }

    /**
     * Get all available extension class names
     *
     * @return array<int, class-string<ExtensionBase>>
     */
    public function get_extensions(): array
    {
        $extensions = self::$extensions;

        if (function_exists('veloria_get_pro_extension_map')) {
            $extensions = array_merge($extensions, veloria_get_pro_extension_map());
        }

        return array_values($extensions);
    }

    /**
     * @return array<string, class-string<ExtensionBase>>
     */
    public function get_extension_registry(): array
    {
        $extensions = self::$extensions;

        if (function_exists('veloria_get_pro_extension_map')) {
            $extensions = array_merge($extensions, veloria_get_pro_extension_map());
        }

        return $extensions;
    }

    /**
     * Build REST-safe catalog entries for all extensions
     *
     * @return array<int, array<string, mixed>>
     */
    public function get_extensions_catalog(): array
    {
        $active = OptionsHelper::get_option('active_extensions', []);
        $active = is_array($active) ? $active : [];
        $items  = [];

        foreach ($this->get_extension_registry() as $slug => $class) {
            $instance = emty_container($class);
            $items[]  = $this->serialize_extension($instance, $active);
        }

        return $items;
    }

    /**
     * Persist only valid extension ids (known slugs the user may enable)
     *
     * @param array<int, string> $requested
     * @return array<int, string>
     */
    public function sanitize_active_extension_ids(array $requested): array
    {
        $requested = array_values(
            array_unique(
                array_map(
                    static function ($id): string {
                        return sanitize_text_field((string) $id);
                    },
                    $requested
                )
            )
        );

        $allowed = [];

        foreach ($this->get_extension_registry() as $slug => $class) {
            $instance = emty_container($class);
            if ($this->user_may_enable_extension($instance)) {
                $allowed[] = $slug;
            }
        }

        return array_values(array_intersect($requested, $allowed));
    }

    /**
     * Whether the current site may turn this extension on
     *
     * @param ExtensionBase $extension
     * @return bool
     */
    private function user_may_enable_extension(ExtensionBase $extension): bool
    {
        return !$extension->is_preview_only() || !$this->extension_is_preview_gated($extension);
    }

    /**
     * Whether preview-only extensions stay disabled in admin.
     *
     * @param ExtensionBase $extension
     * @return bool
     */
    private function extension_is_preview_gated(ExtensionBase $extension): bool
    {
        if (!$extension->is_preview_only()) {
            return false;
        }

        if (class_exists('\Veloria\Pro\Utils\PreviewAccess')) {
            return \Veloria\Pro\Utils\PreviewAccess::is_preview_mode();
        }

        return true;
    }

    /**
     * @param ExtensionBase $extension
     * @return bool
     */
    private function extension_is_locked(ExtensionBase $extension): bool
    {
        return $extension->is_preview_only() && $this->extension_is_preview_gated($extension);
    }

    /**
     * @param ExtensionBase $extension
     * @param array<int, string> $active_ids
     * @return array<string, mixed>
     */
    private function serialize_extension(ExtensionBase $extension, array $active_ids): array
    {
        $id        = $extension->get_id();
        $is_locked = $this->extension_is_locked($extension);

        $has_st = $extension->has_settings() && !$is_locked;
        $fields = $has_st ? $extension->get_fields() : [];
        $settings = $has_st ? $this->get_extension_settings_payload($extension->get_id()) : [];

        return [
            'id'          => $id,
            'title'       => $extension->get_title(),
            'description' => $extension->get_description(),
            'isEnabled'   => in_array($id, $active_ids, true) && !$is_locked,
            'isLocked'    => $is_locked,
            'badge'       => $this->resolve_badge($extension),
            'docUrl'      => $extension->get_doc_url(),
            'demoUrl'     => $extension->get_demo_url(),
            'hasSettings' => $extension->has_settings() && !$is_locked,
            'fields'      => $fields,
            'settings'    => $settings,
        ];
    }

    /**
     * Single marketing badge for the card (popular, updated, or new)
     *
     * @param ExtensionBase $extension
     * @return string|null
     */
    private function resolve_badge(ExtensionBase $extension): ?string
    {
        if ($extension->is_popular()) {
            return 'popular';
        }

        $launched_ts = strtotime($extension->get_date_launched());
        $updated_ts  = strtotime($extension->get_date_updated());
        $now         = time();

        if ($launched_ts === false || $updated_ts === false) {
            return null;
        }

        $days_since_update  = ($now - $updated_ts) / DAY_IN_SECONDS;
        $days_since_launch  = ($now - $launched_ts) / DAY_IN_SECONDS;
        $meaningfully_newer = ($updated_ts - $launched_ts) >= DAY_IN_SECONDS;

        if ($days_since_update <= 45 && $meaningfully_newer) {
            return 'updated';
        }

        if ($days_since_launch <= 45) {
            return 'new';
        }

        return null;
    }

    /**
     * Get all active extensions
     *
     * @return array
     */
    public function get_active_extensions(): array
    {
        $active_ids = OptionsHelper::get_option('active_extensions', []);

        if (!is_array($active_ids)) {
            $active_ids = [];
        }

        $classes = [];

        foreach ($this->get_extension_registry() as $slug => $class) {
            if (in_array($slug, $active_ids, true)) {
                $classes[] = $class;
            }
        }

        return $classes;
    }
}
