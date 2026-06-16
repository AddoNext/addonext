<?php
namespace Veloria\Utils;

defined('ABSPATH') or exit;

/**
 * Ensures WooCommerce frontend scripts are registered before Veloria widgets enqueue them.
 */
class WooCommerceAssets
{
    /**
     * Register WooCommerce frontend scripts outside the normal storefront enqueue flow.
     *
     * @return void
     */
    public static function ensure_frontend_scripts_registered(): void
    {
        if (!function_exists('WC') || !class_exists('WooCommerce', false)) {
            return;
        }

        $wc = WC();
        if (!$wc) {
            return;
        }

        if (method_exists($wc, 'frontend_includes')) {
            $wc->frontend_includes();
        }

        if (!isset($wc->frontend_scripts)
            || !is_object($wc->frontend_scripts)
            || !method_exists($wc->frontend_scripts, 'register_scripts')
        ) {
            return;
        }

        $wc->frontend_scripts->register_scripts();
    }

    /**
     * Keep only script handles that WordPress knows about.
     *
     * @param array<int, string> $dependencies
     * @return array<int, string>
     */
    public static function script_dependencies(array $dependencies): array
    {
        if ($dependencies === []) {
            return [];
        }

        self::ensure_frontend_scripts_registered();

        return array_values(
            array_filter(
                $dependencies,
                static function (string $handle): bool {
                    if (strpos($handle, 'wc-') !== 0) {
                        return true;
                    }

                    return wp_script_is($handle, 'registered');
                }
            )
        );
    }
}
