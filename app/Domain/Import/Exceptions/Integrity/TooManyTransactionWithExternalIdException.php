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
    private $externalId;

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
     * External identifier for which multiple transactions found.
     *
     * @return integer
     */
    public function getExternalId(): int
    {
        return $this->externalId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "For external id [{$this->getExternalId()}] few transactions found";
    }
}
