<?php

namespace App\Domain\Exceptions\Constraint\Payment;

use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use App\Models\Card;
use App\Models\Tariff;
use App\Models\TariffFare;
use Carbon\Carbon;

/**
 * Thrown when payment by card was performed with invalid payment amount.
 */
class InvalidPaymentAmountException extends BusinessLogicIntegrityException
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
     * Tariff that should be payed.
     *
     * @var Tariff
     */
    protected $tariff;

    /**
     * Payed amount.
     *
     * @var int
     */
    protected $amount;

    /**
     * Expected tariff fare.
     *
     * @var TariffFare
     */
    protected $tariffFare;

    /**
     * Thrown when payment by card was performed with invalid payment amount.
     *
     * @param Card $card Card that should pay
     * @param Tariff $tariff Tariff that should be payed
     * @param int $amount Payed amount
     * @param TariffFare $tariffFare Expected tariff fare
     * @param Carbon $date Date of expected payment
     */
    public function __construct(Card $card, Tariff $tariff, int $amount, TariffFare $tariffFare, Carbon $date)
    {
        parent::__construct('Invalid payment amount');
        $this->card = $card;
        $this->date = $date;
        $this->tariff = $tariff;
        $this->amount = $amount;
        $this->tariffFare = $tariffFare;
    }
}
