<?php
/**
 * USP Items widget template.
 *
 * @var \Elementrify\Widgets\UspItems\UspItems $this
 */

defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

$settings  = $this->get_settings_for_display();
$items     = isset($settings['items']) && is_array($settings['items']) ? $settings['items'] : [];
$title_tag = isset($settings['title_tag']) ? sanitize_key((string) $settings['title_tag']) : 'h3';
$allowed   = ['h2', 'h3', 'h4', 'div', 'p'];
if (!in_array($title_tag, $allowed, true)) {
    $title_tag = 'h3';
}

if ($items === []) {
    return;
}

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="elementrify-usp-items__grid">
        <?php
        foreach ($items as $index => $item) {
            $item_id = isset($item['_id']) ? (string) $item['_id'] : (string) $index;
            $surface_key = 'usp_surface_' . $index;
            $this->add_render_attribute($surface_key, 'class', 'elementrify-usp-item__surface');

            $circle_bg  = !empty($item['circle_bg']) ? (string) $item['circle_bg'] : '#e0e7ff';
            $icon_color = !empty($item['icon_color']) ? (string) $item['icon_color'] : '#2563eb';
            $title      = isset($item['item_title']) ? (string) $item['item_title'] : '';
            $subtitle   = isset($item['item_subtitle']) ? (string) $item['item_subtitle'] : '';
            $link       = isset($item['item_link']) && is_array($item['item_link']) ? $item['item_link'] : [];
            $has_url    = !empty($link['url']);

            $article_class = 'elementrify-usp-item elementor-repeater-item-' . esc_attr($item_id);
            ?>
            <article class="<?php echo esc_attr($article_class); ?>">
                <?php if ($has_url) : ?>
                    <?php
                    $link_key = 'usp_link_' . $index;
                    $this->add_render_attribute($link_key, 'class', 'elementrify-usp-item__link');
                    $this->add_link_attributes($link_key, $link);
                    ?>
                    <a <?php $this->print_render_attribute_string($link_key); ?>>
                        <div <?php $this->print_render_attribute_string($surface_key); ?>>
                            <span class="elementrify-usp-item__icon-ring" style="background-color: <?php echo esc_attr($circle_bg); ?>;">
                                <span class="elementrify-usp-item__icon" style="color: <?php echo esc_attr($icon_color); ?>;">
                                    <?php
                                    if (!empty($item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($title_tag); ?> class="elementrify-usp-item__title"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
                            <p class="elementrify-usp-item__subtitle"><?php echo esc_html($subtitle); ?></p>
                        </div>
                    </a>
                <?php else : ?>
                    <div class="elementrify-usp-item__link elementrify-usp-item__link--static" role="group">
                        <div <?php $this->print_render_attribute_string($surface_key); ?>>
                            <span class="elementrify-usp-item__icon-ring" style="background-color: <?php echo esc_attr($circle_bg); ?>;">
                                <span class="elementrify-usp-item__icon" style="color: <?php echo esc_attr($icon_color); ?>;">
                                    <?php
                                    if (!empty($item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($title_tag); ?> class="elementrify-usp-item__title"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
                            <p class="elementrify-usp-item__subtitle"><?php echo esc_html($subtitle); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
            <?php
        }
        ?>
    </div>
</div>
