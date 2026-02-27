<?php
defined('ABSPATH') || exit;

$this->add_render_attribute('wrapper', 'class', 'elementrify-divider-wrapper');
$this->add_render_attribute('divider', 'class', 'elementrify-divider');
$this->add_render_attribute('divider', 'class', 'elementrify-divider--' . esc_attr($this->get_settings_for_display('style')));
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>>
    <hr <?php $this->print_render_attribute_string('divider'); ?>>
</div>
