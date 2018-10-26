<?php

use Illuminate\Database\Schema\Blueprint;

/**
 * Migrations helper that can add activity period fields to table.
 */
trait ActivityPeriodMigrationHelper
{
    /**
     * Adds activity period fields to table.
     *
     * @param Blueprint $table Table to add activity period fields to
     * @param bool $withIndex Automatically add index for dates
     */
    private function activityPeriod(Blueprint $table, bool $withIndex = true): void
    {
        $table->timestamp('active_from')->nullable(false)->comment('Start date of activity period of this record');
        $table->timestamp('active_to')->nullable()->comment('End date of activity period of this record');

        if ($withIndex) {
            $table->index(['active_from', 'active_to']);
        }
    }
}
