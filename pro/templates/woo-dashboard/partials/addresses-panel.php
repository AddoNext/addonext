<?php
defined('ABSPATH') || exit;

use Addonext\Widgets\WooDashboard\WooDashboard;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_customerId = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $addonext_addressList = apply_filters(
        'woocommerce_my_account_get_addresses',
        [
            'billing'  => __('Billing address', 'addonext'),
            'shipping' => __('Shipping address', 'addonext'),
        ],
        $addonext_customerId
    );
} else {
    $addonext_addressList = apply_filters(
        'woocommerce_my_account_get_addresses',
        [
            'billing' => __('Billing address', 'addonext'),
        ],
        $addonext_customerId
    );
}
?>
<div class="addonext-wd-addresses">
    <p class="addonext-wd-addresses__intro">
        <?php
        echo wp_kses_post(
            apply_filters(
                'woocommerce_my_account_my_address_description',
                esc_html__('The following addresses will be used on the checkout page by default.', 'addonext')
            )
        );
        ?>
    </p>

    <div class="addonext-wd-addresses__grid">
        <?php foreach ($addonext_addressList as $addonext_addressKey => $addonext_addressTitle) : ?>
            <?php
            $addonext_formatted = wc_get_account_formatted_address($addonext_addressKey);
            $addonext_hasAddress = $addonext_formatted !== '';
            $addonext_vars = WooDashboard::get_address_edit_template_vars($addonext_addressKey);
            ob_start();
            wc_get_template('myaccount/form-edit-address.php', $addonext_vars);
            $addonext_formHtml = ob_get_clean();
            $addonext_formHtml = preg_replace('/<form\b/', '<form class="addonext-wd-addresses__wc-form"', $addonext_formHtml, 1);
            ?>
            <article
                class="addonext-wd-addresses__card"
                data-address-key="<?php echo esc_attr($addonext_addressKey); ?>"
                data-address-title="<?php echo esc_attr($addonext_addressTitle); ?>"
            >
                <div class="addonext-wd-addresses__view">
                    <header class="addonext-wd-addresses__card-head">
                        <span class="addonext-wd-addresses__icon" aria-hidden="true">
                            <?php if ($addonext_addressKey === 'billing') : ?>
                                <i class="fas fa-file-invoice"></i>
                            <?php else : ?>
                                <i class="fas fa-truck"></i>
                            <?php endif; ?>
                        </span>
                        <h3 class="addonext-wd-addresses__card-title"><?php echo esc_html($addonext_addressTitle); ?></h3>
                    </header>

                    <div class="addonext-wd-addresses__view-body">
                        <?php if ($addonext_hasAddress) : ?>
                            <div class="addonext-wd-addresses__text"><?php echo wp_kses_post($addonext_formatted); ?></div>
                        <?php else : ?>
                            <p class="addonext-wd-addresses__empty"><?php esc_html_e('You have not set up this type of address yet.', 'addonext'); ?></p>
                        <?php endif; ?>
                        <?php do_action('woocommerce_my_account_after_my_address', $addonext_addressKey); ?>
                    </div>

                    <footer class="addonext-wd-addresses__card-foot">
                        <button type="button" class="addonext-wd-addresses__btn addonext-wd-addresses__toggle-edit">
                            <i class="fas fa-pen" aria-hidden="true"></i>
                            <span class="addonext-wd-addresses__btn-label">
                                <?php
                                printf(
                                    $addonext_hasAddress
                                        ? esc_html__('Edit %s', 'addonext')
                                        : esc_html__('Add %s', 'addonext'),
                                    esc_html($addonext_addressTitle)
                                );
                                ?>
                            </span>
                        </button>
                    </footer>
                </div>

                <div class="addonext-wd-addresses__editor" hidden>
                    <div class="addonext-wd-addresses__form-message" role="status" aria-live="polite" hidden></div>
                    <?php echo $addonext_formHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <div class="addonext-wd-addresses__editor-actions">
                        <button type="button" class="addonext-wd-addresses__cancel-edit">
                            <?php echo esc_html__('Cancel', 'addonext'); ?>
                        </button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php
    }
}
