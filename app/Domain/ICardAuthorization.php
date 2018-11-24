<?php

namespace App\Domain;

use App\Models\Card;
use App\Models\Tariff;
use App\Models\Validator;
use Carbon\Carbon;

/**
 * Card authorization. Contains information about authorization date, card and validator.
 */
interface ICardAuthorization
{
    /**
     * Date and time of authorization.
     *
     * @return Carbon
     */
    public function getDate(): Carbon;

    /**
     * Authorized card.
     *
     * @return Card
     */
    public function getCard(): Card;

    /**
     * Validator that authorizes card.
     *
     * @return Validator
     */
    public function getValidator(): Validator;

    /**
     * Payment tariff that was used to pay bus trip.
     *
     * @return Tariff|null
     */
    public function getTariff(): ?Tariff;

    /**
     * Paid bus trip fare.
     *
     * @return integer|null
     */
    public function getPaymentAmount(): ?int;
}
