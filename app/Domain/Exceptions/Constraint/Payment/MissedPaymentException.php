<?php

namespace App\Domain\Exceptions\Constraint\Payment;

use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use App\Models\Card;
use Carbon\Carbon;

/**
 * Thrown when payment by card expected but not performed.
 */
class MissedPaymentException extends BusinessLogicIntegrityException
{
    /**
     * Card that should pay.
     *
     * @var Card
     */
    protected $card;

    /**
     * Date of expected payment.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Thrown when payment by card expected but not performed.
     *
     * @param Card $card Card that should pay
     * @param Carbon $date Date of expected payment
     */
    public function __construct(Card $card, Carbon $date)
    {
        parent::__construct('No payment by card when expected');
        $this->card = $card;
        $this->date = $date;
    }
}
