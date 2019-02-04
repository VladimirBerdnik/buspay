<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when multiple validators with same external identifier detected.
 */
class TooManyValidatorsWithExternalIdException extends BusinessLogicIntegrityImportException
{
    /**
     * External identifier for which multiple validators found.
     *
     * @var integer
     */
    protected $externalId;

    /**
     * Thrown when multiple validators with same external identifier detected.
     *
     * @param integer $externalId External identifier for which multiple validators found
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Too many validators with same external id');
        $this->externalId = $externalId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "For external id [{$this->externalId}] few validators found";
    }
}
