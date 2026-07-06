<?php
/**
 * USP Items widget template.
 *
 * @var \Addonext\Widgets\UspItems\UspItems $this
 */

defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

$addonext_settings  = $this->get_settings_for_display();
$addonext_items     = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_title_tag = isset($addonext_settings['title_tag']) ? sanitize_key((string) $addonext_settings['title_tag']) : 'h3';
$addonext_allowed   = ['h2', 'h3', 'h4', 'div', 'p'];
if (!in_array($addonext_title_tag, $addonext_allowed, true)) {
    $addonext_title_tag = 'h3';
}

if ($addonext_items === []) {
    return;
}

?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <div class="addonext-usp-items__grid">
        <?php
        foreach ($addonext_items as $addonext_index => $addonext_item) {
            $addonext_item_id = isset($addonext_item['_id']) ? (string) $addonext_item['_id'] : (string) $addonext_index;
            $addonext_surface_key = 'usp_surface_' . $addonext_index;
            $this->add_render_attribute($addonext_surface_key, 'class', 'addonext-usp-item__surface');

            $addonext_circle_bg  = !empty($addonext_item['circle_bg']) ? (string) $addonext_item['circle_bg'] : '#e0e7ff';
            $addonext_icon_color = !empty($addonext_item['icon_color']) ? (string) $addonext_item['icon_color'] : '#2563eb';
            $addonext_title      = isset($addonext_item['item_title']) ? (string) $addonext_item['item_title'] : '';
            $addonext_subtitle   = isset($addonext_item['item_subtitle']) ? (string) $addonext_item['item_subtitle'] : '';
            $addonext_link       = isset($addonext_item['item_link']) && is_array($addonext_item['item_link']) ? $addonext_item['item_link'] : [];
            $addonext_has_url    = !empty($addonext_link['url']);

            $addonext_article_class = 'addonext-usp-item elementor-repeater-item-' . esc_attr($addonext_item_id);
            ?>
            <article class="<?php echo esc_attr($addonext_article_class); ?>">
                <?php if ($addonext_has_url) : ?>
                    <?php
                    $addonext_link_key = 'usp_link_' . $addonext_index;
                    $this->add_render_attribute($addonext_link_key, 'class', 'addonext-usp-item__link');
                    $this->add_link_attributes($addonext_link_key, $addonext_link);
                    ?>
                    <a <?php $this->print_render_attribute_string($addonext_link_key); ?>>
                        <div <?php $this->print_render_attribute_string($addonext_surface_key); ?>>
                            <span class="addonext-usp-item__icon-ring" style="background-color: <?php echo esc_attr($addonext_circle_bg); ?>;">
                                <span class="addonext-usp-item__icon" style="color: <?php echo esc_attr($addonext_icon_color); ?>;">
                                    <?php
                                    if (!empty($addonext_item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($addonext_item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-usp-item__title"><?php echo esc_html($addonext_title); ?></<?php echo esc_attr($addonext_title_tag); ?>>
                            <p class="addonext-usp-item__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                        </div>
                    </a>
                <?php else : ?>
                    <div class="addonext-usp-item__link addonext-usp-item__link--static" role="group">
                        <div <?php $this->print_render_attribute_string($addonext_surface_key); ?>>
                            <span class="addonext-usp-item__icon-ring" style="background-color: <?php echo esc_attr($addonext_circle_bg); ?>;">
                                <span class="addonext-usp-item__icon" style="color: <?php echo esc_attr($addonext_icon_color); ?>;">
                                    <?php
                                    if (!empty($addonext_item['item_icon']['value'])) {
                                        Icons_Manager::render_icon($addonext_item['item_icon'], ['aria-hidden' => 'true']);
                                    }
                                    ?>
                                </span>
                            </span>
                            <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-usp-item__title"><?php echo esc_html($addonext_title); ?></<?php echo esc_attr($addonext_title_tag); ?>>
                            <p class="addonext-usp-item__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
            <?php
        }
        ?>
    </div>
</div>
