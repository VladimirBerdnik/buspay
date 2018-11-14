<?php

namespace App\Domain\Exceptions\Integrity;

/**
 * Thrown when multiple validators with same external identifier detected.
 */
class TooManyValidatorsWithExternalIdException extends BusinessLogicIntegrityException
{
    /**
     * External identifier for which multiple validators found.
     *
     * @var int
     */
    private $externalId;

    /**
     * Thrown when multiple validators with same external identifier detected.
     *
     * @param int $externalId External identifier for which multiple validators found
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Too many validators with same external id');
        $this->externalId = $externalId;
    }

    /**
     * External identifier for which multiple validators found.
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
        return "For external id {$this->getExternalId()} few validators found";
    }
}
