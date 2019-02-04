<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when multiple transactions with same external identifier detected.
 */
class TooManyTransactionWithExternalIdException extends BusinessLogicIntegrityImportException
{
    /**
     * External identifier for which multiple transactions found.
     *
     * @var integer
     */
    protected $externalId;

    /**
     * Thrown when multiple transactions with same external identifier detected.
     *
     * @param integer $externalId External identifier for which multiple transactions found
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Too many transactions with same external id');
        $this->externalId = $externalId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "For external id [{$this->externalId}] few transactions found";
    }
}
