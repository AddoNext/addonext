<?php
namespace Addonext\Admin;

use Addonext\Migrations\ActivateFreeWidgets;

defined('ABSPATH') or exit;

/**
 * Controller for running migrations
 *
 * @package AddoNext\Admin
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
        if (addonext_pro_class_exists('\Addonext\Pro\Migrations\ActivateProWidgets')) {
            $migrations[] = \Addonext\Pro\Migrations\ActivateProWidgets::class;
        }

        foreach ($migrations as $migration) {
            $migration = addonext_container($migration);

            if (!$migration->is_completed() && $migration->validate()) {
                try {
                    $migration->up();
                    $migration->mark_as_completed();
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
    }
}
