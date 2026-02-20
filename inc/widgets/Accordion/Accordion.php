<?php
namespace Elementrify\Widgets\Accordion;

use Elementrify\Base\WidgetBase;

defined( 'ABSPATH' ) or exit;

class Accordion extends WidgetBase
{
    public function get_category(): array
    {
        return [ 'elementrify' ];
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
