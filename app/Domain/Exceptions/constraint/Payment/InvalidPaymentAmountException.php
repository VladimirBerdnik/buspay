<?php

namespace App\Domain\Exceptions\Constraint\Payment;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Models\Card;
use App\Models\Tariff;
use App\Models\TariffFare;
use Carbon\Carbon;

/**
 * Thrown when payment by card was performed with invalid payment amount.
 */
class InvalidPaymentAmountException extends BusinessLogicConstraintException
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
     * Tariff that should be payed.
     *
     * @var Tariff
     */
    private $tariff;

    /**
     * Payed amount.
     *
     * @var int
     */
    private $amount;

    /**
     * Expected tariff fare.
     *
     * @var TariffFare
     */
    private $tariffFare;

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

    /**
     * Tariff that should be payed.
     *
     * @return Tariff
     */
    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    /**
     * Payed amount.
     *
     * @return integer
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Expected tariff fare.
     *
     * @return TariffFare
     */
    public function getTariffFare(): TariffFare
    {
        return $this->tariffFare;
    }
}
