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
    protected $card;

    /**
     * Date of unexpected payment.
     *
     * @var Carbon
     */
    protected $date;

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
}
