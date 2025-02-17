<?php

namespace App\Domain\Import;

use App\Extensions\ErrorsReporter;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

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
     * Errors and messages reporter.
     *
     * @var ErrorsReporter
     */
    private $errorsReporter;

    /**
     * External items storage name.
     *
     * @var string
     */
    protected $externalStorageName;

    /**
     * Parent class for different records from external storage importers.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ErrorsReporter $errorsReporter Errors and messages reporter
     */
    public function __construct(ConnectionInterface $connection, ErrorsReporter $errorsReporter)
    {
        $this->connection = $connection;
        $this->errorsReporter = $errorsReporter;
    }

    /**
     * Returns errors and messages reporter.
     *
     * @return ErrorsReporter
     */
    public function getErrorsReporter(): ErrorsReporter
    {
        return $this->errorsReporter;
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

    /**
     * Returns external storage items query builder.
     *
     * @return Builder
     */
    protected function getStorage(): Builder
    {
        return $this->getConnection()->table($this->externalStorageName);
    }
}
