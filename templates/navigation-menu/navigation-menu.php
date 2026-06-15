<?php
/**
 * Navigation Menu widget template.
 *
 * @var \Veloria\Widgets\NavigationMenu\NavigationMenu $this
 */

defined('ABSPATH') || exit;

$menu_id = isset($menu_id) ? (int) $menu_id : 0;

if ($menu_id <= 0) {
    if (\Veloria\Widgets\NavigationMenu\NavigationMenu::is_elementor_edit_canvas()) {
        echo '<p class="veloria-nav-menu__placeholder">' . esc_html__('Select a WordPress menu in the widget settings.', 'veloria') . '</p>';
    }

    return;
}

?>
<nav <?php $this->print_render_attribute_string('nav'); ?>>
    <div class="veloria-nav-menu__inner">
        <?php
        wp_nav_menu(
            [
                'menu'           => $menu_id,
                'container'      => false,
                'menu_id'        => 'veloria-nav-' . $this->get_id(),
                'menu_class'     => 'menu veloria-nav-menu__root',
                'fallback_cb'    => false,
                'depth'          => 0,
                'echo'           => true,
                'walker'         => new \Veloria\Widgets\NavigationMenu\Walker_Veloria_Nav_Menu(),
            ]
        );
        ?>
    </div>
</nav>
