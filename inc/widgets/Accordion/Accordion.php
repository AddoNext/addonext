<?php
namespace Elementrify\Widgets\Accordion;

use Elementrify\Base\WidgetBase;

defined('ABSPATH') or exit;

class Accordion extends WidgetBase
{
    public function get_admin_category(): string
    {
        return 'content';
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

    public function get_description(): string
    {
        return __('Advanced accordion with multiple items', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }
}
