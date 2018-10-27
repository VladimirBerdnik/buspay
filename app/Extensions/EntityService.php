<?php

namespace App\Extensions;

use Illuminate\Database\ConnectionInterface;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Throwable;

/**
 * Base service that can handle related with domain models actions.
 */
abstract class EntityService
{
    use RepositoryRetrievingMethodsProxyTrait;

    /**
     * Handled entities records storage.
     *
     * @var IRepository
     */
    private $repository;

    /**
     * Storage connection interface.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Base service that can handle related with domain models actions.
     *
     * @param ConnectionInterface $connection Storage connection interface
     * @param IRepository $repository Handled entities records storage
     */
    public function __construct(ConnectionInterface $connection, IRepository $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
    }

    /**
     * Returns repository that can deal with handled models.
     *
     * @return IRepository
     */
    protected function getRepository(): IRepository
    {
        return $this->repository;
    }

    /**
     * Performs action in storage transaction.
     *
     * @param callable $transactionalCall The function that should be called
     *
     * @return mixed Result of the called function
     *
     * @throws Throwable
     */
    protected function handleTransaction(callable $transactionalCall)
    {
        try {
            $this->connection->beginTransaction();
            $result = $transactionalCall();
        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
        $this->connection->commit();

        return $result;
    }
}
