<?php
namespace Elementrify\Base;

defined('ABSPATH') or exit;

abstract class ExtensionBase
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    abstract public function get_id(): string;

    abstract public function get_title(): string;

    abstract public function get_description(): string;

    abstract public function get_icon(): string;

    abstract public function get_category(): string;

    public function get_style_depends(): array
    {
        return [];
    }

    public function get_script_depends(): array
    {
        return [];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            $this->get_id(),
            ELEMENTRIFY_URL . 'assets/css/extensions/' . $this->get_id() . '.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            $this->get_id(),
            ELEMENTRIFY_URL . 'assets/js/extensions/' . $this->get_id() . '.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }
}
