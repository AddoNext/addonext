<?php
defined('ABSPATH') || exit;

$this->add_render_attribute('wrapper', 'class', 'addonext-spacer');
?>
<div <?php $this->print_render_attribute_string('wrapper'); ?>></div>
