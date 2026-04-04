<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$showSidebar = ($settings['show_sidebar'] ?? 'yes') === 'yes';
$showStats = ($settings['show_stats'] ?? 'yes') === 'yes';
$showRecentOrders = ($settings['show_recent_orders'] ?? 'yes') === 'yes';
$showQuickActions = ($settings['show_quick_actions'] ?? 'yes') === 'yes';
$showPanelOrders = ($settings['show_panel_orders'] ?? 'yes') === 'yes';
$showPanelWishlist = ($settings['show_panel_wishlist'] ?? 'yes') === 'yes';
$showPanelAccount = ($settings['show_panel_account'] ?? 'yes') === 'yes';
$showPanelAddresses = ($settings['show_panel_addresses'] ?? 'yes') === 'yes';
$showLogoutNav = ($settings['show_logout_nav'] ?? 'yes') === 'yes';
$ordersLimit = isset($settings['orders_limit']) ? max(1, (int) $settings['orders_limit']) : 2;
$ordersListLimit = isset($settings['orders_list_limit']) ? max(1, min(50, (int) $settings['orders_list_limit'])) : 25;
$guestMessage = isset($settings['guest_message']) ? (string) $settings['guest_message'] : '';
$guestButtonLabel = isset($settings['guest_button_label']) ? (string) $settings['guest_button_label'] : '';
$statLabelOrders = isset($settings['stat_label_orders']) && $settings['stat_label_orders'] !== ''
    ? (string) $settings['stat_label_orders'] : __('Total Orders', 'elementrify');
$statLabelSpent = isset($settings['stat_label_spent']) && $settings['stat_label_spent'] !== ''
    ? (string) $settings['stat_label_spent'] : __('Total Spent', 'elementrify');
$statLabelWishlist = isset($settings['stat_label_wishlist']) && $settings['stat_label_wishlist'] !== ''
    ? (string) $settings['stat_label_wishlist'] : __('Wishlist Items', 'elementrify');
$statIconOrders = isset($settings['stat_icon_orders']) && is_array($settings['stat_icon_orders']) ? $settings['stat_icon_orders'] : [];
$statIconSpent = isset($settings['stat_icon_spent']) && is_array($settings['stat_icon_spent']) ? $settings['stat_icon_spent'] : [];
$statIconWishlist = isset($settings['stat_icon_wishlist']) && is_array($settings['stat_icon_wishlist']) ? $settings['stat_icon_wishlist'] : [];
$quickActionsTitle = isset($settings['quick_actions_title']) && $settings['quick_actions_title'] !== ''
    ? (string) $settings['quick_actions_title'] : __('Quick Actions', 'elementrify');
$quickActionsItems = isset($settings['quick_actions_items']) && is_array($settings['quick_actions_items'])
    ? $settings['quick_actions_items'] : [];
$layoutClass = 'elementrify-woo-dashboard__layout';
if (!$showSidebar) {
    $layoutClass .= ' elementrify-woo-dashboard__layout--no-sidebar';
}
$cardsRowClass = 'elementrify-woo-dashboard__cards';
if ($showRecentOrders xor $showQuickActions) {
    $cardsRowClass .= $showQuickActions && !$showRecentOrders
        ? ' elementrify-woo-dashboard__cards--quick-only'
        : ' elementrify-woo-dashboard__cards--recent-only';
}

$isWooActive = class_exists('WooCommerce');

?>
<div class="elementrify-woo-dashboard">
    <?php if (!$isWooActive) : ?>
        <div class="elementrify-woo-dashboard__notice">
            <?php echo esc_html__('WooCommerce is required for Woo Dashboard.', 'elementrify'); ?>
        </div>
    <?php elseif (!is_user_logged_in()) : ?>
        <div class="elementrify-woo-dashboard__notice">
            <div class="elementrify-woo-dashboard__notice-text"><?php echo esc_html($guestMessage); ?></div>
            <a class="elementrify-woo-dashboard__notice-btn" href="<?php echo esc_url(wp_login_url((string) get_permalink())); ?>">
                <?php echo esc_html($guestButtonLabel !== '' ? $guestButtonLabel : __('Log in', 'elementrify')); ?>
            </a>
        </div>
    <?php else : ?>
        <?php
        $userId = (int) get_current_user_id();
        $user = wp_get_current_user();
        if (!$user->exists()) {
            return;
        }
        $displayName = $user->display_name !== '' ? $user->display_name : __('Customer', 'elementrify');
        $avatarUrl = (string) get_avatar_url($userId, ['size' => 96]);
        $roleLabel = $this->get_user_role_label($user);

        $accountUrl = function_exists('wc_get_page_permalink') ? (string) wc_get_page_permalink('myaccount') : '';
        $ordersUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('orders') : '';
        $downloadsUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('downloads') : '';
        $editAccountUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-account') : '';
        $editAddressUrl = function_exists('wc_get_account_endpoint_url') ? (string) wc_get_account_endpoint_url('edit-address') : '';
        $logoutUrl = function_exists('wc_logout_url') ? (string) wc_logout_url($accountUrl !== '' ? $accountUrl : home_url('/')) : wp_logout_url();

        $totalOrders = function_exists('wc_get_customer_order_count') ? (int) wc_get_customer_order_count($userId) : 0;
        $totalSpent = function_exists('wc_get_customer_total_spent') ? (float) wc_get_customer_total_spent($userId) : 0.0;
        $wishlistCount = function_exists('yith_wcwl_count_products') ? (int) yith_wcwl_count_products() : 0;

        $wd_uid = $this->get_id();

        $recentOrders = [];
        $allOrders = [];
        if (function_exists('wc_get_orders')) {
            $recentOrders = wc_get_orders([
                'customer_id' => $userId,
                'limit'       => $ordersLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
            $allOrders = wc_get_orders([
                'customer_id' => $userId,
                'limit'       => $ordersListLimit,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'return'      => 'objects',
            ]);
        }
        ?>

        <div
            class="elementrify-woo-dashboard__shell"
            data-logout-url="<?php echo esc_url($logoutUrl); ?>"
            data-orders-url="<?php echo esc_url($ordersUrl); ?>"
            data-downloads-url="<?php echo esc_url($downloadsUrl); ?>"
            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
            data-account-nonce="<?php echo esc_attr(wp_create_nonce('elementrify_woo_dashboard_account')); ?>"
            data-address-nonce="<?php echo esc_attr(wp_create_nonce('elementrify_woo_dashboard_address')); ?>"
        >
            <div class="<?php echo esc_attr($layoutClass); ?>">
                <?php if ($showSidebar) : ?>
                    <aside class="elementrify-woo-dashboard__sidebar">
                        <div class="elementrify-woo-dashboard__profile">
                            <div class="elementrify-woo-dashboard__avatar">
                                <?php if ($avatarUrl !== '') : ?>
                                    <img src="<?php echo esc_url($avatarUrl); ?>" alt="<?php echo esc_attr($displayName); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="elementrify-woo-dashboard__profile-meta">
                                <div class="elementrify-woo-dashboard__name"><?php echo esc_html($displayName); ?></div>
                                <div class="elementrify-woo-dashboard__badge"><?php echo esc_html($roleLabel); ?></div>
                            </div>
                        </div>

                        <nav class="elementrify-woo-dashboard__nav" aria-label="<?php echo esc_attr__('Account menu', 'elementrify'); ?>">
                            <button type="button" class="elementrify-woo-dashboard__nav-item is-active" data-wd-panel="dashboard" aria-current="page">
                                <i class="fas fa-home" aria-hidden="true"></i><span><?php echo esc_html__('Dashboard', 'elementrify'); ?></span>
                            </button>
                            <?php if ($showPanelOrders) : ?>
                                <button type="button" class="elementrify-woo-dashboard__nav-item" data-wd-panel="orders">
                                    <i class="fas fa-shopping-bag" aria-hidden="true"></i><span><?php echo esc_html__('Orders', 'elementrify'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($showPanelWishlist) : ?>
                                <button type="button" class="elementrify-woo-dashboard__nav-item" data-wd-panel="wishlist">
                                    <i class="far fa-heart" aria-hidden="true"></i><span><?php echo esc_html__('Wishlist', 'elementrify'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($showPanelAccount) : ?>
                                <button type="button" class="elementrify-woo-dashboard__nav-item" data-wd-panel="edit-account">
                                    <i class="fas fa-user" aria-hidden="true"></i><span><?php echo esc_html__('Account Details', 'elementrify'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($showPanelAddresses) : ?>
                                <button type="button" class="elementrify-woo-dashboard__nav-item" data-wd-panel="edit-address">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i><span><?php echo esc_html__('Addresses', 'elementrify'); ?></span>
                                </button>
                            <?php endif; ?>
                            <?php if ($showLogoutNav) : ?>
                                <button type="button" class="elementrify-woo-dashboard__nav-item elementrify-woo-dashboard__nav-item--logout" data-wd-action="logout">
                                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span><?php echo esc_html__('Logout', 'elementrify'); ?></span>
                                </button>
                            <?php endif; ?>
                        </nav>
                    </aside>
                <?php endif; ?>

                <section class="elementrify-woo-dashboard__main">
                    <?php
                    if (function_exists('wc_print_notices')) {
                        wc_print_notices();
                    }
                    ?>

                    <div class="elementrify-woo-dashboard__panels">
                        <div class="elementrify-woo-dashboard__panel" data-wd-panel-id="dashboard" id="elementrify-wd-panel-dashboard-<?php echo esc_attr($wd_uid); ?>">
                            <?php if ($showStats) : ?>
                                <div class="elementrify-woo-dashboard__stats">
                                    <div class="elementrify-woo-dashboard__stat elementrify-woo-dashboard__stat--orders">
                                        <div class="elementrify-woo-dashboard__stat-label"><?php echo esc_html($statLabelOrders); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-value"><?php echo esc_html((string) $totalOrders); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($statIconOrders, 'fas fa-shopping-bag'); ?></div>
                                    </div>
                                    <div class="elementrify-woo-dashboard__stat elementrify-woo-dashboard__stat--spent">
                                        <div class="elementrify-woo-dashboard__stat-label"><?php echo esc_html($statLabelSpent); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-value"><?php echo wp_kses_post(wc_price($totalSpent)); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($statIconSpent, 'fas fa-dollar-sign'); ?></div>
                                    </div>
                                    <div class="elementrify-woo-dashboard__stat elementrify-woo-dashboard__stat--wishlist">
                                        <div class="elementrify-woo-dashboard__stat-label"><?php echo esc_html($statLabelWishlist); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-value"><?php echo esc_html((string) $wishlistCount); ?></div>
                                        <div class="elementrify-woo-dashboard__stat-icon"><?php $this->render_dashboard_icon($statIconWishlist, 'fas fa-heart'); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($showRecentOrders || ($showQuickActions && !empty($quickActionsItems))) : ?>
                            <div class="<?php echo esc_attr($cardsRowClass); ?>">
                                <?php if ($showRecentOrders) : ?>
                                <div class="elementrify-woo-dashboard__card">
                                    <div class="elementrify-woo-dashboard__card-header">
                                        <div class="elementrify-woo-dashboard__card-title"><?php echo esc_html__('Recent Orders', 'elementrify'); ?></div>
                                        <?php if ($showPanelOrders) : ?>
                                            <button type="button" class="elementrify-woo-dashboard__card-link elementrify-woo-dashboard__card-link--btn" data-wd-panel="orders">
                                                <?php echo esc_html__('View all', 'elementrify'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($recentOrders)) : ?>
                                        <div class="elementrify-woo-dashboard__order-list">
                                            <?php foreach ($recentOrders as $order) : ?>
                                                <?php
                                                if (!is_a($order, \WC_Order::class)) {
                                                    continue;
                                                }
                                                $orderNumber = $order->get_order_number();
                                                $orderDate = $order->get_date_created();
                                                $orderTotal = $order->get_formatted_order_total();
                                                $statusSlug = 'wc-' . $order->get_status();
                                                $statusLabel = wc_get_order_status_name($order->get_status());
                                                ?>
                                                <div class="elementrify-woo-dashboard__order-row">
                                                    <div class="elementrify-woo-dashboard__order-left">
                                                        <div class="elementrify-woo-dashboard__order-number">#<?php echo esc_html((string) $orderNumber); ?></div>
                                                        <div class="elementrify-woo-dashboard__order-date">
                                                            <?php echo esc_html($orderDate ? wc_format_datetime($orderDate) : ''); ?>
                                                        </div>
                                                    </div>
                                                    <div class="elementrify-woo-dashboard__order-right">
                                                        <div class="elementrify-woo-dashboard__order-total"><?php echo wp_kses_post($orderTotal); ?></div>
                                                        <div class="elementrify-woo-dashboard__order-status <?php echo esc_attr($statusSlug); ?>">
                                                            <?php echo esc_html($statusLabel); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="elementrify-woo-dashboard__empty">
                                            <?php echo esc_html__('No recent orders yet.', 'elementrify'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($showQuickActions && !empty($quickActionsItems)) : ?>
                                    <div class="elementrify-woo-dashboard__card elementrify-woo-dashboard__card--quick-actions">
                                        <div class="elementrify-woo-dashboard__card-header">
                                            <div class="elementrify-woo-dashboard__card-title"><?php echo esc_html($quickActionsTitle); ?></div>
                                        </div>
                                        <div class="elementrify-woo-dashboard__actions">
                                            <?php foreach ($quickActionsItems as $qaItem) : ?>
                                                <?php
                                                if (!is_array($qaItem)) {
                                                    continue;
                                                }
                                                $qaLabel = isset($qaItem['item_label']) ? (string) $qaItem['item_label'] : '';
                                                if ($qaLabel === '') {
                                                    continue;
                                                }
                                                $qaIcon = isset($qaItem['item_icon']) && is_array($qaItem['item_icon']) ? $qaItem['item_icon'] : [];
                                                $qaAction = isset($qaItem['item_action']) ? (string) $qaItem['item_action'] : 'panel';
                                                $qaPanel = isset($qaItem['item_panel']) ? (string) $qaItem['item_panel'] : 'orders';
                                                $qaLink = isset($qaItem['item_link']) && is_array($qaItem['item_link']) ? $qaItem['item_link'] : [];
                                                $iconStyleParts = [];
                                                if (!empty($qaItem['item_icon_color'])) {
                                                    $iconStyleParts[] = 'color:' . $qaItem['item_icon_color'];
                                                }
                                                $iconStyleAttr = $iconStyleParts !== [] ? ' style="' . esc_attr(implode(';', $iconStyleParts)) . '"' : '';
                                                ?>
                                                <?php if ($qaAction === 'url') : ?>
                                                    <?php
                                                    $href = isset($qaLink['url']) ? (string) $qaLink['url'] : '';
                                                    if ($href === '') {
                                                        $href = '#';
                                                    }
                                                    $target = !empty($qaLink['is_external']) ? ' target="_blank"' : '';
                                                    $relParts = ['noopener', 'noreferrer'];
                                                    if (!empty($qaLink['nofollow'])) {
                                                        $relParts[] = 'nofollow';
                                                    }
                                                    $rel = !empty($qaLink['is_external']) || !empty($qaLink['nofollow'])
                                                        ? ' rel="' . esc_attr(implode(' ', $relParts)) . '"'
                                                        : '';
                                                    ?>
                                                    <a class="elementrify-woo-dashboard__action" href="<?php echo esc_url($href); ?>"<?php echo $target . $rel; ?>>
                                                        <span class="elementrify-woo-dashboard__action-icon"<?php echo $iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($qaIcon, 'fas fa-link'); ?>
                                                        </span>
                                                        <span class="elementrify-woo-dashboard__action-label"><?php echo esc_html($qaLabel); ?></span>
                                                    </a>
                                                <?php else : ?>
                                                    <button type="button" class="elementrify-woo-dashboard__action" data-wd-panel="<?php echo esc_attr($qaPanel); ?>">
                                                        <span class="elementrify-woo-dashboard__action-icon"<?php echo $iconStyleAttr; ?>>
                                                            <?php $this->render_dashboard_icon($qaIcon, 'fas fa-circle'); ?>
                                                        </span>
                                                        <span class="elementrify-woo-dashboard__action-label"><?php echo esc_html($qaLabel); ?></span>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($showPanelOrders) : ?>
                        <div class="elementrify-woo-dashboard__panel" data-wd-panel-id="orders" id="elementrify-wd-panel-orders-<?php echo esc_attr($wd_uid); ?>" hidden>
                            <div class="elementrify-woo-dashboard__screen">
                                <h2 class="elementrify-woo-dashboard__screen-title"><?php echo esc_html__('Orders', 'elementrify'); ?></h2>
                                <?php if (!empty($allOrders)) : ?>
                                    <div class="elementrify-woo-dashboard__order-list">
                                        <?php foreach ($allOrders as $order) : ?>
                                            <?php
                                            if (!is_a($order, \WC_Order::class)) {
                                                continue;
                                            }
                                            $orderNumber = $order->get_order_number();
                                            $orderDate = $order->get_date_created();
                                            $orderTotal = $order->get_formatted_order_total();
                                            $statusSlug = 'wc-' . $order->get_status();
                                            $statusLabel = wc_get_order_status_name($order->get_status());
                                            ?>
                                            <div class="elementrify-woo-dashboard__order-row">
                                                <div class="elementrify-woo-dashboard__order-left">
                                                    <div class="elementrify-woo-dashboard__order-number">#<?php echo esc_html((string) $orderNumber); ?></div>
                                                    <div class="elementrify-woo-dashboard__order-date">
                                                        <?php echo esc_html($orderDate ? wc_format_datetime($orderDate) : ''); ?>
                                                    </div>
                                                </div>
                                                <div class="elementrify-woo-dashboard__order-right">
                                                    <div class="elementrify-woo-dashboard__order-total"><?php echo wp_kses_post($orderTotal); ?></div>
                                                    <div class="elementrify-woo-dashboard__order-status <?php echo esc_attr($statusSlug); ?>">
                                                        <?php echo esc_html($statusLabel); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="elementrify-woo-dashboard__empty"><?php echo esc_html__('No orders found.', 'elementrify'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($showPanelWishlist) : ?>
                        <div class="elementrify-woo-dashboard__panel" data-wd-panel-id="wishlist" id="elementrify-wd-panel-wishlist-<?php echo esc_attr($wd_uid); ?>" hidden>
                            <div class="elementrify-woo-dashboard__screen">
                                <h2 class="elementrify-woo-dashboard__screen-title"><?php echo esc_html__('Wishlist', 'elementrify'); ?></h2>
                                <div class="elementrify-woo-dashboard__screen-body elementrify-woo-dashboard__screen-body--wishlist">
                                    <?php
                                    if (shortcode_exists('yith_wcwl_wishlist')) {
                                        echo do_shortcode('[yith_wcwl_wishlist]');
                                    } elseif (shortcode_exists('ti_wishlistsview')) {
                                        echo do_shortcode('[ti_wishlistsview]');
                                    } else {
                                        echo '<div class="elementrify-woo-dashboard__empty">';
                                        echo esc_html__('Connect a wishlist plugin (for example YITH Wishlist) to show items here.', 'elementrify');
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($showPanelAccount) : ?>
                        <div class="elementrify-woo-dashboard__panel" data-wd-panel-id="edit-account" id="elementrify-wd-panel-edit-account-<?php echo esc_attr($wd_uid); ?>" hidden>
                            <div class="elementrify-woo-dashboard__screen elementrify-wd-account">
                                <h2 class="elementrify-woo-dashboard__screen-title"><?php echo esc_html__('Account details', 'elementrify'); ?></h2>
                                <?php
                                $partial = ELEMENTRIFY_DIR . 'templates/woo-dashboard/partials/account-details-form.php';
                                if (is_readable($partial)) {
                                    include $partial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($showPanelAddresses) : ?>
                        <div class="elementrify-woo-dashboard__panel" data-wd-panel-id="edit-address" id="elementrify-wd-panel-edit-address-<?php echo esc_attr($wd_uid); ?>" hidden>
                            <div class="elementrify-woo-dashboard__screen elementrify-wd-addresses-screen">
                                <h2 class="elementrify-woo-dashboard__screen-title"><?php echo esc_html__('Addresses', 'elementrify'); ?></h2>
                                <?php
                                $addrPartial = ELEMENTRIFY_DIR . 'templates/woo-dashboard/partials/addresses-panel.php';
                                if (is_readable($addrPartial)) {
                                    include $addrPartial;
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <div class="elementrify-woo-dashboard__modal" id="elementrify-wd-logout-modal-<?php echo esc_attr($wd_uid); ?>" hidden role="dialog" aria-modal="true" aria-labelledby="elementrify-wd-logout-title-<?php echo esc_attr($wd_uid); ?>">
                <button type="button" class="elementrify-woo-dashboard__modal-backdrop" data-wd-action="logout-dismiss" aria-label="<?php echo esc_attr__('Close', 'elementrify'); ?>"></button>
                <div class="elementrify-woo-dashboard__modal-dialog">
                    <h2 class="elementrify-woo-dashboard__modal-title" id="elementrify-wd-logout-title-<?php echo esc_attr($wd_uid); ?>"><?php echo esc_html__('Log out?', 'elementrify'); ?></h2>
                    <p class="elementrify-woo-dashboard__modal-text"><?php echo esc_html__('You will need to sign in again to access your account.', 'elementrify'); ?></p>
                    <div class="elementrify-woo-dashboard__modal-actions">
                        <button type="button" class="elementrify-woo-dashboard__modal-btn elementrify-woo-dashboard__modal-btn--ghost" data-wd-action="logout-dismiss">
                            <?php echo esc_html__('Cancel', 'elementrify'); ?>
                        </button>
                        <a class="elementrify-woo-dashboard__modal-btn elementrify-woo-dashboard__modal-btn--danger" href="<?php echo esc_url($logoutUrl); ?>">
                            <?php echo esc_html__('Log out', 'elementrify'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
