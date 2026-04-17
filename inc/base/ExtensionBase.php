<?php
namespace Elementrify\Base;

defined('ABSPATH') or exit;

/**
 * Base class for Elementrify extensions
 *
 * @since 1.0.0
 * @package Elementrify
 */
abstract class ExtensionBase
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Get the extension unique ID
     *
     * @return string
     */
    abstract public function get_id(): string;

    /**
     * Get the extension title
     *
     * @return string
     */
    abstract public function get_title(): string;

    /**
     * Get the extension description
     *
     * @return string
     */
    abstract public function get_description(): string;

    /**
     * Get the extension icon
     *
     * @return string
     */
    abstract public function get_icon(): string;

    /**
     * Get the extension category
     *
     * @return string
     */
    abstract public function get_category(): string;

    /**
     * Get the extension style dependencies
     *
     * @return array
     */
    public function get_style_depends(): array
    {
        return [];
    }

    /**
     * Get the extension script dependencies
     *
     * @return array
     */
    public function get_script_depends(): array
    {
        return [];
    }

    /**
     * Enqueue the extension assets
     *
     * @return void
     */
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

    /**
     * Get the extension fields
     *
     * @return array
     */
    public function get_fields(): array
    {
        return [];
    }

    /**
     * Documentation URL for this extension
     *
     * @return string
     */
    public function get_doc_url(): string
    {
        return '';
    }

    /**
     * Demo or external showcase URL
     *
     * @return string
     */
    public function get_demo_url(): string
    {
        return '';
    }

    /**
     * Whether the extension exposes configurable settings
     *
     * @return bool
     */
    public function has_settings(): bool
    {
        return !empty($this->get_fields());
    }

    /**
     * Check if the extension is popular
     *
     * @return bool
     */
    public function is_popular(): bool
    {
        return false;
    }

    /**
     * Get the date the extension was launched
     *
     * @return string
     */
    abstract public function get_date_launched(): string;

    /**
     * Get the date the extension was updated
     *
     * @return string
     */
    abstract public function get_date_updated(): string;

    /**
     * Get the required plan for the extension
     *
     * @return string
     */
    public function required_plan(): string
    {
        return 'free';
    }
}
