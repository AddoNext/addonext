<?php
namespace Veloria\Extensions\SmoothScroll;

use Veloria\Base\ExtensionBase;
use Veloria\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Smooth wheel scrolling for the front using Lenis.
 *
 * @package Veloria
 * @since 1.0.0
 */
class SmoothScroll extends ExtensionBase
{
    /**
     * Lenis library version (bundled under assets/).
     */
    private const LENIS_VERSION = '1.1.18';

    /**
     * @return string
     */
    public function get_id(): string
    {
        return 'smooth-scroll';
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return __('Smooth Scroll', 'veloria');
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return __('Smooth mouse-wheel scrolling for the whole page.', 'veloria');
    }

    /**
     * @return string
     */
    public function get_icon(): string
    {
        return 'eicon-smooth-scroll veloria-icon';
    }

    /**
     * @return string
     */
    public function get_category(): string
    {
        return 'content';
    }

    /**
     * @return string
     */
    public function get_date_launched(): string
    {
        return '2026-04-18';
    }

    /**
     * @return string
     */
    public function get_date_updated(): string
    {
        return '2026-04-18';
    }

    public function is_popular(): bool
    {
        return true;
    }

    /**
     * Register and enqueue Lenis plus the bootstrap script on the front end only.
     *
     * @return void
     */
    public function enqueue_assets(): void
    {
        if (is_admin()) {
            return;
        }

        $active = OptionsHelper::get_option('active_extensions', []);

        if (!is_array($active) || !in_array($this->get_id(), $active, true)) {
            return;
        }

        $base = VELORIA_URL . 'assets/';

        wp_register_style(
            'veloria-lenis',
            $base . 'css/extensions/lenis.css',
            [],
            self::LENIS_VERSION
        );

        wp_register_style(
            $this->get_id(),
            $base . 'css/extensions/' . $this->get_id() . '.css',
            ['veloria-lenis'],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-lenis',
            $base . 'js/extensions/lenis.min.js',
            [],
            self::LENIS_VERSION,
            true
        );

        wp_register_script(
            $this->get_id(),
            $base . 'js/extensions/' . $this->get_id() . '.js',
            ['veloria-lenis'],
            VELORIA_VERSION,
            true
        );

        wp_enqueue_style($this->get_id());
        wp_enqueue_script($this->get_id());
    }
}
