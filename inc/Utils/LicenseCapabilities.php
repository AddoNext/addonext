<?php
namespace Veloria\Utils;

defined('ABSPATH') or exit;

/**
 * Freemius license checks shared by REST, widget registration, and extension gating.
 */
class LicenseCapabilities
{
    /**
     * Whether the site may use pro-tier widgets and extensions in the UI and runtime.
     *
     * @return bool
     */
    public static function site_has_active_pro_plan(): bool
    {
        if (!function_exists('emt_fs')) {
            return false;
        }

        $fs = emt_fs();

        if (!$fs->can_use_premium_code__premium_only()) {
            return false;
        }

        if (!$fs->is_plan('pro')) {
            return false;
        }

        return $fs->has_active_valid_license();
    }
}
