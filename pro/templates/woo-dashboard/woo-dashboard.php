<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_showSidebar = ($addonext_settings['show_sidebar'] ?? 'yes') === 'yes';
$addonext_showStats = ($addonext_settings['show_stats'] ?? 'yes') === 'yes';
$addonext_showRecentOrders = ($addonext_settings['show_recent_orders'] ?? 'yes') === 'yes';
$addonext_showQuickActions = ($addonext_settings['show_quick_actions'] ?? 'yes') === 'yes';
$addonext_showPanelOrders = ($addonext_settings['show_panel_orders'] ?? 'yes') === 'yes';
$addonext_showPanelWishlist = ($addonext_settings['show_panel_wishlist'] ?? 'yes') === 'yes';
$addonext_showPanelAccount = ($addonext_settings['show_panel_account'] ?? 'yes') === 'yes';
$addonext_showPanelAddresses = ($addonext_settings['show_panel_addresses'] ?? 'yes') === 'yes';
$addonext_showLogoutNav = ($addonext_settings['show_logout_nav'] ?? 'yes') === 'yes';
$addonext_ordersLimit = isset($addonext_settings['orders_limit']) ? max(1, (int) $addonext_settings['orders_limit']) : 2;
$addonext_ordersListLimit = isset($addonext_settings['orders_list_limit']) ? max(1, min(50, (int) $addonext_settings['orders_list_limit'])) : 25;
$addonext_guestMessage = isset($addonext_settings['guest_message']) ? (string) $addonext_settings['guest_message'] : '';
$addonext_guestButtonLabel = isset($addonext_settings['guest_button_label']) ? (string) $addonext_settings['guest_button_label'] : '';
$addonext_statLabelOrders = isset($addonext_settings['stat_label_orders']) && $addonext_settings['stat_label_orders'] !== ''
    ? (string) $addonext_settings['stat_label_orders'] : __('Total Orders', 'addonext');
$addonext_statLabelSpent = isset($addonext_settings['stat_label_spent']) && $addonext_settings['stat_label_spent'] !== ''
    ? (string) $addonext_settings['stat_label_spent'] : __('Total Spent', 'addonext');
$addonext_statLabelWishlist = isset($addonext_settings['stat_label_wishlist']) && $addonext_settings['stat_label_wishlist'] !== ''
    ? (string) $addonext_settings['stat_label_wishlist'] : __('Wishlist Items', 'addonext');
$addonext_statIconOrders = isset($addonext_settings['stat_icon_orders']) && is_array($addonext_settings['stat_icon_orders']) ? $addonext_settings['stat_icon_orders'] : [];
$addonext_statIconSpent = isset($addonext_settings['stat_icon_spent']) && is_array($addonext_settings['stat_icon_spent']) ? $addonext_settings['stat_icon_spent'] : [];
$addonext_statIconWishlist = isset($addonext_settings['stat_icon_wishlist']) && is_array($addonext_settings['stat_icon_wishlist']) ? $addonext_settings['stat_icon_wishlist'] : [];
$addonext_quickActionsTitle = isset($addonext_settings['quick_actions_title']) && $addonext_settings['quick_actions_title'] !== ''
    ? (string) $addonext_settings['quick_actions_title'] : __('Quick Actions', 'addonext');
$addonext_quickActionsItems = isset($addonext_settings['quick_actions_items']) && is_array($addonext_settings['quick_actions_items'])
    ? $addonext_settings['quick_actions_items'] : [];
$addonext_layoutClass = 'addonext-woo-dashboard__layout';
if (!$addonext_showSidebar) {
    $addonext_layoutClass .= ' addonext-woo-dashboard__layout--no-sidebar';
}
$addonext_cardsRowClass = 'addonext-woo-dashboard__cards';
if ($addonext_showRecentOrders xor $addonext_showQuickActions) {
    $addonext_cardsRowClass .= $addonext_showQuickActions && !$addonext_showRecentOrders
        ? ' addonext-woo-dashboard__cards--quick-only'
        : ' addonext-woo-dashboard__cards--recent-only';
}

$addonext_isWooActive = class_exists('WooCommerce');

?>
<div class="addonext-woo-dashboard">
    <?php if (!$addonext_isWooActive) : ?>
        <div class="addonext-woo-dashboard__notice">
            <?php echo esc_html__('WooCommerce is required for Woo Dashboard.', 'addonext'); ?>
        </div>
    <?php elseif (!is_user_logged_in()) : ?>
        <div class="addonext-woo-dashboard__notice">
            <div class="addonext-woo-dashboard__notice-text"><?php echo esc_html($addonext_guestMessage); ?></div>
            <a class="addonext-woo-dashboard__notice-btn" href="<?php echo esc_url(wp_login_url((string) get_permalink())); ?>">
                <?php echo esc_html($addonext_guestButtonLabel !== '' ? $addonext_guestButtonLabel : __('Log in', 'addonext')); ?>
            </a>
        </div>
    <?php else : ?>
        <?php
        $addonext_userId = (int) get_current_user_id();
        $addonext_user = wp_get_current_user();
        if (!$addonext_user->exists()) {
            return;
        }
        $addonext_displayName = $addonext_user->display_name !== '' ? $addonext_user->display_name : __('Customer', 'addonext');
        $addonext_avatarUrl = (string) get_avatar_url($addonext_userId, ['size' => 96]);
        $addonext_roleLabel = $this->get_user_role_label($addonext_user);

        $addonext_accountUrl = function_exists('wc_get_page_permalink') ? (string) wc_get_page_permalink('myaccount') : '';
        $addonext_ordersUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('orders') : '';
        $addonext_downloadsUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('downloads') : '';
        $addonext_editAccountUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-account') : '';
        $addonext_editAddressUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-address') : '';
        $addonext_logoutUrl = function_exists('wc_logout_url') ? (string) wc_logout_url($addonext_accountUrl !== '' ? $addonext_accountUrl : home_url('/')) : wp_logout_url();

        $addonext_totalOrders = function_exists('wc_get_customer_order_count') ? (int) wc_get_customer_order_count($addonext_userId) : 0;
        $addonext_totalSpent = function_exists('wc_get_customer_total_spent') ? (float) wc_get_customer_total_spent($addonext_userId) : 0.0;
        $addonext_wishlistCount = function_exists('yith_wcwl_count_products') ? (int) yith_wcwl_count_products() : 0;

        $addonext_wd_uid = $this->get_id();

        $addonext_recentOrders = [];
        $addonext_allOrders = [];
        if (function_exists('wc_get_orders')) {
            $addonext_recentOrders = wc_get_orders([
                'customer_id' => $addonext_userId,
                'limit'       => $addonext_ordersLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
            $addonext_allOrders = wc_get_orders([
                'customer_id' => $addonext_userId,
                'limit'       => $addonext_ordersListLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
        }
        ?>

        <div
            class="addonext-woo-dashboard__shell"
            data-logout-url="<?php echo esc_url($addonext_logoutUrl); ?>"
            data-orders-url="<?php echo esc_url($addonext_ordersUrl); ?>"
            data-downloads-url="<?php echo esc_url($addonext_downloadsUrl); ?>"
            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
            data-account-nonce="<?php echo esc_attr(wp_create_nonce('addonext_woo_dashboard_account')); ?>"
            data-address-nonce="<?php echo esc_attr(wp_create_nonce('addonext_woo_dashboard_address')); ?>"
        >
            <div class="<?php echo esc_attr($addonext_layoutClass); ?>">
                <?php if ($addonext_showSidebar) : ?>
                    <aside class="addonext-woo-dashboard__sidebar">
                        <div class="addonext-woo-dashboard__profile">
                            <div class="addonext-woo-dashboard__avatar">
                                <?php if ($addonext_avatarUrl !== '') : ?>
                                    <img src="<?php echo esc_url($addonext_avatarUrl); ?>" alt="<?php echo esc_attr($addonext_displayName); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="addonext-woo-dashboard__profile-meta">
                                <div class="addonext-woo-dashboard__name"><?php echo esc_html($addonext_displayName); ?></div>
                                <div class="addonext-woo-dashboard__badge"><?php echo esc_html($addonext_roleLabel); ?></div>
                            </div>
                        </div>

                        <nav class="addonext-woo-dashboard__nav" aria-label="<?php echo esc_attr__('Account menu', 'addonext'); ?>">
                            <button type="button" class="addonext-woo-dashboard__nav-item is-active" data-wd-panel="dashboard" aria-current="page">
                                <i class="fas fa-home" aria-hidden="true"></i><span><?php echo esc_html__('Dashboard', 'addonext'); ?></span>
                            </button>
                            <?php if ($addonext_showPanelOrders) : ?>
                                <button type="button" class="addonext-woo-dashboard__nav-item" data-wd-panel="orders">
                                    <i class="fas fa-shopping-bag" aria-hidden="true"></i><span><?php echo esc_html__('Orders', 'addonext'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($addonext_showPanelWishlist) : ?>
                                <button type="button" class="addonext-woo-dashboard__nav-item" data-wd-panel="wishlist">
                                    <i class="far fa-heart" aria-hidden="true"></i><span><?php echo esc_html__('Wishlist', 'addonext'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($addonext_showPanelAccount) : ?>
                                <button type="button" class="addonext-woo-dashboard__nav-item" data-wd-panel="edit-account">
                                    <i class="fas fa-user" aria-hidden="true"></i><span><?php echo esc_html__('Account Details', 'addonext'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($addonext_showPanelAddresses) : ?>
                                <button type="button" class="addonext-woo-dashboard__nav-item" data-wd-panel="edit-address">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i><span><?php echo esc_html__('Addresses', 'addonext'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($addonext_showLogoutNav) : ?>
                                <button type="button" class="addonext-woo-dashboard__nav-item addonext-woo-dashboard__nav-item--logout" data-wd-action="logout">
                                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span><?php echo esc_html__('Logout', 'addonext'); ?></span>
                                </button>
                            <?php endif; ?>
                        </nav>
                    </aside>
                <?php endif; ?>

                <section class="addonext-woo-dashboard__main">
                    <?php
                    if (function_exists('wc_print_notices')) {
                        wc_print_notices();
                    }
                    ?>

                    <div class="addonext-woo-dashboard__panels">
                        <div class="addonext-woo-dashboard__panel" data-wd-panel-id="dashboard" id="addonext-wd-panel-dashboard-<?php echo esc_attr($addonext_wd_uid); ?>">
                            <?php if ($addonext_showStats) : ?>
                                <div class="addonext-woo-dashboard__stats">
                                    <div class="addonext-woo-dashboard__stat addonext-woo-dashboard__stat--orders">
                                        <div class="addonext-woo-dashboard__stat-label"><?php echo esc_html($addonext_statLabelOrders); ?></div>
                                        <div class="addonext-woo-dashboard__stat-value"><?php echo esc_html((string) $addonext_totalOrders); ?></div>
                                        <div class="addonext-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($addonext_statIconOrders, 'fas fa-shopping-bag'); ?></div>
                                    </div>
                                    <div class="addonext-woo-dashboard__stat addonext-woo-dashboard__stat--spent">
                                        <div class="addonext-woo-dashboard__stat-label"><?php echo esc_html($addonext_statLabelSpent); ?></div>
                                        <div class="addonext-woo-dashboard__stat-value"><?php echo wp_kses_post(wc_price($addonext_totalSpent)); ?></div>
                                        <div class="addonext-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($addonext_statIconSpent, 'fas fa-dollar-sign'); ?></div>
                                    </div>
                                    <div class="addonext-woo-dashboard__stat addonext-woo-dashboard__stat--wishlist">
                                        <div class="addonext-woo-dashboard__stat-label"><?php echo esc_html($addonext_statLabelWishlist); ?></div>
                                        <div class="addonext-woo-dashboard__stat-value"><?php echo esc_html((string) $addonext_wishlistCount); ?></div>
                                        <div class="addonext-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($addonext_statIconWishlist, 'fas fa-heart'); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($addonext_showRecentOrders || ($addonext_showQuickActions && !empty($addonext_quickActionsItems))) : ?>
                            <div class="<?php echo esc_attr($addonext_cardsRowClass); ?>">
                                <?php if ($addonext_showRecentOrders) : ?>
                                <div class="addonext-woo-dashboard__card">
                                    <div class="addonext-woo-dashboard__card-header">
                                        <div class="addonext-woo-dashboard__card-title"><?php echo esc_html__('Recent Orders', 'addonext'); ?></div>
                                        <?php if ($addonext_showPanelOrders) : ?>
                                            <button type="button" class="addonext-woo-dashboard__card-link addonext-woo-dashboard__card-link--btn" data-wd-panel="orders">
                                                <?php echo esc_html__('View all', 'addonext'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($addonext_recentOrders)) : ?>
                                        <div class="addonext-woo-dashboard__order-list">
                                            <?php foreach ($addonext_recentOrders as $addonext_order) : ?>
                                                <?php
                                                if (!is_a($addonext_order, \WC_Order::class)) {
                                                    continue;
                                                }
                                                $addonext_orderNumber = $addonext_order->get_order_number();
                                                $addonext_orderDate = $addonext_order->get_date_created();
                                                $addonext_orderTotal = $addonext_order->get_formatted_order_total();
                                                $addonext_statusSlug = 'wc-' . $addonext_order->get_status();
                                                $addonext_statusLabel = wc_get_order_status_name($addonext_order->get_status());
                                                ?>
                                                <div class="addonext-woo-dashboard__order-row">
                                                    <div class="addonext-woo-dashboard__order-left">
                                                        <div class="addonext-woo-dashboard__order-number">#<?php echo esc_html((string) $addonext_orderNumber); ?></div>
                                                        <div class="addonext-woo-dashboard__order-date">
                                                            <?php echo esc_html($addonext_orderDate ? wc_format_datetime($addonext_orderDate) : ''); ?>
                                                        </div>
                                                    </div>
                                                    <div class="addonext-woo-dashboard__order-right">
                                                        <div class="addonext-woo-dashboard__order-total"><?php echo wp_kses_post($addonext_orderTotal); ?></div>
                                                        <div class="addonext-woo-dashboard__order-status <?php echo esc_attr($addonext_statusSlug); ?>">
                                                            <?php echo esc_html($addonext_statusLabel); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="addonext-woo-dashboard__empty">
                                            <?php echo esc_html__('No recent orders yet.', 'addonext'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($addonext_showQuickActions && !empty($addonext_quickActionsItems)) : ?>
                                    <div class="addonext-woo-dashboard__card addonext-woo-dashboard__card--quick-actions">
                                        <div class="addonext-woo-dashboard__card-header">
                                            <div class="addonext-woo-dashboard__card-title"><?php echo esc_html($addonext_quickActionsTitle); ?></div>
                                        </div>
                                        <div class="addonext-woo-dashboard__actions">
                                            <?php foreach ($addonext_quickActionsItems as $addonext_qaItem) : ?>
                                                <?php
                                                if (!is_array($addonext_qaItem)) {
                                                    continue;
                                                }
                                                $addonext_qaLabel = isset($addonext_qaItem['item_label']) ? (string) $addonext_qaItem['item_label'] : '';
                                                if ($addonext_qaLabel === '') {
                                                    continue;
                                                }
                                                $addonext_qaIcon = isset($addonext_qaItem['item_icon']) && is_array($addonext_qaItem['item_icon']) ? $addonext_qaItem['item_icon'] : [];
                                                $addonext_qaAction = isset($addonext_qaItem['item_action']) ? (string) $addonext_qaItem['item_action'] : 'panel';
                                                $addonext_qaPanel = isset($addonext_qaItem['item_panel']) ? (string) $addonext_qaItem['item_panel'] : 'orders';
                                                $addonext_qaLink = isset($addonext_qaItem['item_link']) && is_array($addonext_qaItem['item_link']) ? $addonext_qaItem['item_link'] : [];
                                                $addonext_iconStyleParts = [];
                                                if (!empty($addonext_qaItem['item_icon_color'])) {
                                                    $addonext_iconStyleParts[] = 'color:' . $addonext_qaItem['item_icon_color'];
                                                }
                                                $addonext_iconStyleAttr = $addonext_iconStyleParts !== [] ? ' style="' . esc_attr(implode(';', $addonext_iconStyleParts)) . '"' : '';
                                                ?>
                                                <?php if ($addonext_qaAction === 'url') : ?>
                                                    <?php
                                                    $addonext_href = isset($addonext_qaLink['url']) ? (string) $addonext_qaLink['url'] : '';
                                                    if ($addonext_href === '') {
                                                        $addonext_href = '#';
                                                    }
                                                    $addonext_target = !empty($addonext_qaLink['is_external']) ? ' target="_blank"' : '';
                                                    $addonext_relParts = ['noopener', 'noreferrer'];
                                                    if (!empty($addonext_qaLink['nofollow'])) {
                                                        $addonext_relParts[] = 'nofollow';
                                                    }
                                                    $addonext_rel = !empty($addonext_qaLink['is_external']) || !empty($addonext_qaLink['nofollow'])
                                                        ? ' rel="' . esc_attr(implode(' ', $addonext_relParts)) . '"'
                                                        : '';
                                                    ?>
                                                    <a class="addonext-woo-dashboard__action" href="<?php echo esc_url($addonext_href); ?>"<?php echo $addonext_target . $addonext_rel; ?>>
                                                        <span class="addonext-woo-dashboard__action-icon"<?php echo $addonext_iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($addonext_qaIcon, 'fas fa-link'); ?>
                                                        </span>
                                                        <span class="addonext-woo-dashboard__action-label"><?php echo esc_html($addonext_qaLabel); ?></span>
                                                    </a>
                                                <?php else : ?>
                                                    <button type="button" class="addonext-woo-dashboard__action" data-wd-panel="<?php echo esc_attr($addonext_qaPanel); ?>">
                                                        <span class="addonext-woo-dashboard__action-icon"<?php echo $addonext_iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($addonext_qaIcon, 'fas fa-circle'); ?>
                                                        </span>
                                                        <span class="addonext-woo-dashboard__action-label"><?php echo esc_html($addonext_qaLabel); ?></span>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($addonext_showPanelOrders) : ?>
                        <div class="addonext-woo-dashboard__panel" data-wd-panel-id="orders" id="addonext-wd-panel-orders-<?php echo esc_attr($addonext_wd_uid); ?>" hidden>
                            <div class="addonext-woo-dashboard__screen">
                                <h2 class="addonext-woo-dashboard__screen-title"><?php echo esc_html__('Orders', 'addonext'); ?></h2>
                                <?php if (!empty($addonext_allOrders)) : ?>
                                    <div class="addonext-woo-dashboard__order-list">
                                        <?php foreach ($addonext_allOrders as $addonext_order) : ?>
                                            <?php
                                            if (!is_a($addonext_order, \WC_Order::class)) {
                                                continue;
                                            }
                                            $addonext_orderNumber = $addonext_order->get_order_number();
                                            $addonext_orderDate = $addonext_order->get_date_created();
                                            $addonext_orderTotal = $addonext_order->get_formatted_order_total();
                                            $addonext_statusSlug = 'wc-' . $addonext_order->get_status();
                                            $addonext_statusLabel = wc_get_order_status_name($addonext_order->get_status());
                                            ?>
                                            <div class="addonext-woo-dashboard__order-row">
                                                <div class="addonext-woo-dashboard__order-left">
                                                    <div class="addonext-woo-dashboard__order-number">#<?php echo esc_html((string) $addonext_orderNumber); ?></div>
                                                    <div class="addonext-woo-dashboard__order-date">
                                                        <?php echo esc_html($addonext_orderDate ? wc_format_datetime($addonext_orderDate) : ''); ?>
                                                    </div>
                                                </div>
                                                <div class="addonext-woo-dashboard__order-right">
                                                    <div class="addonext-woo-dashboard__order-total"><?php echo wp_kses_post($addonext_orderTotal); ?></div>
                                                    <div class="addonext-woo-dashboard__order-status <?php echo esc_attr($addonext_statusSlug); ?>">
                                                        <?php echo esc_html($addonext_statusLabel); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="addonext-woo-dashboard__empty"><?php echo esc_html__('No orders found.', 'addonext'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($addonext_showPanelWishlist) : ?>
                        <div class="addonext-woo-dashboard__panel" data-wd-panel-id="wishlist" id="addonext-wd-panel-wishlist-<?php echo esc_attr($addonext_wd_uid); ?>" hidden>
                            <div class="addonext-woo-dashboard__screen">
                                <h2 class="addonext-woo-dashboard__screen-title"><?php echo esc_html__('Wishlist', 'addonext'); ?></h2>
                                <div class="addonext-woo-dashboard__screen-body addonext-woo-dashboard__screen-body--wishlist">
                                    <?php
                                    if (shortcode_exists('yith_wcwl_wishlist')) {
                                        echo do_shortcode('[yith_wcwl_wishlist]');
                                    } elseif (shortcode_exists('ti_wishlistsview')) {
                                        echo do_shortcode('[ti_wishlistsview]');
                                    } else {
                                        echo '<div class="addonext-woo-dashboard__empty">';
                                        echo esc_html__('Connect a wishlist plugin (for example YITH Wishlist) to show items here.', 'addonext');
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($addonext_showPanelAccount) : ?>
                        <div class="addonext-woo-dashboard__panel" data-wd-panel-id="edit-account" id="addonext-wd-panel-edit-account-<?php echo esc_attr($addonext_wd_uid); ?>" hidden>
                            <div class="addonext-woo-dashboard__screen addonext-wd-account">
                                <h2 class="addonext-woo-dashboard__screen-title"><?php echo esc_html__('Account details', 'addonext'); ?></h2>
                                <?php
                                $addonext_partial = ADDONEXT_DIR . 'pro/templates/woo-dashboard/partials/account-details-form.php';
                                if (is_readable($addonext_partial)) {
                                    include $addonext_partial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($addonext_showPanelAddresses) : ?>
                        <div class="addonext-woo-dashboard__panel" data-wd-panel-id="edit-address" id="addonext-wd-panel-edit-address-<?php echo esc_attr($addonext_wd_uid); ?>" hidden>
                            <div class="addonext-woo-dashboard__screen addonext-wd-addresses-screen">
                                <h2 class="addonext-woo-dashboard__screen-title"><?php echo esc_html__('Addresses', 'addonext'); ?></h2>
                                <?php
                                $addonext_addrPartial = ADDONEXT_DIR . 'pro/templates/woo-dashboard/partials/addresses-panel.php';
                                if (is_readable($addonext_addrPartial)) {
                                    include $addonext_addrPartial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="addonext-woo-dashboard__modal" id="addonext-wd-logout-modal-<?php echo esc_attr($addonext_wd_uid); ?>" hidden role="dialog" aria-modal="true" aria-labelledby="addonext-wd-logout-title-<?php echo esc_attr($addonext_wd_uid); ?>">
                <button type="button" class="addonext-woo-dashboard__modal-backdrop" data-wd-action="logout-dismiss" aria-label="<?php echo esc_attr__('Close', 'addonext'); ?>"></button>
                <div class="addonext-woo-dashboard__modal-dialog">
                    <h2 class="addonext-woo-dashboard__modal-title" id="addonext-wd-logout-title-<?php echo esc_attr($addonext_wd_uid); ?>"><?php echo esc_html__('Log out?', 'addonext'); ?></h2>
                    <p class="addonext-woo-dashboard__modal-text"><?php echo esc_html__('You will need to sign in again to access your account.', 'addonext'); ?></p>
                    <div class="addonext-woo-dashboard__modal-actions">
                        <button type="button" class="addonext-woo-dashboard__modal-btn addonext-woo-dashboard__modal-btn--ghost" data-wd-action="logout-dismiss">
                            <?php echo esc_html__('Cancel', 'addonext'); ?>
                        </button>
                        <a class="addonext-woo-dashboard__modal-btn addonext-woo-dashboard__modal-btn--danger" href="<?php echo esc_url($addonext_logoutUrl); ?>">
                            <?php echo esc_html__('Log out', 'addonext'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
    }
}
