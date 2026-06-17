<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_showSidebar = ($veloria_settings['show_sidebar'] ?? 'yes') === 'yes';
$veloria_showStats = ($veloria_settings['show_stats'] ?? 'yes') === 'yes';
$veloria_showRecentOrders = ($veloria_settings['show_recent_orders'] ?? 'yes') === 'yes';
$veloria_showQuickActions = ($veloria_settings['show_quick_actions'] ?? 'yes') === 'yes';
$veloria_showPanelOrders = ($veloria_settings['show_panel_orders'] ?? 'yes') === 'yes';
$veloria_showPanelWishlist = ($veloria_settings['show_panel_wishlist'] ?? 'yes') === 'yes';
$veloria_showPanelAccount = ($veloria_settings['show_panel_account'] ?? 'yes') === 'yes';
$veloria_showPanelAddresses = ($veloria_settings['show_panel_addresses'] ?? 'yes') === 'yes';
$veloria_showLogoutNav = ($veloria_settings['show_logout_nav'] ?? 'yes') === 'yes';
$veloria_ordersLimit = isset($veloria_settings['orders_limit']) ? max(1, (int) $veloria_settings['orders_limit']) : 2;
$veloria_ordersListLimit = isset($veloria_settings['orders_list_limit']) ? max(1, min(50, (int) $veloria_settings['orders_list_limit'])) : 25;
$veloria_guestMessage = isset($veloria_settings['guest_message']) ? (string) $veloria_settings['guest_message'] : '';
$veloria_guestButtonLabel = isset($veloria_settings['guest_button_label']) ? (string) $veloria_settings['guest_button_label'] : '';
$veloria_statLabelOrders = isset($veloria_settings['stat_label_orders']) && $veloria_settings['stat_label_orders'] !== ''
    ? (string) $veloria_settings['stat_label_orders'] : __('Total Orders', 'veloria');
$veloria_statLabelSpent = isset($veloria_settings['stat_label_spent']) && $veloria_settings['stat_label_spent'] !== ''
    ? (string) $veloria_settings['stat_label_spent'] : __('Total Spent', 'veloria');
$veloria_statLabelWishlist = isset($veloria_settings['stat_label_wishlist']) && $veloria_settings['stat_label_wishlist'] !== ''
    ? (string) $veloria_settings['stat_label_wishlist'] : __('Wishlist Items', 'veloria');
$veloria_statIconOrders = isset($veloria_settings['stat_icon_orders']) && is_array($veloria_settings['stat_icon_orders']) ? $veloria_settings['stat_icon_orders'] : [];
$veloria_statIconSpent = isset($veloria_settings['stat_icon_spent']) && is_array($veloria_settings['stat_icon_spent']) ? $veloria_settings['stat_icon_spent'] : [];
$veloria_statIconWishlist = isset($veloria_settings['stat_icon_wishlist']) && is_array($veloria_settings['stat_icon_wishlist']) ? $veloria_settings['stat_icon_wishlist'] : [];
$veloria_quickActionsTitle = isset($veloria_settings['quick_actions_title']) && $veloria_settings['quick_actions_title'] !== ''
    ? (string) $veloria_settings['quick_actions_title'] : __('Quick Actions', 'veloria');
$veloria_quickActionsItems = isset($veloria_settings['quick_actions_items']) && is_array($veloria_settings['quick_actions_items'])
    ? $veloria_settings['quick_actions_items'] : [];
$veloria_layoutClass = 'veloria-woo-dashboard__layout';
if (!$veloria_showSidebar) {
    $veloria_layoutClass .= ' veloria-woo-dashboard__layout--no-sidebar';
}
$veloria_cardsRowClass = 'veloria-woo-dashboard__cards';
if ($veloria_showRecentOrders xor $veloria_showQuickActions) {
    $veloria_cardsRowClass .= $veloria_showQuickActions && !$veloria_showRecentOrders
        ? ' veloria-woo-dashboard__cards--quick-only'
        : ' veloria-woo-dashboard__cards--recent-only';
}

$veloria_isWooActive = class_exists('WooCommerce');

?>
<div class="veloria-woo-dashboard">
    <?php if (!$veloria_isWooActive) : ?>
        <div class="veloria-woo-dashboard__notice">
            <?php echo esc_html__('WooCommerce is required for Woo Dashboard.', 'veloria'); ?>
        </div>
    <?php elseif (!is_user_logged_in()) : ?>
        <div class="veloria-woo-dashboard__notice">
            <div class="veloria-woo-dashboard__notice-text"><?php echo esc_html($veloria_guestMessage); ?></div>
            <a class="veloria-woo-dashboard__notice-btn" href="<?php echo esc_url(wp_login_url((string) get_permalink())); ?>">
                <?php echo esc_html($veloria_guestButtonLabel !== '' ? $veloria_guestButtonLabel : __('Log in', 'veloria')); ?>
            </a>
        </div>
    <?php else : ?>
        <?php
        $veloria_userId = (int) get_current_user_id();
        $veloria_user = wp_get_current_user();
        if (!$veloria_user->exists()) {
            return;
        }
        $veloria_displayName = $veloria_user->display_name !== '' ? $veloria_user->display_name : __('Customer', 'veloria');
        $veloria_avatarUrl = (string) get_avatar_url($veloria_userId, ['size' => 96]);
        $veloria_roleLabel = $this->get_user_role_label($veloria_user);

        $veloria_accountUrl = function_exists('wc_get_page_permalink') ? (string) wc_get_page_permalink('myaccount') : '';
        $veloria_ordersUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('orders') : '';
        $veloria_downloadsUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('downloads') : '';
        $veloria_editAccountUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-account') : '';
        $veloria_editAddressUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-address') : '';
        $veloria_logoutUrl = function_exists('wc_logout_url') ? (string) wc_logout_url($veloria_accountUrl !== '' ? $veloria_accountUrl : home_url('/')) : wp_logout_url();

        $veloria_totalOrders = function_exists('wc_get_customer_order_count') ? (int) wc_get_customer_order_count($veloria_userId) : 0;
        $veloria_totalSpent = function_exists('wc_get_customer_total_spent') ? (float) wc_get_customer_total_spent($veloria_userId) : 0.0;
        $veloria_wishlistCount = function_exists('yith_wcwl_count_products') ? (int) yith_wcwl_count_products() : 0;

        $veloria_wd_uid = $this->get_id();

        $veloria_recentOrders = [];
        $veloria_allOrders = [];
        if (function_exists('wc_get_orders')) {
            $veloria_recentOrders = wc_get_orders([
                'customer_id' => $veloria_userId,
                'limit'       => $veloria_ordersLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
            $veloria_allOrders = wc_get_orders([
                'customer_id' => $veloria_userId,
                'limit'       => $veloria_ordersListLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
        }
        ?>

        <div
            class="veloria-woo-dashboard__shell"
            data-logout-url="<?php echo esc_url($veloria_logoutUrl); ?>"
            data-orders-url="<?php echo esc_url($veloria_ordersUrl); ?>"
            data-downloads-url="<?php echo esc_url($veloria_downloadsUrl); ?>"
            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
            data-account-nonce="<?php echo esc_attr(wp_create_nonce('veloria_woo_dashboard_account')); ?>"
            data-address-nonce="<?php echo esc_attr(wp_create_nonce('veloria_woo_dashboard_address')); ?>"
        >
            <div class="<?php echo esc_attr($veloria_layoutClass); ?>">
                <?php if ($veloria_showSidebar) : ?>
                    <aside class="veloria-woo-dashboard__sidebar">
                        <div class="veloria-woo-dashboard__profile">
                            <div class="veloria-woo-dashboard__avatar">
                                <?php if ($veloria_avatarUrl !== '') : ?>
                                    <img src="<?php echo esc_url($veloria_avatarUrl); ?>" alt="<?php echo esc_attr($veloria_displayName); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="veloria-woo-dashboard__profile-meta">
                                <div class="veloria-woo-dashboard__name"><?php echo esc_html($veloria_displayName); ?></div>
                                <div class="veloria-woo-dashboard__badge"><?php echo esc_html($veloria_roleLabel); ?></div>
                            </div>
                        </div>

                        <nav class="veloria-woo-dashboard__nav" aria-label="<?php echo esc_attr__('Account menu', 'veloria'); ?>">
                            <button type="button" class="veloria-woo-dashboard__nav-item is-active" data-wd-panel="dashboard" aria-current="page">
                                <i class="fas fa-home" aria-hidden="true"></i><span><?php echo esc_html__('Dashboard', 'veloria'); ?></span>
                            </button>
                            <?php if ($veloria_showPanelOrders) : ?>
                                <button type="button" class="veloria-woo-dashboard__nav-item" data-wd-panel="orders">
                                    <i class="fas fa-shopping-bag" aria-hidden="true"></i><span><?php echo esc_html__('Orders', 'veloria'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($veloria_showPanelWishlist) : ?>
                                <button type="button" class="veloria-woo-dashboard__nav-item" data-wd-panel="wishlist">
                                    <i class="far fa-heart" aria-hidden="true"></i><span><?php echo esc_html__('Wishlist', 'veloria'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($veloria_showPanelAccount) : ?>
                                <button type="button" class="veloria-woo-dashboard__nav-item" data-wd-panel="edit-account">
                                    <i class="fas fa-user" aria-hidden="true"></i><span><?php echo esc_html__('Account Details', 'veloria'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($veloria_showPanelAddresses) : ?>
                                <button type="button" class="veloria-woo-dashboard__nav-item" data-wd-panel="edit-address">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i><span><?php echo esc_html__('Addresses', 'veloria'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($veloria_showLogoutNav) : ?>
                                <button type="button" class="veloria-woo-dashboard__nav-item veloria-woo-dashboard__nav-item--logout" data-wd-action="logout">
                                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span><?php echo esc_html__('Logout', 'veloria'); ?></span>
                                </button>
                            <?php endif; ?>
                        </nav>
                    </aside>
                <?php endif; ?>

                <section class="veloria-woo-dashboard__main">
                    <?php
                    if (function_exists('wc_print_notices')) {
                        wc_print_notices();
                    }
                    ?>

                    <div class="veloria-woo-dashboard__panels">
                        <div class="veloria-woo-dashboard__panel" data-wd-panel-id="dashboard" id="veloria-wd-panel-dashboard-<?php echo esc_attr($veloria_wd_uid); ?>">
                            <?php if ($veloria_showStats) : ?>
                                <div class="veloria-woo-dashboard__stats">
                                    <div class="veloria-woo-dashboard__stat veloria-woo-dashboard__stat--orders">
                                        <div class="veloria-woo-dashboard__stat-label"><?php echo esc_html($veloria_statLabelOrders); ?></div>
                                        <div class="veloria-woo-dashboard__stat-value"><?php echo esc_html((string) $veloria_totalOrders); ?></div>
                                        <div class="veloria-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($veloria_statIconOrders, 'fas fa-shopping-bag'); ?></div>
                                    </div>
                                    <div class="veloria-woo-dashboard__stat veloria-woo-dashboard__stat--spent">
                                        <div class="veloria-woo-dashboard__stat-label"><?php echo esc_html($veloria_statLabelSpent); ?></div>
                                        <div class="veloria-woo-dashboard__stat-value"><?php echo wp_kses_post(wc_price($veloria_totalSpent)); ?></div>
                                        <div class="veloria-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($veloria_statIconSpent, 'fas fa-dollar-sign'); ?></div>
                                    </div>
                                    <div class="veloria-woo-dashboard__stat veloria-woo-dashboard__stat--wishlist">
                                        <div class="veloria-woo-dashboard__stat-label"><?php echo esc_html($veloria_statLabelWishlist); ?></div>
                                        <div class="veloria-woo-dashboard__stat-value"><?php echo esc_html((string) $veloria_wishlistCount); ?></div>
                                        <div class="veloria-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($veloria_statIconWishlist, 'fas fa-heart'); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($veloria_showRecentOrders || ($veloria_showQuickActions && !empty($veloria_quickActionsItems))) : ?>
                            <div class="<?php echo esc_attr($veloria_cardsRowClass); ?>">
                                <?php if ($veloria_showRecentOrders) : ?>
                                <div class="veloria-woo-dashboard__card">
                                    <div class="veloria-woo-dashboard__card-header">
                                        <div class="veloria-woo-dashboard__card-title"><?php echo esc_html__('Recent Orders', 'veloria'); ?></div>
                                        <?php if ($veloria_showPanelOrders) : ?>
                                            <button type="button" class="veloria-woo-dashboard__card-link veloria-woo-dashboard__card-link--btn" data-wd-panel="orders">
                                                <?php echo esc_html__('View all', 'veloria'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($veloria_recentOrders)) : ?>
                                        <div class="veloria-woo-dashboard__order-list">
                                            <?php foreach ($veloria_recentOrders as $veloria_order) : ?>
                                                <?php
                                                if (!is_a($veloria_order, \WC_Order::class)) {
                                                    continue;
                                                }
                                                $veloria_orderNumber = $veloria_order->get_order_number();
                                                $veloria_orderDate = $veloria_order->get_date_created();
                                                $veloria_orderTotal = $veloria_order->get_formatted_order_total();
                                                $veloria_statusSlug = 'wc-' . $veloria_order->get_status();
                                                $veloria_statusLabel = wc_get_order_status_name($veloria_order->get_status());
                                                ?>
                                                <div class="veloria-woo-dashboard__order-row">
                                                    <div class="veloria-woo-dashboard__order-left">
                                                        <div class="veloria-woo-dashboard__order-number">#<?php echo esc_html((string) $veloria_orderNumber); ?></div>
                                                        <div class="veloria-woo-dashboard__order-date">
                                                            <?php echo esc_html($veloria_orderDate ? wc_format_datetime($veloria_orderDate) : ''); ?>
                                                        </div>
                                                    </div>
                                                    <div class="veloria-woo-dashboard__order-right">
                                                        <div class="veloria-woo-dashboard__order-total"><?php echo wp_kses_post($veloria_orderTotal); ?></div>
                                                        <div class="veloria-woo-dashboard__order-status <?php echo esc_attr($veloria_statusSlug); ?>">
                                                            <?php echo esc_html($veloria_statusLabel); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="veloria-woo-dashboard__empty">
                                            <?php echo esc_html__('No recent orders yet.', 'veloria'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($veloria_showQuickActions && !empty($veloria_quickActionsItems)) : ?>
                                    <div class="veloria-woo-dashboard__card veloria-woo-dashboard__card--quick-actions">
                                        <div class="veloria-woo-dashboard__card-header">
                                            <div class="veloria-woo-dashboard__card-title"><?php echo esc_html($veloria_quickActionsTitle); ?></div>
                                        </div>
                                        <div class="veloria-woo-dashboard__actions">
                                            <?php foreach ($veloria_quickActionsItems as $veloria_qaItem) : ?>
                                                <?php
                                                if (!is_array($veloria_qaItem)) {
                                                    continue;
                                                }
                                                $veloria_qaLabel = isset($veloria_qaItem['item_label']) ? (string) $veloria_qaItem['item_label'] : '';
                                                if ($veloria_qaLabel === '') {
                                                    continue;
                                                }
                                                $veloria_qaIcon = isset($veloria_qaItem['item_icon']) && is_array($veloria_qaItem['item_icon']) ? $veloria_qaItem['item_icon'] : [];
                                                $veloria_qaAction = isset($veloria_qaItem['item_action']) ? (string) $veloria_qaItem['item_action'] : 'panel';
                                                $veloria_qaPanel = isset($veloria_qaItem['item_panel']) ? (string) $veloria_qaItem['item_panel'] : 'orders';
                                                $veloria_qaLink = isset($veloria_qaItem['item_link']) && is_array($veloria_qaItem['item_link']) ? $veloria_qaItem['item_link'] : [];
                                                $veloria_iconStyleParts = [];
                                                if (!empty($veloria_qaItem['item_icon_color'])) {
                                                    $veloria_iconStyleParts[] = 'color:' . $veloria_qaItem['item_icon_color'];
                                                }
                                                $veloria_iconStyleAttr = $veloria_iconStyleParts !== [] ? ' style="' . esc_attr(implode(';', $veloria_iconStyleParts)) . '"' : '';
                                                ?>
                                                <?php if ($veloria_qaAction === 'url') : ?>
                                                    <?php
                                                    $veloria_href = isset($veloria_qaLink['url']) ? (string) $veloria_qaLink['url'] : '';
                                                    if ($veloria_href === '') {
                                                        $veloria_href = '#';
                                                    }
                                                    $veloria_target = !empty($veloria_qaLink['is_external']) ? ' target="_blank"' : '';
                                                    $veloria_relParts = ['noopener', 'noreferrer'];
                                                    if (!empty($veloria_qaLink['nofollow'])) {
                                                        $veloria_relParts[] = 'nofollow';
                                                    }
                                                    $veloria_rel = !empty($veloria_qaLink['is_external']) || !empty($veloria_qaLink['nofollow'])
                                                        ? ' rel="' . esc_attr(implode(' ', $veloria_relParts)) . '"'
                                                        : '';
                                                    ?>
                                                    <a class="veloria-woo-dashboard__action" href="<?php echo esc_url($veloria_href); ?>"<?php echo $veloria_target . $veloria_rel; ?>>
                                                        <span class="veloria-woo-dashboard__action-icon"<?php echo $veloria_iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($veloria_qaIcon, 'fas fa-link'); ?>
                                                        </span>
                                                        <span class="veloria-woo-dashboard__action-label"><?php echo esc_html($veloria_qaLabel); ?></span>
                                                    </a>
                                                <?php else : ?>
                                                    <button type="button" class="veloria-woo-dashboard__action" data-wd-panel="<?php echo esc_attr($veloria_qaPanel); ?>">
                                                        <span class="veloria-woo-dashboard__action-icon"<?php echo $veloria_iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($veloria_qaIcon, 'fas fa-circle'); ?>
                                                        </span>
                                                        <span class="veloria-woo-dashboard__action-label"><?php echo esc_html($veloria_qaLabel); ?></span>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($veloria_showPanelOrders) : ?>
                        <div class="veloria-woo-dashboard__panel" data-wd-panel-id="orders" id="veloria-wd-panel-orders-<?php echo esc_attr($veloria_wd_uid); ?>" hidden>
                            <div class="veloria-woo-dashboard__screen">
                                <h2 class="veloria-woo-dashboard__screen-title"><?php echo esc_html__('Orders', 'veloria'); ?></h2>
                                <?php if (!empty($veloria_allOrders)) : ?>
                                    <div class="veloria-woo-dashboard__order-list">
                                        <?php foreach ($veloria_allOrders as $veloria_order) : ?>
                                            <?php
                                            if (!is_a($veloria_order, \WC_Order::class)) {
                                                continue;
                                            }
                                            $veloria_orderNumber = $veloria_order->get_order_number();
                                            $veloria_orderDate = $veloria_order->get_date_created();
                                            $veloria_orderTotal = $veloria_order->get_formatted_order_total();
                                            $veloria_statusSlug = 'wc-' . $veloria_order->get_status();
                                            $veloria_statusLabel = wc_get_order_status_name($veloria_order->get_status());
                                            ?>
                                            <div class="veloria-woo-dashboard__order-row">
                                                <div class="veloria-woo-dashboard__order-left">
                                                    <div class="veloria-woo-dashboard__order-number">#<?php echo esc_html((string) $veloria_orderNumber); ?></div>
                                                    <div class="veloria-woo-dashboard__order-date">
                                                        <?php echo esc_html($veloria_orderDate ? wc_format_datetime($veloria_orderDate) : ''); ?>
                                                    </div>
                                                </div>
                                                <div class="veloria-woo-dashboard__order-right">
                                                    <div class="veloria-woo-dashboard__order-total"><?php echo wp_kses_post($veloria_orderTotal); ?></div>
                                                    <div class="veloria-woo-dashboard__order-status <?php echo esc_attr($veloria_statusSlug); ?>">
                                                        <?php echo esc_html($veloria_statusLabel); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="veloria-woo-dashboard__empty"><?php echo esc_html__('No orders found.', 'veloria'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($veloria_showPanelWishlist) : ?>
                        <div class="veloria-woo-dashboard__panel" data-wd-panel-id="wishlist" id="veloria-wd-panel-wishlist-<?php echo esc_attr($veloria_wd_uid); ?>" hidden>
                            <div class="veloria-woo-dashboard__screen">
                                <h2 class="veloria-woo-dashboard__screen-title"><?php echo esc_html__('Wishlist', 'veloria'); ?></h2>
                                <div class="veloria-woo-dashboard__screen-body veloria-woo-dashboard__screen-body--wishlist">
                                    <?php
                                    if (shortcode_exists('yith_wcwl_wishlist')) {
                                        echo do_shortcode('[yith_wcwl_wishlist]');
                                    } elseif (shortcode_exists('ti_wishlistsview')) {
                                        echo do_shortcode('[ti_wishlistsview]');
                                    } else {
                                        echo '<div class="veloria-woo-dashboard__empty">';
                                        echo esc_html__('Connect a wishlist plugin (for example YITH Wishlist) to show items here.', 'veloria');
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($veloria_showPanelAccount) : ?>
                        <div class="veloria-woo-dashboard__panel" data-wd-panel-id="edit-account" id="veloria-wd-panel-edit-account-<?php echo esc_attr($veloria_wd_uid); ?>" hidden>
                            <div class="veloria-woo-dashboard__screen veloria-wd-account">
                                <h2 class="veloria-woo-dashboard__screen-title"><?php echo esc_html__('Account details', 'veloria'); ?></h2>
                                <?php
                                $veloria_partial = VELORIA_DIR . 'pro/templates/woo-dashboard/partials/account-details-form.php';
                                if (is_readable($veloria_partial)) {
                                    include $veloria_partial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($veloria_showPanelAddresses) : ?>
                        <div class="veloria-woo-dashboard__panel" data-wd-panel-id="edit-address" id="veloria-wd-panel-edit-address-<?php echo esc_attr($veloria_wd_uid); ?>" hidden>
                            <div class="veloria-woo-dashboard__screen veloria-wd-addresses-screen">
                                <h2 class="veloria-woo-dashboard__screen-title"><?php echo esc_html__('Addresses', 'veloria'); ?></h2>
                                <?php
                                $veloria_addrPartial = VELORIA_DIR . 'pro/templates/woo-dashboard/partials/addresses-panel.php';
                                if (is_readable($veloria_addrPartial)) {
                                    include $veloria_addrPartial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="veloria-woo-dashboard__modal" id="veloria-wd-logout-modal-<?php echo esc_attr($veloria_wd_uid); ?>" hidden role="dialog" aria-modal="true" aria-labelledby="veloria-wd-logout-title-<?php echo esc_attr($veloria_wd_uid); ?>">
                <button type="button" class="veloria-woo-dashboard__modal-backdrop" data-wd-action="logout-dismiss" aria-label="<?php echo esc_attr__('Close', 'veloria'); ?>"></button>
                <div class="veloria-woo-dashboard__modal-dialog">
                    <h2 class="veloria-woo-dashboard__modal-title" id="veloria-wd-logout-title-<?php echo esc_attr($veloria_wd_uid); ?>"><?php echo esc_html__('Log out?', 'veloria'); ?></h2>
                    <p class="veloria-woo-dashboard__modal-text"><?php echo esc_html__('You will need to sign in again to access your account.', 'veloria'); ?></p>
                    <div class="veloria-woo-dashboard__modal-actions">
                        <button type="button" class="veloria-woo-dashboard__modal-btn veloria-woo-dashboard__modal-btn--ghost" data-wd-action="logout-dismiss">
                            <?php echo esc_html__('Cancel', 'veloria'); ?>
                        </button>
                        <a class="veloria-woo-dashboard__modal-btn veloria-woo-dashboard__modal-btn--danger" href="<?php echo esc_url($veloria_logoutUrl); ?>">
                            <?php echo esc_html__('Log out', 'veloria'); ?>
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
