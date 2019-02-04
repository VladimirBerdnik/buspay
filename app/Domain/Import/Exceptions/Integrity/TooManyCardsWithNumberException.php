<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when multiple cards with same number detected.
 */
class TooManyCardsWithNumberException extends BusinessLogicIntegrityImportException
{
    /**
     * Card number with which multiple cards found.
     *
     * @var int
     */
    protected $cardNumber;

    /**
     * Thrown when multiple cards with same number detected.
     *
     * @param int $externalId Card number with which multiple cards found
     */
    public function __construct(int $externalId)
    {
        parent::__construct('Too many cards with same number');
        $this->cardNumber = $externalId;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "For card number [{$this->cardNumber}] few cards found";
    }
}
