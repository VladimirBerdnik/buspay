<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when replenishment in external storage doesn't match imported data.
 */
class ReplenishmentMismatchException extends BusinessLogicIntegrityImportException
{
    /**
     * External identifier of replenishment that doesn't match imported data.
     *
     * @var integer
     */
    protected $externalId;

    /**
     * Thrown when replenishment in external storage doesn't match imported data.
     *
     * @param integer $externalId External identifier of replenishment that doesn't match imported data
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Replenishment data does not match data in external storage');
        $this->externalId = $externalId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "Replenishment data with external ID [{$this->externalId}] does not match data in external storage";
    }
}
