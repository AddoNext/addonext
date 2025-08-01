<?php
namespace DragNext\Base;

use Elementor\Widget_Base;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

/**
 * Base class for DragNext blocks
 */
abstract class DGN_Base extends Widget_Base
{
    /**
     * Register block and controls
     *
     * @return void
     */
    public function __construct()
    {
        add_action( 'init', [ $this, 'register' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Get the block title
     *
     * @return string
     */
    abstract public function get_title(): string;

    /**
     * Get the block category
     *
     * @return array
     */
    protected function get_category(): array
    {
        return [ 'dragnext' ];
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
        return [ $this->get_name(), $this->get_title()  ];
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
    protected function enqueue_assets(): void
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
}
