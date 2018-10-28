<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Tariff Fare details.
 *
 * @property-read integer $tariff_id Tariff identifier to which this fare belongs
 * @property-read integer $card_type_id Card type identifier to which this fare applicable
 * @property-read integer $amount Road trip fare
 */
class TariffFareData extends Dto
{
    public const TARIFF_ID = 'tariff_id';
    public const CARD_TYPE_ID = 'card_type_id';
    public const AMOUNT = 'amount';

    /**
     * Tariff identifier to which this fare belongs.
     *
     * @var integer
     */
    protected $tariff_id;

    /**
     * Card type identifier to which this fare applicable.
     *
     * @var integer
     */
    protected $card_type_id;

    /**
     * Road trip fare.
     *
     * @var integer
     */
    protected $amount;
}
