<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when multiple replenishment with same external identifier detected.
 */
class TooManyReplenishmentWithExternalIdException extends BusinessLogicIntegrityImportException
{
    /**
     * External identifier for which multiple replenishment found.
     *
     * @var integer
     */
    private $externalId;

    /**
     * Thrown when multiple replenishment with same external identifier detected.
     *
     * @param integer $externalId External identifier for which multiple replenishment found
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Too many replenishment with same external id');
        $this->externalId = $externalId;
    }

    /**
     * External identifier for which multiple replenishment found.
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
        return "For external id [{$this->getExternalId()}] few replenishment found";
    }
}
