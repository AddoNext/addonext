<?php
namespace Elementrify\Common;

use Elementrify\Base\ExtensionBase;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Extensions\SmoothScroll\SmoothScroll;

defined('ABSPATH') or exit;

/**
 * Elementrify Extension Manager class
 *
 * @package Elementrify
 * @since 1.0.0
 */
class ExtensionManager
{
    protected static $extensions = [
        SmoothScroll::class
    ];

    public function __construct()
    {
    }

    /**
     * Get all available extension class names
     *
     * @return array<int, class-string<ExtensionBase>>
     */
    public function get_extensions(): array
    {
        return self::$extensions;
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

        foreach (self::$extensions as $class) {
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

        foreach (self::$extensions as $class) {
            $instance = emty_container($class);
            if ($this->user_may_enable_extension($instance)) {
                $allowed[] = $instance->get_id();
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
        return $extension->required_plan() === 'free' || $this->user_has_valid_license();
    }

    /**
     * Whether premium-only UI should treat the extension as locked
     *
     * @param ExtensionBase $extension
     * @return bool
     */
    private function extension_is_locked(ExtensionBase $extension): bool
    {
        return $extension->required_plan() !== 'free' && !$this->user_has_valid_license();
    }

    /**
     * @return bool
     */
    private function user_has_valid_license(): bool
    {
        if (!function_exists('emt_fs')) {
            return false;
        }

        $fs = emt_fs();

        return $fs->is_registered() && $fs->has_active_valid_license();
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

        return [
            'id'          => $id,
            'title'       => $extension->get_title(),
            'description' => $extension->get_description(),
            'isEnabled'   => in_array($id, $active_ids, true),
            'isLocked'    => $is_locked,
            'badge'       => $this->resolve_badge($extension),
            'docUrl'      => $extension->get_doc_url(),
            'demoUrl'     => $extension->get_demo_url(),
            'hasSettings' => $extension->has_settings() && !$is_locked,
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
        $extensions = OptionsHelper::get_option('active_extensions', []);

        return array_filter(self::$extensions, function (string $extension) use ($extensions) {
            return in_array(emty_container($extension)->get_id(), $extensions);
        });
    }
}
