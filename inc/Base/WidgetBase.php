<?php
namespace Veloria\Base;

use Elementor\Widget_Base;

defined('ABSPATH') || die('No direct access allowed');

/**
 * Base class for Veloria blocks
 */
abstract class WidgetBase extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        if (is_array($data) && !empty($data) && (!isset($data['settings']) || !is_array($data['settings']))) {
            $data['settings'] = [];
        }
        parent::__construct($data, $args);
        add_action('init', [$this, 'register']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register(): void
    {
    }

    protected function init($data): void
    {
        if (!isset($data['settings']) || !is_array($data['settings'])) {
            $data['settings'] = [];
        }
        parent::init($data);
    }

    /**
     * Get the block title
     *
     * @return string
     */
    public function get_title(): string
    {
        return '';
    }

    /**
     * Elementor panel category (must match slug registered in WidgetManager).
     *
     * @return array<int, string>
     */
    public function get_categories(): array
    {
        return ['veloria'];
    }

    /**
     * Get the admin dashboard category slug for grouping
     *
     * @return string
     */
    public function get_admin_category(): string
    {
        return 'content';
    }

    /**
     * Get the block icon
     *
     * @supports: fontawesome, dashicon
     *
     * @return string
     */
    public function get_icon(): string
    {
        return '';
    }

    /**
     * Get the block description
     *
     * @return string
     */
    protected function get_description(): string
    {
        return '';
    }

    /**
     * Get the block keywords
     *
     * @return array
     */
    public function get_keywords(): array
    {
        return [$this->get_name(), $this->get_title()];
    }

    /**
     * Register block controls
     * Including fields, tabs
     *
     * @return void
     */
    public function register_controls(): void
    {
    }

    /**
     * Enqueue styles and scripts
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
    }

    /**
     * Render the block
     *
     * @return void
     */
    public function render(): void
    {
    }

    /**
     * Get the brand color
     *
     * @return string
     */
    abstract public function get_brand_color(): string;

    /**
     * Get the required plan for the widget
     *
     * @return string
     */
    public function get_required_plan(): string
    {
        return 'free';
    }
}
