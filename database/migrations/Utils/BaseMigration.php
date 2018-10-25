<?php

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;

/**
 * Expands laravel migration class with ability to create query builders.
 */
abstract class MigrationWithQueryBuilder extends Migration
{
    /**
     * Connection instance.
     *
     * @var ConnectionInterface
     */
    protected $connectionInstance;

    /**
     * Expands laravel migration class with ability to create query builders.
     */
    public function __construct()
    {
        $this->connectionInstance = app(ConnectionInterface::class);
    }

    /**
     * Creates query builder for given table.
     *
     * @param string $table Table for which needs to create builder
     *
     * @return Builder
     */
    public function newQuery(string $table): Builder
    {
        return $this->connectionInstance->table($table);
    }
}
