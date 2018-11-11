<?php

namespace App\Domain\Import;

use Illuminate\Database\ConnectionInterface;

/**
 * Parent class for different records from external storage importers.
 */
abstract class ExternalEntitiesImportService
{
    /**
     * External storage connection.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Parent class for different records from external storage importers.
     *
     * @param ConnectionInterface $connection External storage connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * External storage connection.
     *
     * @return ConnectionInterface
     */
    protected function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
