<?php
namespace Elementrify\Extensions\SmoothScroll;

use Elementrify\Base\ExtensionBase;

defined('ABSPATH') or exit;

class SmoothScroll extends ExtensionBase
{
    public function get_id(): string
    {
        return 'smooth-scroll';
    }

    public function get_title(): string
    {
        return __('Smooth Scroll', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Smooth scroll for your website', 'elementrify');
    }

    public function get_icon(): string
    {
        return 'eicon-smooth-scroll elementrify-icon';
    }

    public function get_category(): string
    {
        return 'content';
    }

    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    public function get_date_updated(): string
    {
        return '2026-04-18';
    }
}
