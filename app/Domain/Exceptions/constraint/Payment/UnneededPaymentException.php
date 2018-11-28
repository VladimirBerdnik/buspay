<?php

namespace App\Domain\Exceptions\Constraint\Payment;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Models\Card;
use Carbon\Carbon;

/**
 * Thrown when payment for not paymentable card found.
 */
class UnneededPaymentException extends BusinessLogicConstraintException
{
    /**
     * Card that shouldn't pay.
     *
     * @var Card
     */
    private $card;

    /**
     * Date of unexpected payment.
     *
     * @var Carbon
     */
    private $date;

    /**
     * Thrown when payment for not paymentable card found.
     *
     * @param Card $card Card that shouldn't pay
     * @param Carbon $date Date of unexpected payment
     */
    public function __construct(Card $card, Carbon $date)
    {
        parent::__construct('Unpaymentable card payment');
        $this->card = $card;
        $this->date = $date;
    }

    /**
     * Card that shouldn't pay.
     *
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * Date of unexpected payment.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }
}
