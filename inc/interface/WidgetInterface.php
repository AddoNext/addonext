<?php
namespace DragNext\Admin\Interfaces;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

interface WidgetInterface {
    public function render();
}