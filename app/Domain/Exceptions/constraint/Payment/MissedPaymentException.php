<?php

namespace App\Domain\Exceptions\Constraint\Payment;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Models\Card;
use Carbon\Carbon;

/**
 * Thrown when payment by card expected but not performed.
 */
class MissedPaymentException extends BusinessLogicConstraintException
{
    /**
     * Card that should pay.
     *
     * @var Card
     */
    private $card;

    /**
     * Date of expected payment.
     *
     * @var Carbon
     */
    private $date;

    /**
     * Thrown when payment by card expected but not performed.
     *
     * @param Card $card Card that should pay
     * @param Carbon $date Date of expected payment
     */
    public function __construct(Card $card, Carbon $date)
    {
        parent::__construct('No payment by card');
        $this->card = $card;
        $this->date = $date;
    }

    /**
     * Card that should pay.
     *
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * Date of expected payment.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }
}
