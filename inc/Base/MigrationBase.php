<?php
namespace Veloria\Base;

use Veloria\Utils\OptionsHelper;

defined('ABSPATH') or exit;

/**
 * Base class for migrations
 *
 * @package Veloria\Base
 */
abstract class MigrationBase
{
    /**
     * Name of the migration
     *
     * @return string
     */
    public function get_name(): string
    {
        return strtolower(basename(get_called_class()));
    }

    /**
     * Run the migration
     *
     * @return void
     */
    abstract public function up(): void;

    /**
     * Rollback the migration
     *
     * @return void
     */
    abstract public function down(): void;

    /**
     * Check if the migration is completed
     *
     * @return bool
     */
    public function is_completed(): bool
    {
        $migrations = OptionsHelper::get_option('migrations', []);

        return in_array($this->get_name(), $migrations);
    }

    /**
     * Validate the migration, if validated migration runs
     *
     * @return bool
     */
    abstract public function validate(): bool;

    /**
     * After migration is completed, mark the migration as completed
     *
     * @return void
     */
    public function mark_as_completed(): void
    {
        $migrations = OptionsHelper::get_option('migrations', []);
        OptionsHelper::set_option('migrations', array_merge($migrations, [$this->get_name()]));
    }
}
