<?php
namespace DragNext\Widgets\Button;

use DragNext\Base\DGN_Base;

defined( 'ABSPATH' ) or exit;

class Button extends DGN_Base
{
    public function get_category(): array
    {
        return [ 'general' ];
    }

    public function get_icon(): string
    {
        return 'fa fa-bars';
    }

    public function get_name(): string
    {
        return 'button';
    }

    public function get_title(): string
    {
        return 'Button';
    }
}
