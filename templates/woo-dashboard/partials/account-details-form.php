<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!isset($user) || !$user instanceof \WP_User) {
    return;
}

$wd_uid = isset($wd_uid) ? (string) $wd_uid : '';
?>
<form class="elementrify-wd-account__form" method="post" autocomplete="off" novalidate>
    <div class="elementrify-wd-account__row">
        <label class="elementrify-wd-account__label" for="elementrify-wd-account-first-<?php echo esc_attr($wd_uid); ?>">
            <?php echo esc_html__('First name', 'elementrify'); ?><span class="elementrify-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="elementrify-wd-account__field">
            <input
                class="elementrify-wd-account__input"
                type="text"
                name="account_first_name"
                id="elementrify-wd-account-first-<?php echo esc_attr($wd_uid); ?>"
                value="<?php echo esc_attr($user->first_name); ?>"
                autocomplete="given-name"
                required
            >
        </div>
    </div>

    <div class="elementrify-wd-account__row">
        <label class="elementrify-wd-account__label" for="elementrify-wd-account-last-<?php echo esc_attr($wd_uid); ?>">
            <?php echo esc_html__('Last name', 'elementrify'); ?><span class="elementrify-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="elementrify-wd-account__field">
            <input
                class="elementrify-wd-account__input"
                type="text"
                name="account_last_name"
                id="elementrify-wd-account-last-<?php echo esc_attr($wd_uid); ?>"
                value="<?php echo esc_attr($user->last_name); ?>"
                autocomplete="family-name"
                required
            >
        </div>
    </div>

    <div class="elementrify-wd-account__row elementrify-wd-account__row--wide">
        <label class="elementrify-wd-account__label" for="elementrify-wd-account-display-<?php echo esc_attr($wd_uid); ?>">
            <?php echo esc_html__('Display name', 'elementrify'); ?><span class="elementrify-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="elementrify-wd-account__field">
            <input
                class="elementrify-wd-account__input"
                type="text"
                name="account_display_name"
                id="elementrify-wd-account-display-<?php echo esc_attr($wd_uid); ?>"
                value="<?php echo esc_attr($user->display_name); ?>"
                autocomplete="nickname"
                required
                aria-describedby="elementrify-wd-account-display-hint-<?php echo esc_attr($wd_uid); ?>"
            >
            <p class="elementrify-wd-account__hint" id="elementrify-wd-account-display-hint-<?php echo esc_attr($wd_uid); ?>">
                <?php echo esc_html__('This will be how your name will be displayed in the account section and in reviews', 'elementrify'); ?>
            </p>
        </div>
    </div>

    <div class="elementrify-wd-account__row">
        <label class="elementrify-wd-account__label" for="elementrify-wd-account-email-<?php echo esc_attr($wd_uid); ?>">
            <?php echo esc_html__('Email address', 'elementrify'); ?><span class="elementrify-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="elementrify-wd-account__field">
            <input
                class="elementrify-wd-account__input"
                type="email"
                name="account_email"
                id="elementrify-wd-account-email-<?php echo esc_attr($wd_uid); ?>"
                value="<?php echo esc_attr($user->user_email); ?>"
                autocomplete="email"
                required
            >
        </div>
    </div>

    <fieldset class="elementrify-wd-account__fieldset">
        <legend class="elementrify-wd-account__legend"><?php echo esc_html__('Password change', 'elementrify'); ?></legend>

        <div class="elementrify-wd-account__row">
            <label class="elementrify-wd-account__label" for="elementrify-wd-account-pass-cur-<?php echo esc_attr($wd_uid); ?>">
                <?php echo esc_html__('Current password (leave blank to leave unchanged)', 'elementrify'); ?>
            </label>
            <div class="elementrify-wd-account__field">
                <input
                    class="elementrify-wd-account__input"
                    type="password"
                    name="password_current"
                    id="elementrify-wd-account-pass-cur-<?php echo esc_attr($wd_uid); ?>"
                    value=""
                    autocomplete="current-password"
                >
            </div>
        </div>

        <div class="elementrify-wd-account__row">
            <label class="elementrify-wd-account__label" for="elementrify-wd-account-pass-1-<?php echo esc_attr($wd_uid); ?>">
                <?php echo esc_html__('New password (leave blank to leave unchanged)', 'elementrify'); ?>
            </label>
            <div class="elementrify-wd-account__field">
                <input
                    class="elementrify-wd-account__input"
                    type="password"
                    name="password_1"
                    id="elementrify-wd-account-pass-1-<?php echo esc_attr($wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="elementrify-wd-account__row">
            <label class="elementrify-wd-account__label" for="elementrify-wd-account-pass-2-<?php echo esc_attr($wd_uid); ?>">
                <?php echo esc_html__('Confirm new password', 'elementrify'); ?>
            </label>
            <div class="elementrify-wd-account__field">
                <input
                    class="elementrify-wd-account__input"
                    type="password"
                    name="password_2"
                    id="elementrify-wd-account-pass-2-<?php echo esc_attr($wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>
    </fieldset>

    <div class="elementrify-wd-account__actions">
        <button type="submit" class="elementrify-wd-account__submit">
            <?php echo esc_html__('Save changes', 'elementrify'); ?>
        </button>
    </div>

    <div class="elementrify-wd-account__message" role="status" aria-live="polite" hidden></div>
</form>

<?php
    }
}
