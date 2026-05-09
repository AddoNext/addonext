<?php
namespace Elementrify\Admin;

use Elementrify\Migrations\ActivateProWidgets;
use Elementrify\Migrations\ActivateFreeWidgets;

defined('ABSPATH') or exit;

/**
 * Controller for running migrations
 *
 * @package Elementrify\Admin
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
        ActivateProWidgets::class
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
        foreach ($this->migrations as $migration) {
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
