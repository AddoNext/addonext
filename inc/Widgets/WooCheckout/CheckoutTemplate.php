<?php
namespace Elementrify\Widgets\WooCheckout;

defined('ABSPATH') or exit;

/**
 * Loads checkout view partials from the plugin (themes may override path via filter).
 */
class CheckoutTemplate
{
    /**
     * @param array<string, mixed> $vars Variables available inside the partial.
     */
    public static function partial(string $name, array $vars = []): void
    {
        $relative = 'templates/woo-checkout/partials/' . $name . '.php';
        $path = apply_filters(
            'elementrify_checkout_partial_path',
            ELEMENTRIFY_DIR . $relative,
            $name,
            $vars
        );

        if (!is_string($path) || !is_readable($path)) {
            return;
        }

        // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        extract($vars, EXTR_SKIP);

        include $path;
    }
}
