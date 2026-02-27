<?php
defined('ABSPATH') || exit;

$settings   = $this->get_settings_for_display();
$tabs       = isset($settings['tabs']) ? $settings['tabs'] : [];
$first_open = isset($settings['first_expanded']) && $settings['first_expanded'] === 'yes';
$allow_multiple = isset($settings['allow_multiple']) && $settings['allow_multiple'] === 'yes';
$title_tag  = isset($settings['title_html_tag']) ? $settings['title_html_tag'] : 'div';
$title_tag  = \Elementor\Utils::validate_html_tag($title_tag);
$has_icon   = !empty($settings['selected_icon']['value']);

if (empty($tabs)) {
    return;
}

$accordion_id = 'elementrify-accordion-' . $this->get_id();
$this->add_render_attribute('wrapper', 'id', $accordion_id);
$this->add_render_attribute('wrapper', 'class', 'elementrify-accordion');
$this->add_render_attribute('wrapper', 'data-allow-multiple', $allow_multiple ? '1' : '0');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <?php foreach ($tabs as $index => $item) :
        $is_open     = $first_open && $index === 0;
        $item_class  = 'elementrify-accordion__item' . ($is_open ? ' is-open' : '');
        $item_key    = 'accordion_item_' . $index;
        $content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

        $this->add_render_attribute($item_key, 'class', $item_class);
        $this->add_render_attribute($content_key, 'class', 'elementrify-accordion__content');
        $this->add_inline_editing_attributes($content_key, 'advanced');
    ?>
    <div <?php $this->print_render_attribute_string($item_key); ?>>
        <<?php echo $title_tag; ?> class="elementrify-accordion__header" role="button" tabindex="0">
            <?php if ($has_icon) : ?>
                <span class="elementrify-accordion__icon elementrify-accordion__icon--closed" aria-hidden="true">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                </span>
                <span class="elementrify-accordion__icon elementrify-accordion__icon--open" aria-hidden="true">
                    <?php
                    $active_icon = isset($settings['selected_active_icon']['value']) ? $settings['selected_active_icon'] : $settings['selected_icon'];
                    \Elementor\Icons_Manager::render_icon($active_icon, ['aria-hidden' => 'true']);
                    ?>
                </span>
            <?php endif; ?>
            <span class="elementrify-accordion__title"><?php echo wp_kses_post($item['tab_title']); ?></span>
        </<?php echo $title_tag; ?>>
        <div <?php $this->print_render_attribute_string($content_key); ?>>
            <?php $this->print_text_editor($item['tab_content']); ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script>
(function () {
  var root = document.getElementById('<?php echo esc_js($accordion_id); ?>');
  if (!root) return;
  function handleClick(e) {
    var header = e.target.closest('.elementrify-accordion__header');
    if (!header) return;
    e.preventDefault();
    e.stopPropagation();
    var item = header.closest('.elementrify-accordion__item');
    if (!item) return;
    var allowMultiple = root.getAttribute('data-allow-multiple') === '1';
    var isOpen = item.classList.contains('is-open');
    if (!allowMultiple) {
      var items = root.querySelectorAll('.elementrify-accordion__item');
      for (var i = 0; i < items.length; i++) items[i].classList.remove('is-open');
    }
    if (isOpen) item.classList.remove('is-open');
    else item.classList.add('is-open');
  }
  root.addEventListener('click', handleClick);
  root.addEventListener('keydown', function (e) {
    if (e.target.closest('.elementrify-accordion__header') && (e.key === 'Enter' || e.key === ' ')) {
      e.preventDefault();
      e.target.closest('.elementrify-accordion__header').click();
    }
  });
})();
</script>
