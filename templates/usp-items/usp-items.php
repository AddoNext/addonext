<?php
/**
 * USP Items widget template.
 *
 * @var \Veloria\Widgets\UspItems\UspItems $this
 */

defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

$veloria_settings  = $this->get_settings_for_display();
$veloria_items     = isset($veloria_settings['items']) && is_array($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_title_tag = isset($veloria_settings['title_tag']) ? sanitize_key((string) $veloria_settings['title_tag']) : 'h3';
$veloria_allowed   = ['h2', 'h3', 'h4', 'div', 'p'];
if (!in_array($veloria_title_tag, $veloria_allowed, true)) {
    $veloria_title_tag = 'h3';
}

if ($veloria_items === []) {
    return;
}

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="veloria-usp-items__grid">
        <?php
        foreach ($veloria_items as $veloria_index => $veloria_item) {
            $veloria_item_id = isset($veloria_item['_id']) ? (string) $veloria_item['_id'] : (string) $veloria_index;
            $veloria_surface_key = 'usp_surface_' . $veloria_index;
            $this->add_render_attribute($veloria_surface_key, 'class', 'veloria-usp-item__surface');

            $veloria_circle_bg  = !empty($veloria_item['circle_bg']) ? (string) $veloria_item['circle_bg'] : '#e0e7ff';
            $veloria_icon_color = !empty($veloria_item['icon_color']) ? (string) $veloria_item['icon_color'] : '#2563eb';
            $veloria_title      = isset($veloria_item['item_title']) ? (string) $veloria_item['item_title'] : '';
            $veloria_subtitle   = isset($veloria_item['item_subtitle']) ? (string) $veloria_item['item_subtitle'] : '';
            $veloria_link       = isset($veloria_item['item_link']) && is_array($veloria_item['item_link']) ? $veloria_item['item_link'] : [];
            $veloria_has_url    = !empty($veloria_link['url']);

            $veloria_article_class = 'veloria-usp-item elementor-repeater-item-' . esc_attr($veloria_item_id);
            ?>
            <article class="<?php echo esc_attr($veloria_article_class); ?>">
                <?php if ($veloria_has_url) : ?>
                    <?php
                    $veloria_link_key = 'usp_link_' . $veloria_index;
                    $this->add_render_attribute($veloria_link_key, 'class', 'veloria-usp-item__link');
                    $this->add_link_attributes($veloria_link_key, $veloria_link);
                    ?>
                    <a <?php $this->print_render_attribute_string($veloria_link_key); ?>>
                        <div <?php $this->print_render_attribute_string($veloria_surface_key); ?>>
                            <span class="veloria-usp-item__icon-ring" style="background-color: <?php echo esc_attr($veloria_circle_bg); ?>;">
                                <span class="veloria-usp-item__icon" style="color: <?php echo esc_attr($veloria_icon_color); ?>;">
                                    <?php
                                    if (!empty($veloria_item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($veloria_item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-usp-item__title"><?php echo esc_html($veloria_title); ?></<?php echo esc_attr($veloria_title_tag); ?>>
                            <p class="veloria-usp-item__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                        </div>
                    </a>
                <?php else : ?>
                    <div class="veloria-usp-item__link veloria-usp-item__link--static" role="group">
                        <div <?php $this->print_render_attribute_string($veloria_surface_key); ?>>
                            <span class="veloria-usp-item__icon-ring" style="background-color: <?php echo esc_attr($veloria_circle_bg); ?>;">
                                <span class="veloria-usp-item__icon" style="color: <?php echo esc_attr($veloria_icon_color); ?>;">
                                    <?php
                                    if (!empty($veloria_item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($veloria_item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-usp-item__title"><?php echo esc_html($veloria_title); ?></<?php echo esc_attr($veloria_title_tag); ?>>
                            <p class="veloria-usp-item__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
            <?php
        }
        ?>
    </div>
</div>
