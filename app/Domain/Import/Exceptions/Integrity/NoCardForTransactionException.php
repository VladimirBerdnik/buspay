<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when used in transaction card number wasn't found.
 */
class NoCardForTransactionException extends BusinessLogicIntegrityImportException
{
    /**
     * Card number that wasn't found.
     *
     * @var integer
     */
    protected $cardNumber;

    /**
     * Thrown when used in transaction card number wasn't found.
     *
     * @param integer $cardNumber Card number that wasn't found
     */
    public function __construct(int $cardNumber)
    {
        parent::__construct('Card for transaction not found');
        $this->cardNumber = $cardNumber;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "Card with number [{$this->cardNumber}] for transaction not found";
    }
}
