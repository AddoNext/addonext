<?php
namespace Addonext\Widgets\WooCheckout;

defined('ABSPATH') or exit;

/**
 * Outputs checkout fields as AddoNext markup while preserving WooCommerce field names and classes for checkout scripts.
 */
class CheckoutCustomFields
{
    /**
     * Replaces WooCommerce paragraph.form-row shells with AddoNext markup; checkout.js still targets .form-row.
     */
    public static function wrapFieldHtml(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return $html;
        }

        $html = preg_replace('/^<p\s+class="/', '<div class="addonext-co-field addonext-co-tpl-field ', $html, 1);
        $html = preg_replace('/<\/p>\s*$/', '</div>', $html, 1);
        $html = preg_replace(
            '/<span class="woocommerce-input-wrapper">/',
            '<span class="woocommerce-input-wrapper addonext-co-field__inner">',
            $html,
            1
        );

        return $html;
    }

    /**
     * @param array<string, mixed> $args
     * @param mixed                $value
     */
    public static function render_field(string $key, array $args, $value): void
    {
        $args['return'] = true;
        $inputClass = $args['input_class'] ?? [];
        if (is_string($inputClass)) {
            $inputClass = array_filter(array_map('trim', explode(' ', $inputClass)));
        }
        if (!is_array($inputClass)) {
            $inputClass = [];
        }
        $inputClass[] = 'addonext-co-input';
        $args['input_class'] = array_values(array_unique($inputClass));

        $html = woocommerce_form_field($key, $args, $value);
        if (!is_string($html) || $html === '') {
            return;
        }

        echo self::wrapFieldHtml($html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public static function render_fieldset(\WC_Checkout $checkout, string $fieldset): void
    {
        $fields = $checkout->get_checkout_fields($fieldset);
        if ($fields === []) {
            return;
        }

        foreach ($fields as $key => $args) {
            self::render_field($key, $args, $checkout->get_value($key));
        }
    }
}
