<?php
namespace DragNext\Widgets\Accordion;

use DragNext\Base\DGN_Base;

defined( 'ABSPATH' ) or exit;

class Accordion extends DGN_Base
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
        return 'accordion';
    }

    public function get_title(): string
    {
        return 'Accordion';
    }
}
