<?php
namespace Elementrify\Widgets\NavigationMenu;

defined('ABSPATH') || exit;

/**
 * Walker for Navigation Menu widget markup and submenu structure.
 */
class Walker_Elementrify_Nav_Menu extends \Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (is_object($args) && isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        $classes = ['sub-menu', 'elementrify-nav-menu__sub'];
        if ($depth > 0) {
            $classes[] = 'elementrify-nav-menu__sub--nested';
        }
        if (is_object($args) && !empty($args->submenu_class)) {
            foreach (preg_split('/\s+/', $args->submenu_class) as $extra_class) {
                if ($extra_class !== '' && !in_array($extra_class, $classes, true)) {
                    $classes[] = $extra_class;
                }
            }
        }

        $output .= "{$n}{$indent}<ul class=\"" . esc_attr(implode(' ', $classes)) . "\">{$n}";
    }
}
