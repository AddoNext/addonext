<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_items   = isset($veloria_settings['items']) ? $veloria_settings['items'] : [];
$veloria_columns = isset($veloria_settings['columns']) ? max(1, (int) $veloria_settings['columns']) : 4;

if (empty($veloria_items)) {
    return;
}

$veloria_grid_style = 'display:grid;grid-template-columns:repeat(' . $veloria_columns . ',1fr);gap:24px;width:100%;';
$veloria_item_style = 'display:flex;flex-direction:column;align-items:center;text-align:center;';

$this->add_render_attribute('inner', 'class', 'veloria-animated-counter__inner');
$this->add_render_attribute('grid', 'class', 'veloria-animated-counter__grid');
$this->add_render_attribute('grid', 'style', $veloria_grid_style);
?>
<div class="veloria-animated-counter">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div <?php $this->print_render_attribute_string('grid'); ?>>
        <?php foreach ($veloria_items as $veloria_item) :
            $veloria_item_id = $veloria_item['_id'] ?? '';
            $veloria_number = isset($veloria_item['number']) ? (int) $veloria_item['number'] : 0;
            $veloria_prefix = isset($veloria_item['prefix']) ? $veloria_item['prefix'] : '';
            $veloria_suffix = isset($veloria_item['suffix']) ? $veloria_item['suffix'] : '';
            $veloria_title  = isset($veloria_item['title']) ? $veloria_item['title'] : '';
            $veloria_subtitle = isset($veloria_item['subtitle']) ? $veloria_item['subtitle'] : '';
        ?>
            <div class="veloria-animated-counter__item elementor-repeater-item-<?php echo esc_attr($veloria_item_id); ?>" style="<?php echo esc_attr($veloria_item_style); ?>">
                <span class="veloria-animated-counter__number" style="display:block;" data-to="<?php echo esc_attr($veloria_number); ?>" data-prefix="<?php echo esc_attr($veloria_prefix); ?>" data-suffix="<?php echo esc_attr($veloria_suffix); ?>"><?php echo esc_html($veloria_prefix . '0' . $veloria_suffix); ?></span>
                <?php if ($veloria_title !== '') : ?><span class="veloria-animated-counter__title" style="display:block;width:100%;"><?php echo esc_html($veloria_title); ?></span><?php endif; ?>
                <?php if ($veloria_subtitle !== '') : ?><p class="veloria-animated-counter__subtitle" style="display:block;width:100%;margin:0;"><?php echo esc_html($veloria_subtitle); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
    }
}
