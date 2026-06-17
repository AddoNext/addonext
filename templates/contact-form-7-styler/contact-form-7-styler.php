<?php
/**
 * Contact Form 7 Styler template.
 *
 * @var \Veloria\Widgets\ContactForm7Styler\ContactForm7Styler $this
 */

defined('ABSPATH') || exit;

$veloria_form_id = isset($veloria_form_id) ? (int) $veloria_form_id : 0;

$veloria_is_elementor_edit = class_exists('\Elementor\Plugin')
    && \Elementor\Plugin::$instance->editor
    && \Elementor\Plugin::$instance->editor->is_edit_mode();

?>
<div <?php $this->print_render_attribute_string('root'); ?>>
    <div class="veloria-cf7-styler-inner">
        <?php
        if ($veloria_form_id <= 0) {
            if ($veloria_is_elementor_edit) {
                echo '<p class="veloria-cf7-styler__placeholder">' . esc_html__('Choose a Contact Form 7 form in the widget settings.', 'veloria') . '</p>';
            }
        } elseif (!\Veloria\Widgets\ContactForm7Styler\ContactForm7Styler::is_cf7_active()) {
            if ($veloria_is_elementor_edit) {
                echo '<p class="veloria-cf7-styler__placeholder">' . esc_html__('Contact Form 7 is not active.', 'veloria') . '</p>';
            }
        } else {
            if (function_exists('wpcf7_enqueue_scripts')) {
                wpcf7_enqueue_scripts();
            }
            echo do_shortcode('[contact-form-7 id="' . absint($veloria_form_id) . '" html_class="veloria-cf7-styler-form"]');
        }
        ?>
    </div>
</div>
