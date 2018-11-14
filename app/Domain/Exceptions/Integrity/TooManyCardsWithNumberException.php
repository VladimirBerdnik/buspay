<?php

namespace App\Domain\Exceptions\Integrity;

/**
 * Thrown when multiple cards with same number detected.
 */
class TooManyCardsWithNumberException extends BusinessLogicIntegrityException
{
    /**
     * Card number with which multiple cards found.
     *
     * @var int
     */
    private $cardNumber;

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
     * Card number with which multiple cards found.
     *
     * @return integer
     */
    public function getCardNumber(): int
    {
        return $this->cardNumber;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "For card number {$this->getCardNumber()} few cards found";
    }
}
