<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!isset($veloria_user) || !$veloria_user instanceof \WP_User) {
    return;
}

$veloria_wd_uid = isset($veloria_wd_uid) ? (string) $veloria_wd_uid : '';
?>
<form class="veloria-wd-account__form" method="post" autocomplete="off" novalidate>
    <div class="veloria-wd-account__row">
        <label class="veloria-wd-account__label" for="veloria-wd-account-first-<?php echo esc_attr($veloria_wd_uid); ?>">
            <?php echo esc_html__('First name', 'veloria'); ?><span class="veloria-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="veloria-wd-account__field">
            <input
                class="veloria-wd-account__input"
                type="text"
                name="account_first_name"
                id="veloria-wd-account-first-<?php echo esc_attr($veloria_wd_uid); ?>"
                value="<?php echo esc_attr($veloria_user->first_name); ?>"
                autocomplete="given-name"
                required
            >
        </div>
    </div>

    <div class="veloria-wd-account__row">
        <label class="veloria-wd-account__label" for="veloria-wd-account-last-<?php echo esc_attr($veloria_wd_uid); ?>">
            <?php echo esc_html__('Last name', 'veloria'); ?><span class="veloria-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="veloria-wd-account__field">
            <input
                class="veloria-wd-account__input"
                type="text"
                name="account_last_name"
                id="veloria-wd-account-last-<?php echo esc_attr($veloria_wd_uid); ?>"
                value="<?php echo esc_attr($veloria_user->last_name); ?>"
                autocomplete="family-name"
                required
            >
        </div>
    </div>

    <div class="veloria-wd-account__row veloria-wd-account__row--wide">
        <label class="veloria-wd-account__label" for="veloria-wd-account-display-<?php echo esc_attr($veloria_wd_uid); ?>">
            <?php echo esc_html__('Display name', 'veloria'); ?><span class="veloria-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="veloria-wd-account__field">
            <input
                class="veloria-wd-account__input"
                type="text"
                name="account_display_name"
                id="veloria-wd-account-display-<?php echo esc_attr($veloria_wd_uid); ?>"
                value="<?php echo esc_attr($veloria_user->display_name); ?>"
                autocomplete="nickname"
                required
                aria-describedby="veloria-wd-account-display-hint-<?php echo esc_attr($veloria_wd_uid); ?>"
            >
            <p class="veloria-wd-account__hint" id="veloria-wd-account-display-hint-<?php echo esc_attr($veloria_wd_uid); ?>">
                <?php echo esc_html__('This will be how your name will be displayed in the account section and in reviews', 'veloria'); ?>
            </p>
        </div>
    </div>

    <div class="veloria-wd-account__row">
        <label class="veloria-wd-account__label" for="veloria-wd-account-email-<?php echo esc_attr($veloria_wd_uid); ?>">
            <?php echo esc_html__('Email address', 'veloria'); ?><span class="veloria-wd-account__required" aria-hidden="true">*</span>
        </label>
        <div class="veloria-wd-account__field">
            <input
                class="veloria-wd-account__input"
                type="email"
                name="account_email"
                id="veloria-wd-account-email-<?php echo esc_attr($veloria_wd_uid); ?>"
                value="<?php echo esc_attr($veloria_user->user_email); ?>"
                autocomplete="email"
                required
            >
        </div>
    </div>

    <fieldset class="veloria-wd-account__fieldset">
        <legend class="veloria-wd-account__legend"><?php echo esc_html__('Password change', 'veloria'); ?></legend>

        <div class="veloria-wd-account__row">
            <label class="veloria-wd-account__label" for="veloria-wd-account-pass-cur-<?php echo esc_attr($veloria_wd_uid); ?>">
                <?php echo esc_html__('Current password (leave blank to leave unchanged)', 'veloria'); ?>
            </label>
            <div class="veloria-wd-account__field">
                <input
                    class="veloria-wd-account__input"
                    type="password"
                    name="password_current"
                    id="veloria-wd-account-pass-cur-<?php echo esc_attr($veloria_wd_uid); ?>"
                    value=""
                    autocomplete="current-password"
                >
            </div>
        </div>

        <div class="veloria-wd-account__row">
            <label class="veloria-wd-account__label" for="veloria-wd-account-pass-1-<?php echo esc_attr($veloria_wd_uid); ?>">
                <?php echo esc_html__('New password (leave blank to leave unchanged)', 'veloria'); ?>
            </label>
            <div class="veloria-wd-account__field">
                <input
                    class="veloria-wd-account__input"
                    type="password"
                    name="password_1"
                    id="veloria-wd-account-pass-1-<?php echo esc_attr($veloria_wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="veloria-wd-account__row">
            <label class="veloria-wd-account__label" for="veloria-wd-account-pass-2-<?php echo esc_attr($veloria_wd_uid); ?>">
                <?php echo esc_html__('Confirm new password', 'veloria'); ?>
            </label>
            <div class="veloria-wd-account__field">
                <input
                    class="veloria-wd-account__input"
                    type="password"
                    name="password_2"
                    id="veloria-wd-account-pass-2-<?php echo esc_attr($veloria_wd_uid); ?>"
                    value=""
                    autocomplete="new-password"
                >
            </div>
        </div>
    </fieldset>

    <div class="veloria-wd-account__actions">
        <button type="submit" class="veloria-wd-account__submit">
            <?php echo esc_html__('Save changes', 'veloria'); ?>
        </button>
    </div>

    <div class="veloria-wd-account__message" role="status" aria-live="polite" hidden></div>
</form>

<?php
    }
}
