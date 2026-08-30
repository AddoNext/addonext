<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

if (!isset($addonext_user) || !$addonext_user instanceof \WP_User) {
    return;
}

$addonext_wd_uid = isset($addonext_wd_uid) ? (string) $addonext_wd_uid : '';
?>
<form class="addonext-wd-account__form" method="post" autocomplete="off" novalidate>
    <div class="addonext-wd-account__row">
        <label class="addonext-wd-account__label" for="addonext-wd-account-first-<?php echo esc_attr($addonext_wd_uid); ?>">
            <?php echo esc_html__('First name', 'addonext'); ?><span class="addonext-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="addonext-wd-account__field">
            <input
                class="addonext-wd-account__input"
                type="text"
                name="account_first_name"
                id="addonext-wd-account-first-<?php echo esc_attr($addonext_wd_uid); ?>"
                value="<?php echo esc_attr($addonext_user->first_name); ?>"
                autocomplete="given-name"
                required
            >
        </div>
    </div>

    <div class="addonext-wd-account__row">
        <label class="addonext-wd-account__label" for="addonext-wd-account-last-<?php echo esc_attr($addonext_wd_uid); ?>">
            <?php echo esc_html__('Last name', 'addonext'); ?><span class="addonext-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="addonext-wd-account__field">
            <input
                class="addonext-wd-account__input"
                type="text"
                name="account_last_name"
                id="addonext-wd-account-last-<?php echo esc_attr($addonext_wd_uid); ?>"
                value="<?php echo esc_attr($addonext_user->last_name); ?>"
                autocomplete="family-name"
                required
            >
        </div>
    </div>

    <div class="addonext-wd-account__row addonext-wd-account__row--wide">
        <label class="addonext-wd-account__label" for="addonext-wd-account-display-<?php echo esc_attr($addonext_wd_uid); ?>">
            <?php echo esc_html__('Display name', 'addonext'); ?><span class="addonext-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="addonext-wd-account__field">
            <input
                class="addonext-wd-account__input"
                type="text"
                name="account_display_name"
                id="addonext-wd-account-display-<?php echo esc_attr($addonext_wd_uid); ?>"
                value="<?php echo esc_attr($addonext_user->display_name); ?>"
                autocomplete="nickname"
                required
                aria-describedby="addonext-wd-account-display-hint-<?php echo esc_attr($addonext_wd_uid); ?>"
            >
            <p class="addonext-wd-account__hint" id="addonext-wd-account-display-hint-<?php echo esc_attr($addonext_wd_uid); ?>">
                <?php echo esc_html__('This will be how your name will be displayed in the account section and in reviews', 'addonext'); ?>
            </p>
        </div>
    </div>

    <div class="addonext-wd-account__row">
        <label class="addonext-wd-account__label" for="addonext-wd-account-email-<?php echo esc_attr($addonext_wd_uid); ?>">
            <?php echo esc_html__('Email address', 'addonext'); ?><span class="addonext-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="addonext-wd-account__field">
            <input
                class="addonext-wd-account__input"
                type="email"
                name="account_email"
                id="addonext-wd-account-email-<?php echo esc_attr($addonext_wd_uid); ?>"
                value="<?php echo esc_attr($addonext_user->user_email); ?>"
                autocomplete="email"
                required
            >
        </div>
    </div>

    <fieldset class="addonext-wd-account__fieldset">
        <legend class="addonext-wd-account__legend"><?php echo esc_html__('Password change', 'addonext'); ?></legend>

        <div class="addonext-wd-account__row">
            <label class="addonext-wd-account__label" for="addonext-wd-account-pass-cur-<?php echo esc_attr($addonext_wd_uid); ?>">
                <?php echo esc_html__('Current password (leave blank to leave unchanged)', 'addonext'); ?>
            </label>
            <div class="addonext-wd-account__field">
                <input
                    class="addonext-wd-account__input"
                    type="password"
                    name="password_current"
                    id="addonext-wd-account-pass-cur-<?php echo esc_attr($addonext_wd_uid); ?>"
                    value=""
                    autocomplete="current-password"
                >
            </div>
        </div>

        <div class="addonext-wd-account__row">
            <label class="addonext-wd-account__label" for="addonext-wd-account-pass-1-<?php echo esc_attr($addonext_wd_uid); ?>">
                <?php echo esc_html__('New password (leave blank to leave unchanged)', 'addonext'); ?>
            </label>
            <div class="addonext-wd-account__field">
                <input
                    class="addonext-wd-account__input"
                    type="password"
                    name="password_1"
                    id="addonext-wd-account-pass-1-<?php echo esc_attr($addonext_wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="addonext-wd-account__row">
            <label class="addonext-wd-account__label" for="addonext-wd-account-pass-2-<?php echo esc_attr($addonext_wd_uid); ?>">
                <?php echo esc_html__('Confirm new password', 'addonext'); ?>
            </label>
            <div class="addonext-wd-account__field">
                <input
                    class="addonext-wd-account__input"
                    type="password"
                    name="password_2"
                    id="addonext-wd-account-pass-2-<?php echo esc_attr($addonext_wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>
    </fieldset>

    <div class="addonext-wd-account__actions">
        <button type="submit" class="addonext-wd-account__submit">
            <?php echo esc_html__('Save changes', 'addonext'); ?>
        </button>
    </div>

    <div class="addonext-wd-account__message" role="status" aria-live="polite" hidden></div>
</form>

<?php
    }
}
