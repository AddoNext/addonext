<?php
namespace Veloria\Pro\Utils;

defined('ABSPATH') or exit;

/**
 * Pro-tier preview mode detection for premium builds.
 */
final class PreviewAccess
{
    /**
     * Whether pro widgets/extensions should behave as preview-only (not fully enabled).
     *
     * @return bool
     */
    public static function is_preview_mode(): bool
    {
        $preview_only = true;

        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {
                $preview_only = false;
            }
        }

        return $preview_only;
    }
}
