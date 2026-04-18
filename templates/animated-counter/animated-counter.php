<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$items   = isset($settings['items']) ? $settings['items'] : [];
$columns = isset($settings['columns']) ? max(1, (int) $settings['columns']) : 4;

if (empty($items)) {
    return;
}

$grid_style = 'display:grid;grid-template-columns:repeat(' . $columns . ',1fr);gap:24px;width:100%;';
$item_style = 'display:flex;flex-direction:column;align-items:center;text-align:center;';

$this->add_render_attribute('inner', 'class', 'elementrify-animated-counter__inner');
$this->add_render_attribute('grid', 'class', 'elementrify-animated-counter__grid');
$this->add_render_attribute('grid', 'style', $grid_style);
?>
<div class="elementrify-animated-counter">
    <div <?php $this->print_render_attribute_string('inner'); ?>>
        <div <?php $this->print_render_attribute_string('grid'); ?>>
        <?php foreach ($items as $item) :
            $item_id = $item['_id'] ?? '';
            $number = isset($item['number']) ? (int) $item['number'] : 0;
            $prefix = isset($item['prefix']) ? $item['prefix'] : '';
            $suffix = isset($item['suffix']) ? $item['suffix'] : '';
            $title  = isset($item['title']) ? $item['title'] : '';
            $subtitle = isset($item['subtitle']) ? $item['subtitle'] : '';
        ?>
            <div class="elementrify-animated-counter__item elementor-repeater-item-<?php echo esc_attr($item_id); ?>" style="<?php echo esc_attr($item_style); ?>">
                <span class="elementrify-animated-counter__number" style="display:block;" data-to="<?php echo esc_attr($number); ?>" data-prefix="<?php echo esc_attr($prefix); ?>" data-suffix="<?php echo esc_attr($suffix); ?>"><?php echo esc_html($prefix . '0' . $suffix); ?></span>
                <?php if ($title !== '') : ?><span class="elementrify-animated-counter__title" style="display:block;width:100%;"><?php echo esc_html($title); ?></span><?php endif; ?>
                <?php if ($subtitle !== '') : ?><p class="elementrify-animated-counter__subtitle" style="display:block;width:100%;margin:0;"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
    }
}
