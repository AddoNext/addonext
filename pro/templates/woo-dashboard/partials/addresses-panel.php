<?php
defined('ABSPATH') || exit;

use Veloria\Widgets\WooDashboard\WooDashboard;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_customerId = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $veloria_addressList = apply_filters(
        'woocommerce_my_account_get_addresses',
        [
            'billing'  => __('Billing address', 'veloria'),
            'shipping' => __('Shipping address', 'veloria'),
        ],
        $veloria_customerId
    );
} else {
    $veloria_addressList = apply_filters(
        'woocommerce_my_account_get_addresses',
        [
            'billing' => __('Billing address', 'veloria'),
        ],
        $veloria_customerId
    );
}
?>
<div class="veloria-wd-addresses">
    <p class="veloria-wd-addresses__intro">
        <?php
        echo wp_kses_post(
            apply_filters(
                'woocommerce_my_account_my_address_description',
                esc_html__('The following addresses will be used on the checkout page by default.', 'veloria')
            )
        );
        ?>
    </p>

    <div class="veloria-wd-addresses__grid">
        <?php foreach ($veloria_addressList as $veloria_addressKey => $veloria_addressTitle) : ?>
            <?php
            $veloria_formatted = wc_get_account_formatted_address($veloria_addressKey);
            $veloria_hasAddress = $veloria_formatted !== '';
            $veloria_vars = WooDashboard::get_address_edit_template_vars($veloria_addressKey);
            ob_start();
            wc_get_template('myaccount/form-edit-address.php', $veloria_vars);
            $veloria_formHtml = ob_get_clean();
            $veloria_formHtml = preg_replace('/<form\b/', '<form class="veloria-wd-addresses__wc-form"', $veloria_formHtml, 1);
            ?>
            <article
                class="veloria-wd-addresses__card"
                data-address-key="<?php echo esc_attr($veloria_addressKey); ?>"
                data-address-title="<?php echo esc_attr($veloria_addressTitle); ?>"
            >
                <div class="veloria-wd-addresses__view">
                    <header class="veloria-wd-addresses__card-head">
                        <span class="veloria-wd-addresses__icon" aria-hidden="true">
                            <?php if ($veloria_addressKey === 'billing') : ?>
                                <i class="fas fa-file-invoice"></i>
                            <?php else : ?>
                                <i class="fas fa-truck"></i>
                            <?php endif; ?>
                        </span>
                        <h3 class="veloria-wd-addresses__card-title"><?php echo esc_html($veloria_addressTitle); ?></h3>
                    </header>

                    <div class="veloria-wd-addresses__view-body">
                        <?php if ($veloria_hasAddress) : ?>
                            <div class="veloria-wd-addresses__text"><?php echo wp_kses_post($veloria_formatted); ?></div>
                        <?php else : ?>
                            <p class="veloria-wd-addresses__empty"><?php esc_html_e('You have not set up this type of address yet.', 'veloria'); ?></p>
                        <?php endif; ?>
                        <?php do_action('woocommerce_my_account_after_my_address', $veloria_addressKey); ?>
                    </div>

                    <footer class="veloria-wd-addresses__card-foot">
                        <button type="button" class="veloria-wd-addresses__btn veloria-wd-addresses__toggle-edit">
                            <i class="fas fa-pen" aria-hidden="true"></i>
                            <span class="veloria-wd-addresses__btn-label">
                                <?php
                                printf(
                                    $veloria_hasAddress
                                        ? esc_html__('Edit %s', 'veloria')
                                        : esc_html__('Add %s', 'veloria'),
                                    esc_html($veloria_addressTitle)
                                );
                                ?>
                            </span>
                        </button>
                    </footer>
                </div>

                <div class="veloria-wd-addresses__editor" hidden>
                    <div class="veloria-wd-addresses__form-message" role="status" aria-live="polite" hidden></div>
                    <?php echo $veloria_formHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <div class="veloria-wd-addresses__editor-actions">
                        <button type="button" class="veloria-wd-addresses__cancel-edit">
                            <?php echo esc_html__('Cancel', 'veloria'); ?>
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
