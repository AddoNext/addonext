<?php
namespace DragNext\Base;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

/**
 * Base class for DragNext blocks
 */
abstract class BlockBase
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
     * Get the block ID
     *
     * @return string
     */
    abstract protected function get_id(): string;

    /**
     * Get the block title
     *
     * @return string
     */
    abstract protected function get_title(): string;

    /**
     * Get the block category
     *
     * @return array
     */
    protected function get_category(): array
    {
        return [ 'general' ];
    }

    /**
     * Get the block icon
     *
     * @supports: fontawesome, dashicon
     *
     * @return string
     */
    protected function get_icon(): string
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
    protected function get_keywords(): array
    {
        return [  ];
    }

    /**
     * Register block to controller
     *
     * @return void
     */
    public function register(): void
    {
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
    abstract public function render(): void;
}
