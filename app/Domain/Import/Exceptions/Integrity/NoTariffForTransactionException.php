<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when tariff where card was authorized not found.
 */
class NoTariffForTransactionException extends BusinessLogicIntegrityImportException
{
    /**
     * Tariff identifier that wasn't found.
     *
     * @var integer
     */
    protected $tariffId;

    /**
     * Thrown when tariff where card was authorized not found
     *
     * @param integer $tariffId Tariff identifier that wasn't found
     */
    public function __construct(int $tariffId)
    {
        parent::__construct('No tariff by identifier found');
        $this->tariffId = $tariffId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "No validators with serial number [{$this->tariffId}] found";
    }
}
