<?php
/**
 * Navigation Menu widget template.
 *
 * @var \Addonext\Widgets\NavigationMenu\NavigationMenu $this
 */

defined('ABSPATH') || exit;

$addonext_menu_id = isset($addonext_menu_id) ? (int) $addonext_menu_id : 0;

if ($addonext_menu_id <= 0) {
    if (\Addonext\Widgets\NavigationMenu\NavigationMenu::is_elementor_edit_canvas()) {
        echo '<p class="addonext-nav-menu__placeholder">' . esc_html__('Select a WordPress menu in the widget settings.', 'addonext') . '</p>';
    }

    return;
}

?>
<nav <?php $this->print_render_attribute_string('nav'); ?>>
    <div class="addonext-nav-menu__inner">
        <?php
        wp_nav_menu(
            [
                'menu'           => $addonext_menu_id,
                'container'      => false,
                'menu_id'        => 'addonext-nav-' . $this->get_id(),
                'menu_class'     => 'menu addonext-nav-menu__root',
                'fallback_cb'    => false,
                'depth'          => 0,
                'echo'           => true,
                'walker'         => new \Addonext\Widgets\NavigationMenu\Walker_Addonext_Nav_Menu(),
            ]
        );
        ?>
    </div>
</nav>
