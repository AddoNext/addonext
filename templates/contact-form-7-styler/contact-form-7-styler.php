<?php
/**
 * Contact Form 7 Styler template.
 *
 * @var \Addonext\Widgets\ContactForm7Styler\ContactForm7Styler $this
 */

defined('ABSPATH') || exit;

$addonext_form_id = isset($addonext_form_id) ? (int) $addonext_form_id : 0;

$addonext_is_elementor_edit = class_exists('\Elementor\Plugin')
    && \Elementor\Plugin::$instance->editor
    && \Elementor\Plugin::$instance->editor->is_edit_mode();

?>
<div <?php $this->print_render_attribute_string('root'); ?>>
    <div class="addonext-cf7-styler-inner">
        <?php
        if ($addonext_form_id <= 0) {
            if ($addonext_is_elementor_edit) {
                echo '<p class="addonext-cf7-styler__placeholder">' . esc_html__('Choose a Contact Form 7 form in the widget settings.', 'addonext') . '</p>';
            }
        } elseif (!\Addonext\Widgets\ContactForm7Styler\ContactForm7Styler::is_cf7_active()) {
            if ($addonext_is_elementor_edit) {
                echo '<p class="addonext-cf7-styler__placeholder">' . esc_html__('Contact Form 7 is not active.', 'addonext') . '</p>';
            }
        } else {
            if (function_exists('wpcf7_enqueue_scripts')) {
                wpcf7_enqueue_scripts();
            }
            echo do_shortcode('[contact-form-7 id="' . absint($addonext_form_id) . '" html_class="addonext-cf7-styler-form"]');
        }
        ?>
    </div>
</div>
