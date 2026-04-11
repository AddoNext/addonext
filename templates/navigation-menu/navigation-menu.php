<?php
/**
 * Navigation Menu widget template.
 *
 * @var \Elementrify\Widgets\NavigationMenu\NavigationMenu $this
 */

defined('ABSPATH') || exit;

$menu_id = isset($menu_id) ? (int) $menu_id : 0;

if ($menu_id <= 0) {
    if (\Elementrify\Widgets\NavigationMenu\NavigationMenu::is_elementor_edit_canvas()) {
        echo '<p class="elementrify-nav-menu__placeholder">' . esc_html__('Select a WordPress menu in the widget settings.', 'elementrify') . '</p>';
    }

    return;
}

?>
<nav <?php $this->print_render_attribute_string('nav'); ?>>
    <div class="elementrify-nav-menu__inner">
        <?php
        wp_nav_menu(
            [
                'menu'           => $menu_id,
                'container'      => false,
                'menu_id'        => 'elementrify-nav-' . $this->get_id(),
                'menu_class'     => 'menu elementrify-nav-menu__root',
                'fallback_cb'    => false,
                'depth'          => 0,
                'echo'           => true,
                'walker'         => new \Elementrify\Widgets\NavigationMenu\Walker_Elementrify_Nav_Menu(),
            ]
        );
        ?>
    </div>
</nav>
