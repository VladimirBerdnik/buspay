<?php

namespace App\Domain\Exceptions\Integrity;

/**
 * Thrown when mentioned in replenishment card number wasn't found.
 */
class NoCardForReplenishmentException extends BusinessLogicIntegrityException
{
    /**
     * Card number that wasn't found.
     *
     * @var integer
     */
    private $cardNumber;

    /**
     * Thrown when mentioned in replenishment card number wasn't found.
     *
     * @param integer $cardNumber Card number that wasn't found
     */
    public function __construct(int $cardNumber)
    {
        parent::__construct('Card for replenishment not found');
        $this->cardNumber = $cardNumber;
    }

    /**
     * Card number that wasn't found.
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
        return "Card with number {$this->getCardNumber()} for replenishment not found";
    }
}
