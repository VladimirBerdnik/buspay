<?php

namespace Utils;

use DB;
use Illuminate\Database\Migrations\Migration;

/**
 * Base migration class that can add comment to table.
 */
abstract class CommentsTablesMigration extends Migration
{
    /**
     * Adds comment to table.
     *
     * @param string $table Name of table where need to add table comment
     * @param string|null $comment New comment of the table
     *
     * @return void
     */
    protected function commentTable(string $table, ?string $comment): void
    {
        $connection = DB::getDefaultConnection();
        switch ($connection) {
            case 'pgsql':
                $comment = !$comment ? 'NULL' : "'{$comment}'";
                $statement = "COMMENT ON TABLE {$table} IS {$comment}";
                break;
            case 'mysql':
            default:
                $statement = "ALTER TABLE `{$table}` comment '{$comment}'";
        }
        DB::statement($statement);
    }
}
