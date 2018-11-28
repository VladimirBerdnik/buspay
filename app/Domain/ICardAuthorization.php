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
     * Authorized card serial number.
     *
     * @return integer
     */
    public function getCardSerialNumber(): int;

    /**
     * Authorized card.
     *
     * @return Card
     */
    public function getCard(): Card;

    /**
     * Serial number of validator that authorized card.
     *
     * @return string
     */
    public function getValidatorSerialNumber(): string;

    /**
     * Validator that authorizes card.
     *
     * @return Validator
     */
    public function getValidator(): Validator;

    /**
     * Payment tariff identifier that was used to pay bus trip.
     *
     * @return integer|null
     */
    public function getTariffIdentifier(): ?int;

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

    /**
     * Returns card authorization details as array.
     *
     * @return string[]
     */
    public function toArray(): array;
}
