<?php
namespace Veloria\Admin;

use Veloria\Migrations\ActivateFreeWidgets;

defined('ABSPATH') or exit;

/**
 * Controller for running migrations
 *
 * @package Veloria\Admin
 */
class MigrationController
{
    /**
     * List of migrations to run
     *
     * @var array<class-string<MigrationBase>>
     */
    private $migrations = [
        ActivateFreeWidgets::class,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'run_migrations']);
    }

    /**
     * Run migrations
     *
     * @return void
     */
    public function run_migrations(): void
    {
        $migrations = $this->migrations;
        if (class_exists('\Veloria\Pro\Migrations\ActivateProWidgets')) {
            $migrations[] = \Veloria\Pro\Migrations\ActivateProWidgets::class;
        }

        foreach ($migrations as $migration) {
            $migration = emty_container($migration);

            if (!$migration->is_completed() && $migration->validate()) {
                try {
                    $migration->up();
                    $migration->mark_as_completed();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }
}
