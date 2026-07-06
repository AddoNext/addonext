<?php
namespace Addonext\Admin\Interfaces;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

/**
 * Widget Interface
 * 
 * @package AddoNext
 */
interface WidgetInterface {
    public function get_slug(): string;
    public function get_icon(): string;
    public function render(): void;
}