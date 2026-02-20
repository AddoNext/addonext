<?php
namespace Elementrify\Widgets\Button;

use Elementrify\Base\WidgetBase;

defined( 'ABSPATH' ) or exit;

class Button extends WidgetBase
{
    public function get_category(): array
    {
        return [ 'elementrify' ];
    }

    public function get_icon(): string
    {
        return 'fa fa-button';
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
