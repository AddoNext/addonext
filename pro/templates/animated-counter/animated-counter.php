<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_items   = isset($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_columns = isset($addonext_settings['columns']) ? max(1, (int) $addonext_settings['columns']) : 4;

if (empty($addonext_items)) {
    return;
}

$addonext_grid_style = 'display:grid;grid-template-columns:repeat(' . $addonext_columns . ',1fr);gap:24px;width:100%;';
$addonext_item_style = 'display:flex;flex-direction:column;align-items:center;text-align:center;';

$this->add_render_attribute('inner', 'class', 'addonext-animated-counter__inner');
$this->add_render_attribute('grid', 'class', 'addonext-animated-counter__grid');
$this->add_render_attribute('grid', 'style', $addonext_grid_style);
?>
<div class="addonext-animated-counter">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div <?php $this->print_render_attribute_string('grid'); ?>>
        <?php foreach ($addonext_items as $addonext_item) :
            $addonext_item_id = $addonext_item['_id'] ?? '';
            $addonext_number = isset($addonext_item['number']) ? (int) $addonext_item['number'] : 0;
            $addonext_prefix = isset($addonext_item['prefix']) ? $addonext_item['prefix'] : '';
            $addonext_suffix = isset($addonext_item['suffix']) ? $addonext_item['suffix'] : '';
            $addonext_title  = isset($addonext_item['title']) ? $addonext_item['title'] : '';
            $addonext_subtitle = isset($addonext_item['subtitle']) ? $addonext_item['subtitle'] : '';
        ?>
            <div class="addonext-animated-counter__item elementor-repeater-item-<?php echo esc_attr($addonext_item_id); ?>" style="<?php echo esc_attr($addonext_item_style); ?>">
                <span class="addonext-animated-counter__number" style="display:block;" data-to="<?php echo esc_attr($addonext_number); ?>" data-prefix="<?php echo esc_attr($addonext_prefix); ?>" data-suffix="<?php echo esc_attr($addonext_suffix); ?>"><?php echo esc_html($addonext_prefix . '0' . $addonext_suffix); ?></span>
                <?php if ($addonext_title !== '') : ?><span class="addonext-animated-counter__title" style="display:block;width:100%;"><?php echo esc_html($addonext_title); ?></span><?php endif; ?>
                <?php if ($addonext_subtitle !== '') : ?><p class="addonext-animated-counter__subtitle" style="display:block;width:100%;margin:0;"><?php echo esc_html($addonext_subtitle); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
    }
}
