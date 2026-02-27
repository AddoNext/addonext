<?php
namespace Elementrify\Widgets\ModalPopup;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ModalPopup extends WidgetBase
{
    public function get_icon(): string { return 'eicon-lightbox elementrify-icon'; }
    public function get_name(): string { return 'elementrify-modal-popup'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Modal Popup', 'elementrify'); }
    public function get_description(): string { return __('Custom popup windows', 'elementrify'); }
    public function get_brand_color(): string { return '#7c3aed'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('trigger_text', ['label' => __('Trigger text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Open modal', 'elementrify')]);
        $this->add_control('modal_content', ['label' => __('Modal content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Modal content here.', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/modal-popup/modal-popup.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-modal"><button type="button" class="elementrify-modal__trigger">{{{ settings.trigger_text }}}</button><div class="elementrify-modal__overlay"><div class="elementrify-modal__content">{{{ settings.modal_content }}}</div></div></div><?php
    }
}
